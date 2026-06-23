<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="login">
  <main class="auth-page">
    <div class="auth-art">
      <img src="assets/register.jpg" alt="Controller image placeholder">
    </div>
    <form class="auth-form" action="../php/process-register.php" method="post">
      <a class="auth-close" href="index.php" aria-label="Go to homepage">×</a>
      <h1>Register</h1>
      <?php if (isset($_GET["error"])): ?>
        <p class="form-message error"><?php echo htmlspecialchars($_GET["error"], ENT_QUOTES, "UTF-8"); ?></p>
      <?php endif; ?>
      <div class="field">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" placeholder="Username" pattern="[A-Za-z0-9]+" title="Use letters and numbers only." required>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="Email" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Password" minlength="8" pattern="[A-Za-z0-9]+" title="Use at least 8 letters or numbers. Special characters are not allowed." required>
        <span class="field-hint">Minimum 8 characters. Letters and numbers only.</span>
      </div>
      <button class="btn" type="submit">Register</button>
      <a class="small-link" href="login.php">Login</a>
    </form>
  </main>

  <script src="app.js"></script>
</body>
</html>
