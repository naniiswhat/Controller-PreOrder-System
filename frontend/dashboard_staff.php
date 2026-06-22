<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
  header("Location: login.php");
  exit();
}

function h($value)
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function fetch_count($conn, $sql)
{
  $result = mysqli_query($conn, $sql);
  $row = $result ? mysqli_fetch_assoc($result) : null;
  return $row ? (int) $row['total'] : 0;
}

function fetch_rows($conn, $sql)
{
  $rows = [];
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
    }
  }

  return $rows;
}

function status_class($status)
{
  if ($status === 'processing') {
    return 'status-processing';
  }

  if ($status === 'shipped') {
    return 'status-shipped';
  }

  return 'status-pending';
}

$orders = fetch_rows(
  $conn,
  "SELECT p.order_id, u.username, c.model_name, p.quantity, p.status
   FROM preorders p
   LEFT JOIN users u ON u.user_id = p.user_id
   LEFT JOIN controllers c ON c.controller_id = p.controller_id
   ORDER BY p.order_id DESC"
);
$controllers = fetch_rows($conn, "SELECT controller_id, model_name, description, price, stock_quantity FROM controllers ORDER BY controller_id");
$orderCount = count($orders);
$processingCount = fetch_count($conn, "SELECT COUNT(*) AS total FROM preorders WHERE status = 'processing'");
$stockTotal = fetch_count($conn, "SELECT COALESCE(SUM(stock_quantity), 0) AS total FROM controllers");
$lowStockCount = fetch_count($conn, "SELECT COUNT(*) AS total FROM controllers WHERE stock_quantity <= 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="staff">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/logo.svg" alt="">
    </a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    <a href="../logout.php" data-page="login">Logout</a>
  </nav>

  <main>
    <section class="page staff-page">
      <header class="admin-header">
        <div>
          <h1>Staff page</h1>
          <p class="small-link">Welcome <?php echo h($_SESSION['name'] ?? 'staff'); ?>. Review submitted pre-orders, update order progress, and adjust stock levels.</p>
        </div>
        <a class="btn secondary" href="shop.php">View shop</a>
      </header>

      <?php if (isset($_GET["status"])): ?>
        <p class="form-message success">Update saved.</p>
      <?php endif; ?>

      <div class="stat-row">
        <div class="stat"><strong><?php echo $orderCount; ?></strong><span>Submitted orders</span></div>
        <div class="stat"><strong><?php echo $processingCount; ?></strong><span>Processing</span></div>
        <div class="stat"><strong><?php echo $stockTotal; ?></strong><span>Total stock</span></div>
        <div class="stat"><strong><?php echo $lowStockCount; ?></strong><span>Low stock</span></div>
      </div>

      <div class="staff-grid">
        <section class="admin-panel">
          <div class="panel-head">
            <div>
              <h2>Submitted pre-orders</h2>
              <p class="small-link">Staff can view orders and update status only.</p>
            </div>
            <span class="status-pill status-processing">Live data</span>
          </div>

          <table class="table staff-table">
            <thead>
              <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td>#PO-<?php echo h($order['order_id']); ?></td>
                    <td><?php echo h($order['username'] ?? 'Unknown'); ?></td>
                    <td><?php echo h($order['model_name'] ?? 'Unknown'); ?></td>
                    <td><?php echo h($order['quantity']); ?></td>
                    <td>
                      <form class="status-control" action="../staff/update_order.php" method="post">
                        <input type="hidden" name="order_id" value="<?php echo h($order['order_id']); ?>">
                        <span class="status-pill <?php echo h(status_class($order['status'])); ?>"><?php echo h(ucfirst($order['status'])); ?></span>
                        <select class="order-status" name="status" aria-label="Update status for order <?php echo h($order['order_id']); ?>" onchange="this.form.submit()">
                          <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                          <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                          <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                          <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5">No submitted pre-orders yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </section>

        <section class="admin-panel">
          <div class="panel-head">
            <div>
              <h2>Stock levels</h2>
              <p class="small-link">Adjust available stock for each controller.</p>
            </div>
          </div>

          <div class="stock-list">
            <?php if ($controllers): ?>
              <?php foreach ($controllers as $controller): ?>
                <form class="stock-item <?php echo (int) $controller['stock_quantity'] <= 5 ? 'is-low' : ''; ?>" action="../php/update_stock.php" method="post">
                  <span>
                    <strong><?php echo h($controller['model_name']); ?></strong>
                    <small>RM<?php echo number_format((float) $controller['price'], 2); ?></small>
                  </span>
                  <input type="hidden" name="id" value="<?php echo h($controller['controller_id']); ?>">
                  <input type="hidden" name="model_name" value="<?php echo h($controller['model_name']); ?>">
                  <input type="hidden" name="price" value="<?php echo h($controller['price']); ?>">
                  <input class="stock-input" name="stock" type="number" min="0" value="<?php echo h($controller['stock_quantity']); ?>" aria-label="Stock for <?php echo h($controller['model_name']); ?>">
                  <button class="btn staff-save" type="submit" name="update_product">Save</button>
                </form>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="small-link">No controllers found.</p>
            <?php endif; ?>
          </div>
        </section>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
