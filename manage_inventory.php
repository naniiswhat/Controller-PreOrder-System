<?php
// Pastikan hanya admin/staff yang boleh akses
if (!isset($_SESSION['role'])) {
    die("Access Denied");
}
?>

<h3>Inventory & Controller Variants</h3>

<div style="background:#f4f4f4; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #ddd;">
    <h4>Add New Controller Variant</h4>
    <form action="php/add_variant.php" method="POST">
        <input type="text" name="model_name" placeholder="Model Name (e.g. Pro-X)" required style="padding:8px; width:200px;">
        <input type="number" step="0.01" name="price" placeholder="Price (RM)" required style="padding:8px; width:100px;">
        <input type="number" name="stock" placeholder="Stock" required style="padding:8px; width:80px;">
        <button type="submit" name="add_variant" style="padding:8px 15px; cursor:pointer;">Add Variant</button>
    </form>
</div>

<table style="width:100%; border-collapse:collapse; background:#fff;">
    <tr style="background:#333; color:white;">
        <th style="padding:10px; text-align:left;">ID</th>
        <th style="padding:10px; text-align:left;">Model Name</th>
        <th style="padding:10px; text-align:left;">Price</th>
        <th style="padding:10px; text-align:left;">Stock</th>
        <th style="padding:10px; text-align:left;">Actions</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM controllers");
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                    <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['controller_id']}</td>
                    <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['model_name']}</td>
                    <td style='padding:10px; border-bottom:1px solid #eee;'>RM {$row['price']}</td>
                    <td style='padding:10px; border-bottom:1px solid #eee;'>{$row['stock_quantity']}</td>
                    <td style='padding:10px; border-bottom:1px solid #eee;'>
                        <a href='edit_stock.php?id={$row['controller_id']}' style='margin-right:10px;'>Edit</a> 
                        <a href='php/delete_variant.php?id={$row['controller_id']}' 
                           style='color:red; text-decoration:none;' 
                           onclick='return confirm(\"Adakah anda pasti mahu memadam variasi ini?\")'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='padding:20px; text-align:center;'>Tiada rekod ditemui.</td></tr>";
    }
    ?>
</table>