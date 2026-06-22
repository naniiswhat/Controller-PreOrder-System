<?php
session_start();
include "../includes/db_connect.php";

// check form submission data properties w/ isset function
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {

    // verification filter
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // process incoming data packages using the validation filter defined
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    // validate email parameter property status - redirect to index.php if empty
    if (empty($email)) {
        header("Location: ../index.php?error=Email Address is Required");

    //validate password parameter property status - redirect to index.php if empty
    } else if (empty($password)) {
        header("Location: ../index.php?error=Password is Required");

    // run structural query logic if all checks pass
    } else {

        // hash incoming password to align with db records
        $password = md5($password);
        
        // query records to discover an exact account data row match
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        // enforce rigid row presence count rules
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            // verify db user role matches the user's dropdown input selection
            if ($row['password'] === $password && $row['role'] == $role) {

                // assign authorization parameters into persistent session tracking arrays
                $_SESSION['id'] = $row['user_id'];      // maps to schema user key
                $_SESSION['name'] = $row['username'];    // maps to schema nickname field
                $_SESSION['role'] = $row['role'];        // keeps user role context alive
                $_SESSION['username'] = $row['email'];   // maps tracking credentials cleanly
                
                // redirect to home page container following authorization approval
                header("Location: ../home.php");

            } else { // redirect if role strings mismatch
                header("Location: ../index.php?error=Incorrect selected role for this account");
            }
        } else { // redirect if query maps clear out empty
            header("Location: ../index.php?error=Incorrect email address or password sequence");
        }
    }
} else { // enforce authorization if file is opened directly out of order via browser address bar
    header("Location: ../index.php");
}
?>