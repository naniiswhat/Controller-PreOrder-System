<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "includes/db_connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (!isset($_GET['id'])) {
    die("Error: No ID provided.");
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    die("Error: User not found.");
}
?>
<!DOCTYPE html>
<html>
<body>
    <h3>Edit User: <?php echo htmlspecialchars($row['username']); ?></h3>
    <form action="php/update_user.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
        
        <label>Username:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required><br>
        
        <label>Role:</label><br>
        <select name="role">
            <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
            <option value="staff" <?php if($row['role']=='staff') echo 'selected'; ?>>Staff</option>
            <option value="customer" <?php if($row['role']=='customer') echo 'selected'; ?>>Customer</option>
        </select><br><br>
        
        <button type="submit" name="update_user">Update User</button>
    </form>
    <br>
    <a href="home.php?page=users">Back to Manage Users</a>
</body>
</html>