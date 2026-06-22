<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
  header("Location: login.php");
  exit();
}
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
      <img src="assets/logo-placeholder.svg" alt="">
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
          <p class="small-link">Welcome <?php echo htmlspecialchars($_SESSION['name'] ?? 'staff', ENT_QUOTES, 'UTF-8'); ?>. Review submitted pre-orders, update order progress, and adjust stock levels.</p>
        </div>
        <a class="btn secondary" href="shop.php">View shop</a>
      </header>

      <div class="stat-row">
        <div class="stat"><strong>5</strong><span>Submitted orders</span></div>
        <div class="stat"><strong id="processingCount">2</strong><span>Processing</span></div>
        <div class="stat"><strong id="stockTotal">155</strong><span>Total stock</span></div>
        <div class="stat"><strong id="lowStockCount">1</strong><span>Low stock</span></div>
      </div>

      <div class="staff-grid">
        <section class="admin-panel">
          <div class="panel-head">
            <div>
              <h2>Submitted pre-orders</h2>
              <p class="small-link">Staff can view orders and update status only.</p>
            </div>
            <span class="status-pill status-processing">Live draft</span>
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
              <tr>
                <td>#PO-1042</td>
                <td>Aiman</td>
                <td>Steam Controller Pro</td>
                <td>1</td>
                <td>
                  <div class="status-control">
                    <span class="status-pill status-pending">Pending</span>
                    <select class="order-status" aria-label="Update status for order PO-1042">
                      <option selected>Pending</option>
                      <option>Processing</option>
                      <option>Shipped</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#PO-1043</td>
                <td>Sara</td>
                <td>Controller Lite</td>
                <td>2</td>
                <td>
                  <div class="status-control">
                    <span class="status-pill status-processing">Processing</span>
                    <select class="order-status" aria-label="Update status for order PO-1043">
                      <option>Pending</option>
                      <option selected>Processing</option>
                      <option>Shipped</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#PO-1044</td>
                <td>Jason</td>
                <td>Steam Controller V2 Base</td>
                <td>1</td>
                <td>
                  <div class="status-control">
                    <span class="status-pill status-shipped">Shipped</span>
                    <select class="order-status" aria-label="Update status for order PO-1044">
                      <option>Pending</option>
                      <option>Processing</option>
                      <option selected>Shipped</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#PO-1045</td>
                <td>Mei Ling</td>
                <td>Steam Controller Pro</td>
                <td>1</td>
                <td>
                  <div class="status-control">
                    <span class="status-pill status-processing">Processing</span>
                    <select class="order-status" aria-label="Update status for order PO-1045">
                      <option>Pending</option>
                      <option selected>Processing</option>
                      <option>Shipped</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td>#PO-1046</td>
                <td>Daniel</td>
                <td>Controller Elite Dock</td>
                <td>1</td>
                <td>
                  <div class="status-control">
                    <span class="status-pill status-pending">Pending</span>
                    <select class="order-status" aria-label="Update status for order PO-1046">
                      <option selected>Pending</option>
                      <option>Processing</option>
                      <option>Shipped</option>
                    </select>
                  </div>
                </td>
              </tr>
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
            <label class="stock-item">
              <span>
                <strong>Steam Controller V2 Base</strong>
                <small>Standard model</small>
              </span>
              <input class="stock-input" type="number" min="0" value="100" aria-label="Stock for Steam Controller V2 Base">
            </label>

            <label class="stock-item">
              <span>
                <strong>Steam Controller Pro</strong>
                <small>Advanced model</small>
              </span>
              <input class="stock-input" type="number" min="0" value="50" aria-label="Stock for Steam Controller Pro">
            </label>

            <label class="stock-item is-low">
              <span>
                <strong>Controller Elite Dock</strong>
                <small>Accessory</small>
              </span>
              <input class="stock-input" type="number" min="0" value="5" aria-label="Stock for Controller Elite Dock">
            </label>
          </div>

          <button class="btn staff-save" type="button">Save staff updates</button>
          <p class="staff-note" role="status" aria-live="polite">Changes are static on this page only.</p>
        </section>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
