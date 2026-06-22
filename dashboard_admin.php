<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: frontend/login.php");
  exit();
}

header("Location: frontend/dashboard_admin.php");
exit();
?>
