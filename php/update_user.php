<?php
session_start();
include "../includes/db_connect.php";

if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Gunakan Prepared Statement untuk keselamatan
    $stmt = mysqli_stmt_init($conn);
    $sql = "UPDATE users SET username=?, role=? WHERE user_id=?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../home.php?page=users&status=updated");
        } else {
            echo "Update failed: " . mysqli_stmt_error($stmt);
        }
    }
    exit();
} else {
    echo "Invalid request.";
}
?>