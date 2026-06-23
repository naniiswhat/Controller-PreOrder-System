<?php

function controller_image_upload_root()
{
    return realpath(__DIR__ . "/../frontend/assets") . "/uploads/controllers";
}

function controller_image_relative_root()
{
    return "assets/uploads/controllers";
}

function controller_upload_error_message($code)
{
    $messages = [
        UPLOAD_ERR_INI_SIZE => "The uploaded image is larger than the server allows.",
        UPLOAD_ERR_FORM_SIZE => "The uploaded image is too large.",
        UPLOAD_ERR_PARTIAL => "The image upload did not finish.",
        UPLOAD_ERR_NO_TMP_DIR => "The server upload folder is missing.",
        UPLOAD_ERR_CANT_WRITE => "The server could not save the uploaded image.",
        UPLOAD_ERR_EXTENSION => "The image upload was stopped by a PHP extension.",
    ];

    return $messages[$code] ?? "The image upload failed.";
}

function normalize_uploaded_images($files)
{
    if (!$files || !isset($files['name'])) {
        return [];
    }

    if (!is_array($files['name'])) {
        return [$files];
    }

    $normalized = [];

    foreach ($files['name'] as $index => $name) {
        $normalized[] = [
            'name' => $name,
            'type' => $files['type'][$index] ?? '',
            'tmp_name' => $files['tmp_name'][$index] ?? '',
            'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
            'size' => $files['size'][$index] ?? 0,
        ];
    }

    return $normalized;
}

function next_controller_image_order($conn, $controllerId)
{
    $order = 0;
    $stmt = mysqli_prepare($conn, "SELECT COALESCE(MAX(sort_order), 0) AS next_order FROM controller_images WHERE controller_id=?");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $controllerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $order = (int) ($row['next_order'] ?? 0);
        mysqli_stmt_close($stmt);
    }

    return $order + 1;
}

function save_controller_images($files, $controllerId, $conn)
{
    $uploads = normalize_uploaded_images($files);
    $savedPaths = [];
    $controllerId = (int) $controllerId;
    $sortOrder = next_controller_image_order($conn, $controllerId);

    try {
        foreach ($uploads as $file) {
            if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException(controller_upload_error_message($file['error']));
            }

            if (!is_uploaded_file($file['tmp_name'])) {
                throw new RuntimeException("One selected file was not uploaded correctly.");
            }

            $maxBytes = 5 * 1024 * 1024;

            if ((int) $file['size'] > $maxBytes) {
                throw new RuntimeException("Product images must be 5MB or smaller.");
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            $extensions = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];

            if (!isset($extensions[$mime])) {
                throw new RuntimeException("Only JPG, PNG, WebP, or GIF images are allowed.");
            }

            $folder = controller_image_upload_root() . "/" . $controllerId;

            if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
                throw new RuntimeException("The product image folder could not be created.");
            }

            do {
                $filename = sprintf("controller-%d-%d.%s", $controllerId, $sortOrder, $extensions[$mime]);
                $destination = $folder . "/" . $filename;
                $currentOrder = $sortOrder;
                $sortOrder++;
            } while (file_exists($destination));

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new RuntimeException("A product image could not be saved.");
            }

            $relativePath = controller_image_relative_root() . "/" . $controllerId . "/" . $filename;
            $stmt = mysqli_prepare($conn, "INSERT INTO controller_images (controller_id, image_path, sort_order) VALUES (?, ?, ?)");

            if (!$stmt) {
                delete_controller_image($relativePath);
                throw new RuntimeException("The product image path could not be saved.");
            }

            mysqli_stmt_bind_param($stmt, "isi", $controllerId, $relativePath, $currentOrder);

            if (!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                delete_controller_image($relativePath);
                throw new RuntimeException("The product image path could not be saved.");
            }

            mysqli_stmt_close($stmt);
            $savedPaths[] = $relativePath;
        }
    } catch (RuntimeException $error) {
        foreach ($savedPaths as $path) {
            delete_controller_image($path);
        }

        throw $error;
    }

    return $savedPaths;
}

function delete_controller_image($path)
{
    if (!$path) {
        return;
    }

    $root = realpath(controller_image_upload_root());
    $target = realpath(__DIR__ . "/../frontend/" . $path);

    if ($root) {
        $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    if ($root && $target && str_starts_with($target, $root) && is_file($target)) {
        unlink($target);
    }
}

function delete_controller_images($conn, $controllerId)
{
    $stmt = mysqli_prepare($conn, "SELECT image_path FROM controller_images WHERE controller_id=?");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $controllerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            delete_controller_image($row['image_path']);
        }

        mysqli_stmt_close($stmt);
    }
}
