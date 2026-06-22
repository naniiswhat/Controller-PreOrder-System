<?php
session_start();
include "../includes/db_connect.php";

if (!isset($_SESSION['id'], $_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../frontend/login.php?error=Please log in as a customer to place a pre-order");
    exit();
}

$controller_id = filter_input(INPUT_POST, 'controller_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$redirect = "../frontend/shop.php";

if ($controller_id) {
    $redirect = "../frontend/product.php?id=" . $controller_id;
}

if (!$controller_id || !$quantity || $quantity < 1) {
    header("Location: ../frontend/shop.php?error=Choose a valid quantity");
    exit();
}

mysqli_begin_transaction($conn);

$stmt = mysqli_prepare($conn, "SELECT stock_quantity FROM controllers WHERE controller_id=? FOR UPDATE");
mysqli_stmt_bind_param($stmt, "i", $controller_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$controller = mysqli_fetch_assoc($result);

if (!$controller) {
    mysqli_rollback($conn);
    header("Location: ../frontend/shop.php?error=Controller not found");
    exit();
}

$stock = (int) $controller['stock_quantity'];

if ($stock < $quantity) {
    mysqli_rollback($conn);
    header("Location: {$redirect}&error=Only {$stock} unit(s) available");
    exit();
}

$user_id = (int) $_SESSION['id'];
$insert = mysqli_prepare($conn, "INSERT INTO preorders (user_id, controller_id, quantity, status) VALUES (?, ?, ?, 'pending')");
mysqli_stmt_bind_param($insert, "iii", $user_id, $controller_id, $quantity);

$update = mysqli_prepare($conn, "UPDATE controllers SET stock_quantity = stock_quantity - ? WHERE controller_id=?");
mysqli_stmt_bind_param($update, "ii", $quantity, $controller_id);

if (mysqli_stmt_execute($insert) && mysqli_stmt_execute($update)) {
    mysqli_commit($conn);
    header("Location: {$redirect}&success=Pre-order placed successfully");
    exit();
}

mysqli_rollback($conn);
header("Location: {$redirect}&error=Unable to place pre-order right now");
exit();
?>
