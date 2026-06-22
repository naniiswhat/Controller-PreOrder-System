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
        exit();

    // validate password parameter property status - redirect to index.php if empty
    } else if (empty($password)) {
        header("Location: ../index.php?error=Password is Required");
        exit();

    // run structural query logic if all checks pass
    } else {
        
        // 1. Write the query using question marks as placeholders
        $sql = "SELECT * FROM users WHERE email=? AND password=?";
        
        // 2. Initialize a secure statement package
        $stmt = mysqli_stmt_init($conn);
        
        // 3. Prepare the statement and check for database errors
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index.php?error=Database query failed");
            exit();
        } else {
            // Note: Keep your hashing here for now to match current DB records,
            // but remember to migrate to password_hash() later for security.
            $hashed_password = md5($password);
            
            // 4. Bind the user inputs to the question marks ("ss" = two strings)
            mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
            
            // 5. Execute the secure query
            mysqli_stmt_execute($stmt);
            
            // 6. Grab the result package
            $result = mysqli_stmt_get_result($stmt);
            
            // enforce rigid row presence count rules
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                // verify db user role matches the user's dropdown input selection
                if ($row['role'] == $role) {
                    
                    // assign authorization parameters into persistent session tracking arrays
                    $_SESSION['id'] = $row['user_id'];
                    $_SESSION['name'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['username'] = $row['email'];
                    
                    header("Location: ../home.php");
                    exit();

                } else { // redirect if role strings mismatch
                    header("Location: ../index.php?error=Incorrect selected role for this account");
                    exit();
                }
            } else { // redirect if query maps clear out empty
                header("Location: ../index.php?error=Incorrect email address or password sequence");
                exit();
            }
        }
    }
} else { // enforce authorization if file is opened directly
    header("Location: ../index.php");
    exit();
}
?>