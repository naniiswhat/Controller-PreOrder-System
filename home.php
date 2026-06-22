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
                      [Insert Core CRUD Manager Views Here]
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