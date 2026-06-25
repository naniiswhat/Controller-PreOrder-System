<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$target = 'edit_product.php' . ($id ? '?id=' . $id : '');

header('Location: ' . $target);
exit();
