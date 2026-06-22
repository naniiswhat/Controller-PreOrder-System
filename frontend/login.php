<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="login">
  <main class="auth-page">
    <div class="auth-art">
      <img src="assets/login.jpg" alt="Controller image placeholder">
    </div>
    <form class="auth-form" action="../php/check-login.php" method="post">
      <a class="auth-close" href="index.php" aria-label="Go to homepage">×</a>
      <h1>Welcome Back</h1>
      <p class="small-link">Login to manage or place pre orders.</p>
      <?php if (isset($_GET["error"])): ?>
        <p class="form-message error"><?php echo htmlspecialchars($_GET["error"], ENT_QUOTES, "UTF-8"); ?></p>
      <?php endif; ?>
      <?php if (isset($_GET["success"])): ?>
        <p class="form-message success"><?php echo htmlspecialchars($_GET["success"], ENT_QUOTES, "UTF-8"); ?></p>
      <?php endif; ?>
      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="Email" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Password" required>
      </div>
      <div class="field">
        <label for="role">Role</label>
        <select id="role" name="role" required>
          <option value="customer">Customer Portal</option>
          <option value="staff">Staff / Inventory</option>
          <option value="admin">Admin Override</option>
        </select>
      </div>
      <button class="btn" type="submit">Login</button>
      <a class="small-link" href="register.php">Register</a>
    </form>
  </main>

  <script src="app.js"></script>
</body>
</html>
