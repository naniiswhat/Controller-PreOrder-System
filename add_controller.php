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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Controller | Controller Pre Order System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5 d-flex justify-content-center">
        <div class="card shadow p-4 w-100" style="max-width: 560px;">
            <h3 class="mb-4 text-center">Add Controller</h3>
            <form action="php/add_variant.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" for="model_name">Model Name:</label>
                    <input id="model_name" type="text" class="form-control" name="model_name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description:</label>
                    <textarea id="description" class="form-control" name="description" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price">Price (RM):</label>
                    <input id="price" type="number" step="0.01" min="0" class="form-control" name="price" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="stock">Stock Quantity:</label>
                    <input id="stock" type="number" min="0" class="form-control" name="stock" required>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="product_images">Product Images:</label>
                    <input id="product_images" class="form-control" name="product_images[]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
                    <div class="form-text">Saved in assets/uploads/controllers/{product id}/ as controller-{product id}-{number}.</div>
                </div>

                <button type="submit" name="add_variant" class="btn btn-success w-100 mb-2 d-flex align-items-center justify-content-center gap-2">
                    <img src="frontend/assets/icons/plus.svg" alt="" style="width:16px; height:16px; filter:invert(1);">
                    Add Product
                </button>
                <a href="frontend/dashboard_admin.php" class="btn btn-outline-secondary w-100">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
