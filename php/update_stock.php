<?php
session_start();

include "../includes/db_connect.php"; 
require_once __DIR__ . "/image_upload.php";

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../frontend/login.php");
    exit();
}

function inventory_redirect() {
    if ($_SESSION['role'] === 'admin') {
        return "../frontend/dashboard_admin.php";
    }

    return "../frontend/dashboard_staff.php";
}

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['model_name'];
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $saved_paths = [];

    if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $img_id) {
            $del_stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($del_stmt, "DELETE FROM controller_images WHERE image_id=?")) {
                mysqli_stmt_bind_param($del_stmt, "i", $img_id);
                mysqli_stmt_execute($del_stmt);
            }
        }
    }

    // safety
    $stmt = mysqli_stmt_init($conn);
    $updates_description = $description !== null;
    $sql = $updates_description
        ? "UPDATE controllers SET model_name=?, description=?, price=?, stock_quantity=? WHERE controller_id=?"
        : "UPDATE controllers SET model_name=?, price=?, stock_quantity=? WHERE controller_id=?";

    mysqli_begin_transaction($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        if ($updates_description) {
            mysqli_stmt_bind_param($stmt, "ssdii", $name, $description, $price, $stock, $id);
        } else {
            mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $stock, $id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            try {
                $saved_paths = save_controller_images($_FILES['product_images'] ?? null, $id, $conn);
                mysqli_commit($conn);

                // redirect inventory
                header("Location: " . inventory_redirect() . "?status=updated");
                exit();
            } catch (RuntimeException $error) {
                foreach ($saved_paths as $path) {
                    delete_controller_image($path);
                }

                mysqli_rollback($conn);
                header("Location: " . inventory_redirect() . "?error=" . rawurlencode($error->getMessage()));
                exit();
            }
        } else {
            mysqli_rollback($conn);
            echo "Error executing query: " . mysqli_stmt_error($stmt);
        }
    } else {
        mysqli_rollback($conn);
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "Borang not sent correctly.";
}
?>
