<?php
session_start();
include "../includes/db_connect.php";

$loginPage = "../frontend/login.php";
$homePage = "../home.php";

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
        header("Location: {$loginPage}?error=Email Address is Required");
        exit();

    // validate password parameter property status - redirect to index.php if empty
    } else if (empty($password)) {
        header("Location: {$loginPage}?error=Password is Required");
        exit();

    // run structural query logic if all checks pass
    } else {
        
        // 1. Write the query using question marks as placeholders
        $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
        
        // 2. Initialize a secure statement package
        $stmt = mysqli_stmt_init($conn);
        
        // 3. Prepare the statement and check for database errors
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: {$loginPage}?error=Database query failed");
            exit();
        } else {
            // 4. Bind the user input to the question mark ("s" = one string)
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // 5. Execute the secure query
            mysqli_stmt_execute($stmt);
            
            // 6. Grab the result package
            $result = mysqli_stmt_get_result($stmt);
            
            // enforce rigid row presence count rules
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                $stored_password = $row['password'];
                $password_matches = password_verify($password, $stored_password) || hash_equals(md5($password), $stored_password);

                // verify password and db user role match the user's dropdown input selection
                if ($password_matches && $row['role'] == $role) {
                    
                    // assign authorization parameters into persistent session tracking arrays
                    $_SESSION['id'] = $row['user_id'];
                    $_SESSION['name'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['username'] = $row['email'];
                    
                    header("Location: {$homePage}");
                    exit();

                } else if ($password_matches) { // redirect if role strings mismatch
                    header("Location: {$loginPage}?error=Incorrect selected role for this account");
                    exit();
                } else {
                    header("Location: {$loginPage}?error=Incorrect email address or password sequence");
                    exit();
                }
            } else { // redirect if query maps clear out empty
                header("Location: {$loginPage}?error=Incorrect email address or password sequence");
                exit();
            }
        }
    }
} else { // enforce authorization if file is opened directly
    header("Location: {$loginPage}");
    exit();
}
?>
