<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';

// Handle add / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = db()->prepare(
            'INSERT INTO products (sku, product_name, category, quantity, reorder_level, unit_price, unit_cost)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            trim($_POST['sku']), trim($_POST['product_name']), trim($_POST['category']),
            (int) $_POST['quantity'], (int) $_POST['reorder_level'],
            (float) $_POST['unit_price'], (float) $_POST['unit_cost'],
        ]);
    } elseif (isset($_POST['delete'])) {
        $stmt = db()->prepare('DELETE FROM products WHERE product_id = ?');
        $stmt->execute([(int) $_POST['delete']]);
    }
    header('Location: products.php');
    exit;
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM products WHERE product_name LIKE ? OR sku LIKE ? ORDER BY product_name');
    $stmt->execute(["%$q%", "%$q%"]);
} else {
    $stmt = db()->query('SELECT * FROM products ORDER BY product_name');
}
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products - Inventory System</title>
<link rel="stylesheet" href="style.css">
<script src="app.js" defer></script>
</head>
<body>
<?php include 'nav.php'; ?>
<main>
  <div class="panel">
    <h2>Add product</h2>
    <form method="post" class="inline-form">
      <input name="sku" placeholder="SKU" required>
      <input name="product_name" placeholder="Product name" required>
      <input name="category" placeholder="Category" required>
      <input name="quantity" type="number" min="0" placeholder="Qty" required>
      <input name="reorder_level" type="number" min="0" placeholder="Reorder at" value="5" required>
      <input name="unit_price" type="number" step="0.01" min="0" placeholder="Price" required>
      <input name="unit_cost" type="number" step="0.01" min="0" placeholder="Cost" required>
      <button name="add" value="1">Add</button>
    </form>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2>Products (<?= count($products) ?>)</h2>
      <form method="get"><input name="q" value="<?= e($q) ?>" placeholder="Search name or SKU"><button>Search</button></form>
    </div>
    <table>
      <thead><tr><th>SKU</th><th>Name</th><th>Category</th><th>Qty</th><th>Price</th><th>Cost</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($products as $p): ?>
        <tr class="<?= $p['quantity'] <= $p['reorder_level'] ? 'low' : '' ?>">
          <td><?= e($p['sku']) ?></td>
          <td><?= e($p['product_name']) ?></td>
          <td><?= e($p['category']) ?></td>
          <td><?= $p['quantity'] ?></td>
          <td><?= number_format($p['unit_price']) ?></td>
          <td><?= number_format($p['unit_cost']) ?></td>
          <td class="actions">
            <a href="edit.php?id=<?= $p['product_id'] ?>">Edit</a>
            <form method="post" class="delete-form">
              <button name="delete" value="<?= $p['product_id'] ?>" class="link-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
