<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

function h($value)
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$controllerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$controller = null;
$images = [];
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

      if ($controller) {
        $imageStatement = $db->prepare('SELECT image_path FROM controller_images WHERE controller_id = ? ORDER BY sort_order, image_id');

        if ($imageStatement) {
          $imageStatement->bind_param('i', $controllerId);
          $imageStatement->execute();
          $imageResult = $imageStatement->get_result();

          while ($imageRow = $imageResult->fetch_assoc()) {
            $images[] = $imageRow['image_path'];
          }

          $imageStatement->close();
        }
      }
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
$images = $images ?: ['assets/product-1_0.jpg'];
$imagePath = $images[0];
$isAvailable = $controller && $stock > 0;
$isCustomer = isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
$navLabel = isset($_SESSION['role']) ? 'Logout' : 'Login';
$navHref = isset($_SESSION['role']) ? '../logout.php' : 'login.php';
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
      <img src="assets/logo.svg" alt="">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
        <a href="dashboard_customer.php">My Orders</a>
    <?php endif; ?>
    
    <a href="about.php" data-page="about">About</a>
    <a href="<?php echo h($navHref); ?>" data-page="login"><?php echo h($navLabel); ?></a>
  </nav>

  <main class="product-detail-main">
    <?php if ($controller): ?>
      <section class="product-detail-layout" aria-label="<?php echo $modelName; ?> details">
        <div class="product-gallery">
          <div class="product-thumbnails" aria-label="Product views">
            <?php foreach ($images as $index => $galleryImage): ?>
              <button class="thumb <?php echo $index === 0 ? 'active' : ''; ?>" type="button" aria-label="Product image <?php echo h($index + 1); ?>">
                <img src="<?php echo h($galleryImage); ?>" alt="">
              </button>
            <?php endforeach; ?>
          </div>

          <div class="product-stage">
            <img src="<?php echo h($imagePath); ?>" alt="<?php echo $modelName; ?>">
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

            <?php if (isset($_GET["error"])): ?>
              <p class="form-message error"><?php echo h($_GET["error"]); ?></p>
            <?php endif; ?>
            <?php if (isset($_GET["success"])): ?>
              <p class="form-message success"><?php echo h($_GET["success"]); ?></p>
            <?php endif; ?>

            <?php if ($isAvailable && $isCustomer): ?>
              <form class="preorder-form" action="../php/create-preorder.php" method="post">
                <input type="hidden" name="controller_id" value="<?php echo h($controller['controller_id']); ?>">
                <label for="quantity">Quantity</label>
                <input id="quantity" name="quantity" type="number" min="1" max="<?php echo h($stock); ?>" value="1" required>
                <button class="btn preorder-btn" type="submit">Pre-order now</button>
              </form>
            <?php elseif ($isAvailable): ?>
              <a class="btn preorder-btn" href="login.php">Login to pre-order</a>
            <?php else: ?>
              <a class="btn preorder-btn disabled" href="shop.php">Back to shop</a>
            <?php endif; ?>

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
