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

function ensure_controller_images_table($connection)
{
  mysqli_query(
    $connection,
    "CREATE TABLE IF NOT EXISTS controller_images (
      image_id int(11) NOT NULL AUTO_INCREMENT,
      controller_id int(11) NOT NULL,
      image_path varchar(255) NOT NULL,
      sort_order int(11) NOT NULL DEFAULT 0,
      uploaded_at timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (image_id),
      KEY controller_id (controller_id),
      CONSTRAINT controller_images_ibfk_1 FOREIGN KEY (controller_id) REFERENCES controllers (controller_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
  );

  $legacy = mysqli_query($connection, "SHOW COLUMNS FROM controllers LIKE 'image_path'");

  if ($legacy && mysqli_num_rows($legacy) > 0) {
    mysqli_query(
      $connection,
      "INSERT INTO controller_images (controller_id, image_path, sort_order)
       SELECT c.controller_id, c.image_path, 1
       FROM controllers c
       WHERE c.image_path IS NOT NULL
         AND c.image_path <> ''
         AND NOT EXISTS (
           SELECT 1 FROM controller_images ci
           WHERE ci.controller_id = c.controller_id
             AND ci.image_path = c.image_path
         )"
    );
  }

  if ($legacy) {
    mysqli_free_result($legacy);
  }
}

ensure_controller_images_table($conn);

function get_db_connection()
{
  global $servername, $username, $password, $dbname;

  $connection = mysqli_connect($servername, $username, $password, $dbname);

  if (!$connection) {
    return null;
  }

  mysqli_set_charset($connection, "utf8mb4");
  ensure_controller_images_table($connection);

  return $connection;
}
