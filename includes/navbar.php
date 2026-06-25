<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('h')) {
    function h($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
}

$navLabel = isset($_SESSION['role']) ? 'Logout' : 'Login';
$navHref = isset($_SESSION['role']) ? '../logout.php' : 'login.php';
$confirmMessage = isset($_SESSION['role']) ? 'Are you sure you want to logout?' : 'Are you sure you want to login?';
?>

<nav class="top-nav" aria-label="Main navigation">
    <a class="brand" href="index.php"><img src="assets/logo.svg" alt="Logo"></a>
    <a href="shop.php" data-page="shop">Shop</a>
    <a href="about.php" data-page="about">About</a>
    
    <a href="#" 
       onclick="confirmNavigation('<?php echo h($navHref); ?>', '<?php echo h($confirmMessage); ?>');" 
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