<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "controller_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

function get_db_connection()
{
  global $servername, $username, $password, $dbname;

  $connection = mysqli_connect($servername, $username, $password, $dbname);

  if (!$connection) {
    return null;
  }

  mysqli_set_charset($connection, "utf8mb4");

  return $connection;
}
