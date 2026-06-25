<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('h')) {
    function h($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
}

$isLoggedIn = isset($_SESSION['role']);
$navLabel = $isLoggedIn ? 'Logout' : 'Login';
$navHref = $isLoggedIn ? '../logout.php' : 'login.php';
$confirmMessage = $isLoggedIn ? 'Are you sure you want to logout?' : '';
?>

<nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php"><img src="assets/logo.svg" alt="Logo"></a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    
    <a href="#" 
       onclick="<?php echo !empty($confirmMessage) ? "confirmNavigation('" . h($navHref) . "', '" . h($confirmMessage) . "');" : "window.location.href='" . h($navHref) . "';"; ?>" 
       data-page="login">
       <?php echo h($navLabel); ?>
    </a>
</nav>

<script>
    function confirmNavigation(url, message) {
        if (confirm(message)) {
            window.location.href = url;
        }
    }
</script>