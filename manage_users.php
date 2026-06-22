<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}
?>

<h3>Manage Users</h3>

<div style="background:#f4f4f4; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #ddd;">
    <h4>Register New User</h4>
    <form action="php/add_user.php" method="POST">
        <input type="text" name="username" placeholder="Username" required style="padding:5px;">
        <input type="email" name="email" placeholder="Email (Optional)" style="padding:5px;">
        <input type="password" name="password" placeholder="Password" required style="padding:5px;">
        <select name="role" style="padding:5px;">
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>
        <button type="submit" name="add_user" style="padding:5px 10px;">Register</button>
    </form>
</div>

<table style="width:100%; border-collapse:collapse; background:#fff;">
    <tr style="background:#333; color:white;">
        <th style="padding:10px; text-align:left;">ID</th>
        <th style="padding:10px; text-align:left;">Username</th>
        <th style="padding:10px; text-align:left;">Email</th>
        <th style="padding:10px; text-align:left;">Role</th>
        <th style="padding:10px; text-align:left;">Actions</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM users");
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['user_id']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['username']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['email']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['role']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee;'>
                    <a href='edit_user.php?id={$row['user_id']}'>Edit</a> | 
                    <a href='php/delete_user.php?id={$row['user_id']}' style='color:red;' onclick='return confirm(\"Confirm delete?\")'>Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>