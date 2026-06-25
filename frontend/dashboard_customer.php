<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

// Security: Kick out anyone who isn't a logged-in customer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['id'];
$orders = [];

// Fetch ONLY this specific customer's orders, joining with the controllers table to get the name
$stmt = mysqli_stmt_init($conn);
$sql = "SELECT p.order_id, p.quantity, p.order_date, p.status, c.model_name, c.price 
        FROM preorders p 
        JOIN controllers c ON p.controller_id = c.controller_id 
        WHERE p.user_id = ? 
        ORDER BY p.order_date DESC";

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}

$navLabel = 'Logout';
$navHref = '../logout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="shop">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo.svg" alt="Logo">
    </a>
    <a href="shop.php">Shop</a>
    <a href="dashboard_customer.php" style="font-weight: bold; color: #007bff;">My Orders</a>
    <a href="about.php">About</a>
    <a href="<?php echo $navHref; ?>"><?php echo $navLabel; ?></a>
  </nav>

  <main>
    <section class="page">
      <header class="page-title">
        <h1>My Pre-Orders</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>. Here is your order history.</p>
      </header>

      <div style="max-width: 900px; margin: 0 auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <?php if (count($orders) > 0): ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="padding: 12px;">Order ID</th>
                        <th style="padding: 12px;">Item</th>
                        <th style="padding: 12px;">Quantity</th>
                        <th style="padding: 12px;">Total Price</th>
                        <th style="padding: 12px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">#PO-<?php echo $order['order_id']; ?></td>
                        <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($order['model_name']); ?></td>
                        <td style="padding: 12px;"><?php echo $order['quantity']; ?></td>
                        <td style="padding: 12px;">RM<?php echo number_format($order['price'] * $order['quantity'], 2); ?></td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85em; background: <?php echo ($order['status'] === 'shipped') ? '#d4edda' : (($order['status'] === 'processing') ? '#cce5ff' : '#fff3cd'); ?>; color: #333;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #666; padding: 40px 0;">You haven't placed any pre-orders yet. <a href="shop.php" style="color: #007bff; text-decoration: none;">Browse the shop.</a></p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>