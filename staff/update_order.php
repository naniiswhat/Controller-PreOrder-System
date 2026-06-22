<?php
session_start();
include_once __DIR__ . "/../includes/db_connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../frontend/login.php");
    exit();
}

if(isset($_POST['order_id']) && isset($_POST['status'])){
    $id = $_POST['order_id'];
    $status = $_POST['status'];
    
    // Gunakan prepared statement untuk keselamatan
    $stmt = mysqli_prepare($conn, "UPDATE preorders SET status=? WHERE order_id=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    
    if(mysqli_stmt_execute($stmt)){
        // Redirect kembali ke frontend staff dashboard
        header("Location: ../frontend/dashboard_staff.php?status=updated");
        exit();
    } else {
        echo "Error updating record.";
    }
}
?>
