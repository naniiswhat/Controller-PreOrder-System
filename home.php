<?php
session_start();
include "includes/db_connect.php";

// validate account access keys before compiling HTML data structures
if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Dashboard Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
      <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh">
          
          <?php if ($_SESSION['role'] == 'admin') { ?>
              <div class="card text-center p-4 shadow" style="width: 24rem;">
                <img src="img/admin-default.png" class="card-img-top mx-auto d-block" alt="admin logo" style="width: 100px;">
                <div class="card-body">
                  <h5 class="card-title">Welcome Admin: <?=$_SESSION['name']?></h5>
                  <p class="card-text text-danger font-monospace">System Clearance: Full Override Privilege</p>
                  
                  <div class="alert alert-info mt-3">
                      <strong>Admin Task Room:</strong><br>
                    <?php 
                        // fetch dashboard statistics
                        $user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
                        $order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM preorders"))['total'];
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="alert alert-primary">Total Users: <b><?=$user_count?></b></div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success">Total Orders: <b><?=$order_count?></b></div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bg-white text-start shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order ID</th><th>User ID</th><th>Controller ID</th><th>Qty</th><th>Status</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM preorders");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                            <td>{$row['order_id']}</td>
                                            <td>{$row['user_id']}</td>
                                            <td>{$row['controller_id']}</td>
                                            <td>{$row['quantity']}</td>
                                            <td><strong>{$row['status']}</strong></td>
                                            <td>
                                                <a href='edit_order.php?id={$row['order_id']}' class='btn btn-sm btn-primary'>Edit</a>
                                                <a href='php/manage-orders.php?delete={$row['order_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Confirm delete?\")'>Delete</a>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                  </div>
                  
                  <a href="logout.php" class="btn btn-dark w-100">Sign Out</a>
                </div>
              </div>

          <?php } else if ($_SESSION['role'] == 'staff') { ?>
              <div class="card text-center p-4 shadow" style="width: 24rem;">
                <img src="img/user-default.jpg" class="card-img-top mx-auto d-block" alt="staff logo" style="width: 100px;">
                <div class="card-body">
                  <h5 class="card-title">Welcome Staff: <?=$_SESSION['name']?></h5>
                  <p class="card-text text-warning font-monospace">System Clearance: Inventory Supervisor</p>
                  
                  <div class="alert alert-info mt-3">
                      <strong>Staff Queue Room:</strong><br>
                      [Insert Pre-Order Status Processing Modals Here]
                  </div>
                  
                  <a href="logout.php" class="btn btn-dark w-100">Sign Out</a>
                </div>
              </div>

          <?php } else { ?>
              <div class="card text-center p-4 shadow" style="width: 24rem;">
                <img src="img/user-default.jpg" class="card-img-top mx-auto d-block" alt="client logo" style="width: 100px;">
                <div class="card-body">
                  <h5 class="card-title">Steam Controller V2 Pre-Order</h5>
                  <p class="text-muted">Authenticated Profile: <?=$_SESSION['name']?></p>
                  
                  <div class="alert alert-success mt-3">
                      <strong>Your Catalog Dashboard:</strong><br>
                      [Insert Interactive Pre-Order Request Forms Here]
                  </div>
                  
                  <a href="logout.php" class="btn btn-dark w-100">Sign Out</a>
                </div>
              </div>
          <?php } ?>
          
      </div>
</body>
</html>

<?php 
// fallback protection
} else {
    header("Location: index.php");
} 
?>