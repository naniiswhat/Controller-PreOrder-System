<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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

$userCount = fetch_count($conn, "SELECT COUNT(*) AS total FROM users");
$orderCount = fetch_count($conn, "SELECT COUNT(*) AS total FROM preorders");
$controllerCount = fetch_count($conn, "SELECT COUNT(*) AS total FROM controllers");
$stockTotal = fetch_count($conn, "SELECT COALESCE(SUM(stock_quantity), 0) AS total FROM controllers");
$controllers = fetch_rows(
  $conn,
  "SELECT c.controller_id, c.model_name, c.description, c.stock_quantity, c.price,
          (SELECT ci.image_path FROM controller_images ci WHERE ci.controller_id = c.controller_id ORDER BY ci.sort_order, ci.image_id LIMIT 1) AS primary_image,
          (SELECT COUNT(*) FROM controller_images ci WHERE ci.controller_id = c.controller_id) AS image_count
   FROM controllers c
   ORDER BY c.controller_id"
);
$orders = fetch_rows(
  $conn,
  "SELECT p.order_id, u.username, c.model_name, p.quantity, p.status
   FROM preorders p
   LEFT JOIN users u ON u.user_id = p.user_id
   LEFT JOIN controllers c ON c.controller_id = p.controller_id
   ORDER BY p.order_id DESC"
);
$users = fetch_rows($conn, "SELECT user_id, username, email, role FROM users ORDER BY user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="admin">
  <nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="dashboard_admin.php" aria-label="Admin Home">
      <img src="assets/logo.svg" alt="Logo">
    </a>
    <a href="../logout.php" data-page="login">Logout</a>
  </nav>

  <main>
    <section class="page">
      <header class="admin-header">
        <div>
          <h1>Admin page</h1>
          <p class="small-link">Welcome <?php echo h($_SESSION['name'] ?? 'admin'); ?>. Products, pre orders, and users in one place.</p>
        </div>
      </header>

      <?php if (isset($_GET["status"])): ?>
        <p class="form-message success">Update saved.</p>
      <?php endif; ?>
      <?php if (isset($_GET["error"])): ?>
        <p class="form-message error"><?php echo h($_GET["error"]); ?></p>
      <?php endif; ?>

      <div class="stat-row">
        <div class="stat"><strong><?php echo $controllerCount; ?></strong><span>Controllers</span></div>
        <div class="stat"><strong><?php echo $orderCount; ?></strong><span>Pre orders</span></div>
        <div class="stat"><strong><?php echo $userCount; ?></strong><span>Users</span></div>
        <div class="stat"><strong><?php echo $stockTotal; ?></strong><span>Stock</span></div>
      </div>

      <div class="admin-grid">
        <section class="admin-panel">
          <div class="panel-head">
            <div>
              <h2>Products</h2>
              <p class="small-link">Manage controller listings, stock, pricing, and product images.</p>
            </div>
            <a class="btn with-icon" href="../add_controller.php">
              <img src="assets/icons/plus.svg" alt="">
              Add
            </a>
          </div>
          <table class="table">
            <thead>
              <tr><th>Image</th><th>Model</th><th>Stock</th><th>Price</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php if ($controllers): ?>
                <?php foreach ($controllers as $controller): ?>
                  <?php $imagePath = $controller['primary_image'] ?: 'assets/product-1_0.jpg'; ?>
                  <tr>
                    <td><img class="product-thumb" src="<?php echo h($imagePath); ?>" alt=""></td>
                    <td><?php echo h($controller['model_name']); ?></td>
                    <td><?php echo h($controller['stock_quantity']); ?></td>
                    <td>RM<?php echo number_format((float) $controller['price'], 2); ?></td>
                    <td>
                      <span class="small-link"><?php echo h($controller['image_count']); ?> image(s)</span>
                      <span class="table-actions">
                        <a class="icon-action" href="../edit_product.php?id=<?php echo h($controller['controller_id']); ?>" aria-label="Edit <?php echo h($controller['model_name']); ?>" title="Edit">
                          <img src="assets/icons/square-pen.svg" alt="">
                        </a>
                        <a class="icon-action danger" href="../php/delete_variant.php?id=<?php echo h($controller['controller_id']); ?>" aria-label="Delete <?php echo h($controller['model_name']); ?>" title="Delete" onclick="return confirm('Confirm delete?')">
                          <img src="assets/icons/trash-2.svg" alt="">
                        </a>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5">No controllers found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </section>

        <section class="admin-panel">
          <h2>Orders</h2>
          <table class="table">
            <thead>
              <tr><th>Customer</th><th>Item</th><th>Qty</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td><?php echo h($order['username'] ?? 'Unknown'); ?></td>
                    <td><?php echo h($order['model_name'] ?? 'Unknown'); ?></td>
                    <td><?php echo h($order['quantity']); ?></td>
                    <td><?php echo h($order['status']); ?></td>
                    <td>
                      <span class="table-actions">
                        <a class="icon-action" href="../edit_order.php?id=<?php echo h($order['order_id']); ?>" aria-label="Edit order <?php echo h($order['order_id']); ?>" title="Edit">
                          <img src="assets/icons/square-pen.svg" alt="">
                        </a>
                        <a class="icon-action danger" href="../php/manage-orders.php?delete=<?php echo h($order['order_id']); ?>" aria-label="Delete order <?php echo h($order['order_id']); ?>" title="Delete" onclick="return confirm('Confirm delete?')">
                          <img src="assets/icons/trash-2.svg" alt="">
                        </a>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5">No pre orders found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </section>

        <section class="admin-panel">
          <h2>Users</h2>
          <form class="compact-form" action="../php/add_user.php" method="post">
            <div class="field">
              <label for="username">Username</label>
              <input id="username" name="username" type="text" required>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <input id="email" name="email" type="email">
            </div>
            <div class="field">
              <label for="password">Password</label>
              <input id="password" name="password" type="password" required>
            </div>
            <div class="field">
              <label for="role">Role</label>
              <select id="role" name="role" required>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
              </select>
            </div>
            <button class="btn with-icon" type="submit" name="add_user">
              <img src="assets/icons/plus.svg" alt="">
              Add user
            </button>
          </form>
          <table class="table">
            <thead>
              <tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php if ($users): ?>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?php echo h($user['username']); ?></td>
                    <td><?php echo h($user['email']); ?></td>
                    <td><?php echo h(ucfirst($user['role'])); ?></td>
                    <td>
                      <span class="table-actions">
                        <a class="icon-action" href="../edit_user.php?id=<?php echo h($user['user_id']); ?>" aria-label="Edit <?php echo h($user['username']); ?>" title="Edit">
                          <img src="assets/icons/square-pen.svg" alt="">
                        </a>
                        <a class="icon-action danger" href="../php/delete_user.php?id=<?php echo h($user['user_id']); ?>" aria-label="Delete <?php echo h($user['username']); ?>" title="Delete" onclick="return confirm('Confirm delete?')">
                          <img src="assets/icons/trash-2.svg" alt="">
                        </a>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4">No users found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </section>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
