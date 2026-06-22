<?php
//define info needed to log into local MySQL server
$servername = "localhost"; // since we are running xampp locally
$username = "root";        // xampp default db username
$password = "";            // xampp default db password is blank
$dbname = "controller_db"; // name of our db in phpMyAdmin

// open conn pipe to MySQL using procedural mysqli function
$conn = mysqli_connect($servername, $username, $password, $dbname);

// verify if connection works, stop website immediately if fail.
if (!$conn) {
    // kill script execution and display connection error text
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>