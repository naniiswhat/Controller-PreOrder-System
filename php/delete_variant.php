<?php
session_start();
include "../includes/db_connect.php";
require_once __DIR__ . "/image_upload.php";

// Pastikan admin sahaja yang boleh memadam
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $image_paths = [];

    $image_stmt = mysqli_prepare($conn, "SELECT image_path FROM controller_images WHERE controller_id = ?");

    if ($image_stmt) {
        mysqli_stmt_bind_param($image_stmt, "i", $id);
        mysqli_stmt_execute($image_stmt);
        $image_result = mysqli_stmt_get_result($image_stmt);

        while ($image = mysqli_fetch_assoc($image_result)) {
            $image_paths[] = $image['image_path'];
        }

        mysqli_stmt_close($image_stmt);
    }

    // Menggunakan Prepared Statement untuk keselamatan
    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM controllers WHERE controller_id = ?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            foreach ($image_paths as $path) {
                delete_controller_image($path);
            }

            $folder = realpath(__DIR__ . "/../frontend/assets/uploads/controllers/" . (int) $id);

            if ($folder && is_dir($folder)) {
                @rmdir($folder);
            }
        }
        
        // Redirect balik ke dashboard inventori
        header("Location: ../frontend/dashboard_admin.php?status=deleted");
    } else {
        echo "Ralat: " . mysqli_error($conn);
    }
}
exit();
?>
