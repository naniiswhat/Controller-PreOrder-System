<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();
include "includes/db_connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { die("Access Denied"); }
if (!isset($_GET['id'])) { die("Error: No ID provided."); }

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) { die("Error: User not found."); }
function h($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User | Controller Pre Order System</title>
    <link rel="stylesheet" href="frontend/styles.css">
</head>
<body data-page="admin-edit">
    <nav class="top-nav" aria-label="Backend navigation">
        <a class="brand" href="frontend/dashboard_admin.php">
            <img src="frontend/assets/logo.svg" alt="Logo">
        </a>
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <section class="page backend-edit-page">
            <header class="admin-header">
                <div>
                    <h1>Edit user</h1>
                    <p class="small-link">Account override for: <?php echo h($row['username']); ?></p>
                </div>
                <a class="btn secondary" href="frontend/dashboard_admin.php">Back to dashboard</a>
            </header>
            <form class="backend-edit-grid" action="php/update_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo h($row['user_id']); ?>">
                <section class="admin-panel backend-edit-main" style="grid-column: 1 / -1;">
                    <div class="field">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo h($row['username']); ?>" required>
                    </div>
                    <div class="field" style="margin-top: 14px;">
                        <label>Role</label>
                        <select name="role">
                            <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                            <option value="staff" <?php if($row['role']=='staff') echo 'selected'; ?>>Staff</option>
                            <option value="customer" <?php if($row['role']=='customer') echo 'selected'; ?>>Customer</option>
                        </select>
                    </div>
                </section>
                <div class="backend-actions" style="grid-column: 1 / -1;">
                    <button type="submit" name="update_user" class="btn">Update User</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>