<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="home">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo.svg" alt="">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    <a href="login.php" data-page="login">Login</a>
  </nav>

  <main class="home-main">
    <section class="launch-hero" aria-labelledby="launch-title">
      <div class="hero-bg" aria-hidden="true"></div>
      <div class="controller-stage" aria-hidden="true">
          <p class="launch-kicker">New drop open now</p>
          <span class="controller-shadow-text">Steam Controller</span>
          <img class="floating-controller" src="assets/front.png" alt="">
      </div>
      <a class="btn preorder-launch-btn" href="shop.php">Pre-order now</a>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
