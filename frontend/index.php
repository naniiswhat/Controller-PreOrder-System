<?php
session_start();

$navLabel = isset($_SESSION['role']) ? 'Logout' : 'Login';
$navHref = isset($_SESSION['role']) ? '../logout.php' : 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="home">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>

  <main class="home-main">
    <section class="launch-hero" aria-label="Steam Controller preorder">
      <div class="hero-bg" aria-hidden="true"></div>
      <div class="controller-stage" aria-hidden="true">
          <p class="launch-kicker">New Controller Drop!</p>
          <img class="floating-controller" src="assets/front.png" alt="">
      </div>
      <div class="launch-info">
        <h1>Steam Controller</h1>
        <p>Reserve the next controller drop before stock goes live.</p>
        <a class="btn preorder-launch-btn" href="shop.php">Pre-order now</a>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
