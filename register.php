<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam Controller Pre-Order - Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2> Create an Account </h2>
        
        <?php if (isset($_GET["error"])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET["error"]); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="php/process-register.php"> 
            
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="username" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-6">
                    <button type="submit" class="btn btn-success">Register Account</button>
                    <a href="index.php" class="btn btn-outline-secondary ms-2">Back to Login</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>