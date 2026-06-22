<?php
session_start();

if (!isset($_SESSION['username'], $_SESSION['id'], $_SESSION['role'])) {
  header("Location: frontend/login.php");
  exit();
}

if ($_SESSION['role'] === 'admin') {
  header("Location: frontend/dashboard_admin.php");
  exit();
}

if ($_SESSION['role'] === 'staff') {
  header("Location: frontend/dashboard_staff.php");
  exit();
}

header("Location: frontend/shop.php");
exit();
?>
