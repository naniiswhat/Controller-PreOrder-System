<?php
include "../includes/db_connect.php";
if (isset($_POST['add_user'])) {
    $user = $_POST['username'];
    // Jika email kosong, tetapkan sebagai NULL supaya tiada ralat duplicate
    $email = !empty($_POST['email']) ? $_POST['email'] : NULL;
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $user, $email, $pass, $role);
    
    if(mysqli_stmt_execute($stmt)){
        header("Location: ../home.php?page=users");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>