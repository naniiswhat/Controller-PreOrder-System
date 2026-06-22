<?php 
session_start();

// check session tracking variables - redirect to home.php if true
if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam Controller Pre-Order - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2> Account Login </h2>
        
        <?php if (isset($_GET["error"])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET["error"]); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["success"])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET["success"]); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="php/check-login.php"> 
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Workspace Role</label>
                <div class="col-sm-6">
                    <select class="form-select" name="role" required>
                        <option value="customer">Customer Portal</option>
                        <option value="staff">Staff / Inventory</option>
                        <option value="admin">Admin Override</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                    <div class="mt-3">
                        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>