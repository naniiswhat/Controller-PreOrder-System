<?php
require_once __DIR__ . '/../includes/db_connect.php';

$controllerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$controller = null;
$loadError = false;

if ($controllerId) {
  $db = get_db_connection();

  if ($db) {
    $statement = $db->prepare('SELECT controller_id, model_name, description, price, stock_quantity FROM controllers WHERE controller_id = ? LIMIT 1');

    if ($statement) {
      $statement->bind_param('i', $controllerId);
      $statement->execute();
      $result = $statement->get_result();
      $controller = $result->fetch_assoc();
      $statement->close();
    } else {
      $loadError = true;
    }

    $db->close();
  } else {
    $loadError = true;
  }
}

$modelName = $controller ? htmlspecialchars($controller['model_name'], ENT_QUOTES, 'UTF-8') : 'Controller not found';
$description = $controller && $controller['description']
  ? htmlspecialchars($controller['description'], ENT_QUOTES, 'UTF-8')
  : 'This controller is not available right now.';
$price = $controller ? 'RM' . number_format((float) $controller['price'], 2) : '';
$stock = $controller ? (int) $controller['stock_quantity'] : 0;
$isAvailable = $controller && $stock > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $modelName; ?> | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="shop">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo-placeholder.svg" alt="">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    <a href="login.php" data-page="login">Login</a>
  </nav>

  <main class="product-detail-main">
    <?php if ($controller): ?>
      <section class="product-detail-layout" aria-label="<?php echo $modelName; ?> details">
        <div class="product-gallery">
          <div class="product-thumbnails" aria-label="Product views">
            <button class="thumb active" type="button" aria-label="Side view">
              <img src="assets/product-1_0.jpg" alt="">
            </button>
            <button class="thumb thumb-wide" type="button" aria-label="Angled view">
              <img src="assets/product-1_0.jpg" alt="">
            </button>
            <button class="thumb thumb-top" type="button" aria-label="Top view">
              <img src="assets/product-1_0.jpg" alt="">
            </button>
            <button class="thumb thumb-detail" type="button" aria-label="Grip detail">
              <img src="assets/product-1_0.jpg" alt="">
            </button>
          </div>

          <div class="product-stage">
            <img src="assets/product-1_0.jpg" alt="<?php echo $modelName; ?>">
          </div>

          <div class="gallery-controls" aria-label="Gallery controls">
            <button type="button" aria-label="Previous image">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
              </svg>
            </button>
            <button type="button" aria-label="Next image">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="m9 18 6-6-6-6"></path>
              </svg>
            </button>
          </div>
        </div>

        <aside class="product-detail-panel">
          <div class="product-detail-content">
            <header class="product-summary">
              <p class="product-kicker">Controller preorder</p>
              <h1><?php echo $modelName; ?></h1>
              <p class="summary-line">Precision input, tuned grip, and responsive play for the next session.</p>
              <strong class="detail-price"><?php echo $price; ?></strong>
            </header>

            <section class="option-group" aria-labelledby="color-title">
              <div class="option-head">
                <h2 id="color-title">Color</h2>
                <span><?php echo $stock; ?> available</span>
              </div>
              <div class="option-grid compact">
                <button class="option-pill selected" type="button">Carbon</button>
                <button class="option-pill" type="button">Cloud</button>
                <button class="option-pill" type="button">Pulse</button>
              </div>
            </section>

            <a class="btn preorder-btn<?php echo $isAvailable ? '' : ' disabled'; ?>" href="<?php echo $isAvailable ? 'login.php' : 'shop.php'; ?>">
              <?php echo $isAvailable ? 'Pre-order now' : 'Back to shop'; ?>
            </a>

            <div class="product-divider"></div>

            <section class="product-copy">
              <h2>Description</h2>
              <p><?php echo $description; ?></p>
              <p>Built for accurate movement and comfortable long sessions, this controller keeps core inputs close, clean, and quick to reach.</p>
              <p>Reserve yours before the next drop and track the preorder from your account once checkout opens.</p>
            </section>

            <p class="return-note" aria-label="Return note">
              <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="m7.5 4.27 9 5.15"></path>
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
                <path d="m3.3 7 8.7 5 8.7-5"></path>
                <path d="M12 22V12"></path>
                <path d="m16 19 2 2 4-4"></path>
              </svg>
              14-day return and controller exchange
            </p>

            <div class="detail-accordions">
              <details>
                <summary>
                  Controller Details
                  <svg class="icon accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"></path>
                  </svg>
                </summary>
                <p>Responsive buttons, textured grips, precise thumbstick travel, and a balanced shell for daily play.</p>
              </details>
              <details>
                <summary>
                  Pre-order Details
                  <svg class="icon accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"></path>
                  </svg>
                </summary>
                <p>Preorders reserve available stock. Final confirmation happens after account login.</p>
              </details>
              <details>
                <summary>
                  Return Policy
                  <svg class="icon accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"></path>
                  </svg>
                </summary>
                <p>Eligible controllers can be returned or exchanged within 14 days when kept in original condition.</p>
              </details>
            </div>
          </div>
        </aside>
      </section>
    <?php else: ?>
      <section class="product-missing">
        <h1>Controller not found</h1>
        <p>
          <?php echo $loadError ? 'We could not load controller data right now.' : 'That controller does not exist in the current collection.'; ?>
        </p>
        <a class="btn" href="shop.php">Back to shop</a>
      </section>
    <?php endif; ?>
  </main>

  <script src="app.js"></script>
</body>
</html>
