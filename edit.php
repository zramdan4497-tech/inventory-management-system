<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';

$id = (int) ($_GET['id'] ?? $_POST['product_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare(
        'UPDATE products SET sku=?, product_name=?, category=?, quantity=?, reorder_level=?, unit_price=?, unit_cost=?
         WHERE product_id=?'
    );
    $stmt->execute([
        trim($_POST['sku']), trim($_POST['product_name']), trim($_POST['category']),
        (int) $_POST['quantity'], (int) $_POST['reorder_level'],
        (float) $_POST['unit_price'], (float) $_POST['unit_cost'], $id,
    ]);
    header('Location: products.php');
    exit;
}

$stmt = db()->prepare('SELECT * FROM products WHERE product_id = ?');
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { http_response_code(404); exit('Product not found'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit product - Inventory System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
  <div class="panel narrow">
    <h2>Edit: <?= e($p['product_name']) ?></h2>
    <form method="post" class="stack-form">
      <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
      <label>SKU <input name="sku" value="<?= e($p['sku']) ?>" required></label>
      <label>Name <input name="product_name" value="<?= e($p['product_name']) ?>" required></label>
      <label>Category <input name="category" value="<?= e($p['category']) ?>" required></label>
      <label>Quantity <input name="quantity" type="number" min="0" value="<?= $p['quantity'] ?>" required></label>
      <label>Reorder level <input name="reorder_level" type="number" min="0" value="<?= $p['reorder_level'] ?>" required></label>
      <label>Unit price <input name="unit_price" type="number" step="0.01" min="0" value="<?= $p['unit_price'] ?>" required></label>
      <label>Unit cost <input name="unit_cost" type="number" step="0.01" min="0" value="<?= $p['unit_cost'] ?>" required></label>
      <button>Save changes</button>
    </form>
  </div>
</main>
</body>
</html>
