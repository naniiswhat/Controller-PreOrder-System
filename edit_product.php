<?php
session_start();

include "includes/db_connect.php";

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: frontend/login.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: frontend/dashboard_admin.php");
    exit();
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function inventory_path()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff'
        ? 'frontend/dashboard_staff.php'
        : 'frontend/dashboard_admin.php';
}

$stmt = mysqli_stmt_init($conn);
$sql = "SELECT * FROM controllers WHERE controller_id = ?";
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Error: Record not found in database.");
    }
} else {
    die("Error: Failed to prepare database query.");
}

$images = [];
$image_stmt = mysqli_prepare($conn, "SELECT image_id, image_path FROM controller_images WHERE controller_id=? ORDER BY sort_order, image_id");

if ($image_stmt) {
    mysqli_stmt_bind_param($image_stmt, "i", $id);
    mysqli_stmt_execute($image_stmt);
    $image_result = mysqli_stmt_get_result($image_stmt);

    while ($image = mysqli_fetch_assoc($image_result)) {
        $images[] = $image;
    }
    mysqli_stmt_close($image_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Controller Pre Order System</title>
    <link rel="stylesheet" href="frontend/styles.css">
</head>
<body data-page="admin-edit">
    <nav class="top-nav" aria-label="Backend navigation">
        <a class="brand" href="frontend/dashboard_admin.php" aria-label="Admin dashboard">
            <img src="frontend/assets/logo.svg" alt="">
        </a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <section class="page backend-edit-page">
            <header class="admin-header">
                <div>
                    <h1>Edit product</h1>
                    <p class="small-link"><?php echo h($row['model_name']); ?> - backend product management</p>
                </div>
                <a class="btn secondary" href="<?php echo h(inventory_path()); ?>">Back to dashboard</a>
            </header>

            <form class="backend-edit-grid" action="php/update_stock.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo h($row['controller_id']); ?>">

                <section class="admin-panel backend-edit-main">
                    <h2>Product details</h2>

                    <div class="field">
                        <label for="model_name">Model name</label>
                        <input id="model_name" type="text" name="model_name" value="<?php echo h($row['model_name']); ?>" required>
                    </div>

                    <div class="field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Short product description"><?php echo h($row['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label for="price">Price (RM)</label>
                            <input id="price" type="number" step="0.01" min="0" name="price" value="<?php echo h($row['price']); ?>" required>
                        </div>
                        <div class="field">
                            <label for="stock">Stock quantity</label>
                            <input id="stock" type="number" min="0" name="stock" value="<?php echo h($row['stock_quantity']); ?>" required>
                        </div>
                    </div>
                </section>

                <section class="admin-panel backend-gallery-panel">
                    <div class="panel-head">
                        <div>
                            <h2>Product images</h2>
                            <p class="small-link">Uploaded images and new selections for this product.</p>
                        </div>
                    </div>

                    <div class="backend-image-grid" id="product_gallery">
                        <?php foreach ($images as $img): ?>
                            <div class="backend-image-card">
                                <img src="frontend/<?php echo h($img['image_path']); ?>" alt="<?php echo h($row['model_name']); ?>">
                                <label class="delete-image-control">
                                    <input type="checkbox" name="delete_images[]" value="<?php echo h($img['image_id']); ?>">
                                    Delete
                                </label>
                            </div>
                        <?php endforeach; ?>

                        <label class="backend-add-image" for="product_images">
                            <input id="product_images" type="file" name="product_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
                            <span>
                                <img src="frontend/assets/icons/plus.svg" alt="">
                                Add images
                                <small>Preview before saving</small>
                            </span>
                        </label>
                    </div>

                    <p class="field-hint">New images are appended to this controller gallery.</p>
                </section>

                <div class="backend-actions">
                    <button type="submit" name="update_product" class="btn with-icon">
                        <img src="frontend/assets/icons/package-check.svg" alt="">
                        Save changes
                    </button>
                    <a href="<?php echo h(inventory_path()); ?>" class="btn secondary">Cancel</a>
                </div>
            </form>
        </section>
    </main>

    <script>
        const imageInput = document.querySelector('#product_images');
        const gallery = document.querySelector('#product_gallery');
        const addTile = document.querySelector('.backend-add-image');

        imageInput?.addEventListener('change', () => {
            gallery.querySelectorAll('.backend-image-card.is-new').forEach((preview) => preview.remove());

            Array.from(imageInput.files).forEach((file) => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const preview = document.createElement('div');
                preview.className = 'backend-image-card is-new';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;
                img.addEventListener('load', () => URL.revokeObjectURL(img.src), { once: true });

                const label = document.createElement('span');
                label.className = 'new-image-label';
                label.textContent = 'Ready to upload';

                preview.append(img, label);
                gallery.insertBefore(preview, addTile);
            });
        });
    </script>
</body>
</html>
