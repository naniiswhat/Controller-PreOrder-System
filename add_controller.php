<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: frontend/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Controller | Controller Pre Order System</title>
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
                    <h1>Add new product</h1>
                    <p class="small-link">Create a new controller variant in the database.</p>
                </div>
                <a class="btn secondary" href="frontend/dashboard_admin.php">Back to dashboard</a>
            </header>
            <form class="backend-edit-grid" action="php/add_variant.php" method="POST" enctype="multipart/form-data">
                <section class="admin-panel backend-edit-main" style="grid-column: 1 / -1;">
                    <div class="field">
                        <label>Model Name</label>
                        <input type="text" name="model_name" required>
                    </div>
                    <div class="field" style="margin-top: 14px;">
                        <label>Description</label>
                        <textarea name="description" rows="4"></textarea>
                    </div>
                    <div class="form-row" style="margin-top: 14px;">
                        <div class="field">
                            <label>Price (RM)</label>
                            <input type="number" step="0.01" min="0" name="price" required>
                        </div>
                        <div class="field">
                            <label>Stock Quantity</label>
                            <input type="number" min="0" name="stock" required>
                        </div>
                    </div>
                    <div class="field" style="margin-top: 14px;">
                        <label>Product Images</label>
                        <input type="file" name="product_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple style="padding: 10px;">
                        <span class="field-hint">Select multiple images to upload to the product gallery.</span>
                    </div>
                </section>
                <div class="backend-actions" style="grid-column: 1 / -1;">
                    <button type="submit" name="add_variant" class="btn">Add Product</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>