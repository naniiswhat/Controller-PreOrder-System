<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="shop">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo.svg" alt="">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    <a href="login.php" data-page="login">Login</a>
  </nav>

  <main>
    <section class="page">
      <header class="page-title">
        <h1>Full collection</h1>
        <p>6 results</p>
      </header>

      <div class="shop-grid">
        <a class="product" href="product.php?id=1">
          <div class="product-image"><img src="assets/product-1_0.jpg" alt="Steam Controller V2 Base"></div>
          <h2>Steam Controller V2</h2>
          <p>Dual trackpads and HD haptics.</p>
          <div class="price">RM99.99</div>
        </a>
        <a class="product" href="product.php?id=2">
          <div class="product-image"><img src="assets/product-placeholder.svg" alt="Steam Controller Pro"></div>
          <h2>Steam Controller Pro</h2>
          <p>Swappable thumbsticks and premium grips.</p>
          <div class="price">RM89.99</div>
        </a>
        <article class="product">
          <div class="product-image"><img src="assets/product-placeholder.svg" alt="Controller Lite"></div>
          <h2>PS5 DualSense</h2>
          <p>DualSense® Wireless Controller</p>
          <div class="price">RM109.00</div>
        </article>
        <article class="product">
          <div class="product-image"><img src="assets/product-placeholder.svg" alt="Arcade Controller"></div>
          <h2>Arcade Controller</h2>
          <p>Large buttons for fighting games.</p>
          <div class="price">RM74.99</div>
        </article>
        <article class="product">
          <div class="product-image"><img src="assets/product-placeholder.svg" alt="Controller Dock"></div>
          <h2>Controller Dock</h2>
          <p>Charging dock with display stand.</p>
          <div class="price">RM29.99</div>
        </article>
        <article class="product">
          <div class="product-image"><img src="assets/product-placeholder.svg" alt="Pro Grip Kit"></div>
          <h2>Pro Grip Kit</h2>
          <p>Replacement grips and sticks.</p>
          <div class="price">RM19.99</div>
        </article>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
