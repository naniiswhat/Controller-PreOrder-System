<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

// Fungsi sanitasi output
function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Ambil data dari database
$controllers = [];
$query = "SELECT c.controller_id, c.model_name, c.description, c.price, c.stock_quantity,
                 (SELECT ci.image_path FROM controller_images ci WHERE ci.controller_id = c.controller_id ORDER BY ci.sort_order, ci.image_id LIMIT 1) AS primary_image
          FROM controllers c
          ORDER BY c.controller_id";

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $controllers[] = $row;
    }
}

// Logik Navigasi
$isLoggedIn = isset($_SESSION['role']);
$navLabel = $isLoggedIn ? 'Logout' : 'Login';
$navHref = $isLoggedIn ? '../logout.php' : 'login.php';
$confirmMessage = $isLoggedIn ? 'Are you sure you want to logout?' : 'Are you sure you want to login?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Controller Pre Order System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body data-page="shop">

<?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main>
        <section class="page">
            <header class="page-title">
                <h1>Full collection</h1>
                <p><?php echo count($controllers); ?> results</p>
            </header>

            <div class="shop-grid">
                <?php if (!empty($controllers)): ?>
                    <?php foreach ($controllers as $controller): ?>
                        <?php $imagePath = $controller['primary_image'] ?: 'assets/product-1_0.jpg'; ?>
                        <a class="product" href="product.php?id=<?php echo h($controller['controller_id']); ?>">
                            <div class="product-image">
                                <img src="<?php echo h($imagePath); ?>" alt="<?php echo h($controller['model_name']); ?>">
                            </div>
                            <h2><?php echo h($controller['model_name']); ?></h2>
                            <p><?php echo h($controller['description'] ?: 'Controller preorder item.'); ?></p>
                            <div class="price">RM<?php echo number_format((float) $controller['price'], 2); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No controllers are available right now.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        /**
         * Fungsi pengesahan untuk navigasi
         * Diletakkan di sini untuk mengelakkan konflik event
         */
        function confirmNavigation(url, message) {
            if (confirm(message)) {
                window.location.href = url;
            }
        }
    </script>
</body>
</html>