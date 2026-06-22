<?php
// fire up the session engine
session_start();

// step into the includes folder to grab the database connection
include "../includes/db_connect.php";

// check if the form submitted all required data fields
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

    // verification filter to scrub malicious code
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // clean the incoming data
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    
    // forcefully assign the customer role
    $role = 'customer'; 

    // check for empty fields just in case
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../register.php?error=All fields are required");
        exit();
    } else {
        
        // step 1: check if the email already exists in the database using a secure prepared statement
        $check_sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $check_sql)) {
            header("Location: ../register.php?error=Database error during check");
            exit();
        }
        
        // bind the email parameter and run the check
        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // if it returns a row, the email is already taken
        if (mysqli_num_rows($result) > 0) {
            header("Location: ../register.php?error=That email address is already in use");
            exit();
        } else {
            
            // step 2: the email is unique, so let's hash the password using md5 to match our system rules
            $hashed_password = md5($password);

            // step 3: insert the new user into the database securely
            $insert_sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt2, $insert_sql)) {
                header("Location: ../register.php?error=Database error during registration");
                exit();
            }

            // bind the 4 strings ("ssss") to the question marks
            mysqli_stmt_bind_param($stmt2, "ssss", $username, $email, $hashed_password, $role);
            
            // execute the insertion
            if (mysqli_stmt_execute($stmt2)) {
                // success! send them to the login screen with a nice message
                header("Location: ../index.php?success=Account created successfully. You can now log in.");
                exit();
            } else {
                header("Location: ../register.php?error=An unknown error occurred during registration");
                exit();
            }
        }
    }
} else {
    // kick them out if they try to access this file directly via the url bar
    header("Location: ../register.php");
    exit();
}
?>