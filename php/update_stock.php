<?php
session_start();
// Pastikan laluan include betul, jika fail ini dalam folder php/, guna ../
include "../includes/db_connect.php"; 

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['model_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Menggunakan Prepared Statement untuk keselamatan (mencegah SQL Injection)
    $stmt = mysqli_stmt_init($conn);
    $sql = "UPDATE controllers SET model_name=?, price=?, stock_quantity=? WHERE controller_id=?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        // "sdii" bermaksud: string (name), double (price), int (stock), int (id)
        mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $stock, $id);
        mysqli_stmt_execute($stmt);
        
        // Redirect balik ke dashboard inventori
        header("Location: ../home.php?page=inventory&status=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>