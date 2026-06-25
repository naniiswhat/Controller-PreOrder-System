<?php
// Pastikan hanya admin/staff yang boleh akses
if (!isset($_SESSION['role'])) {
    die("Access Denied");
}
?>

<h3>Inventory & Controller Variants</h3>

<div style="background:#f4f4f4; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #ddd;">
    <h4>Add New Controller Variant</h4>
    <form action="php/add_variant.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="model_name" placeholder="Model Name (e.g. Pro-X)" required style="padding:8px; width:200px;">
        <input type="number" step="0.01" name="price" placeholder="Price (RM)" required style="padding:8px; width:100px;">
        <input type="number" name="stock" placeholder="Stock" required style="padding:8px; width:80px;">
        <input type="file" name="product_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple style="padding:8px;">
        <button type="submit" name="add_variant" style="display:inline-flex; align-items:center; gap:6px; padding:8px 15px; cursor:pointer;">
            <img src="frontend/assets/icons/plus.svg" alt="" style="width:16px; height:16px;">
            Add Variant
        </button>
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
                        <a href='edit_product.php?id={$row['controller_id']}' aria-label='Edit product' title='Edit' style='display:inline-flex; width:34px; height:34px; align-items:center; justify-content:center; border:1px solid #ddd; border-radius:8px; margin-right:6px; vertical-align:middle;'>
                            <img src='frontend/assets/icons/square-pen.svg' alt='' style='width:17px; height:17px; display:block;'>
                        </a>
                        <a href='php/delete_variant.php?id={$row['controller_id']}' 
                           aria-label='Delete product'
                           title='Delete'
                           style='display:inline-flex; width:34px; height:34px; align-items:center; justify-content:center; border:1px solid #ddd; border-radius:8px; vertical-align:middle;'
                           onclick='return confirm(\"Adakah anda pasti mahu memadam variasi ini?\")'>
                            <img src='frontend/assets/icons/trash-2.svg' alt='' style='width:17px; height:17px; display:block;'>
                        </a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='padding:20px; text-align:center;'>Tiada rekod ditemui.</td></tr>";
    }
    ?>
</table>
