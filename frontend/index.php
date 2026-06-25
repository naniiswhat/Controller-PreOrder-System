<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
        exit();
    } else if ($_SESSION['role'] === 'staff') {
        header("Location: dashboard_staff.php");
        exit();
    }
}

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
  
  <style>
    @media (max-width: 800px) {
      .launch-info {
        text-align: center !important;
        padding: 0 20px 30px 20px !important;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
      }
      .launch-info h1, .launch-info p {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
      }
      .launch-info h1 {
        font-size: 2.2rem !important;
        margin-bottom: 10px !important;
        color: #111 !important;
      }
      .launch-info p {
        font-size: 1rem !important;
        margin-bottom: 25px !important;
        color: #555 !important;
        max-width: 320px;
      }
    }
  </style>
</head>
<body data-page="home">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo.svg" alt="Logo">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
        <a href="dashboard_customer.php" style="font-weight: bold; color: #007bff;">My Orders</a>
    <?php endif; ?>

    <a href="about.php" data-page="about">About</a>
    <a href="<?php echo $navHref; ?>" data-page="login"><?php echo $navLabel; ?></a>
  </nav>

  <main class="home-main">
    <section class="launch-hero" aria-label="Steam Controller preorder">
      <div class="hero-bg" aria-hidden="true"></div>
      <div class="controller-stage" aria-hidden="true">
          <p class="launch-kicker">New stock dropping soon!</p>
          <img class="floating-controller" src="assets/front.png" alt="Steam Controller">
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