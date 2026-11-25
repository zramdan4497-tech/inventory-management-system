<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';

$totals = db()->query(
    "SELECT COUNT(*) AS products,
            COALESCE(SUM(quantity * unit_cost), 0)  AS stock_cost,
            COALESCE(SUM(quantity * unit_price), 0) AS stock_value
     FROM products"
)->fetch();
$lowStock = db()->query(
    'SELECT sku, product_name, quantity, reorder_level FROM products
     WHERE quantity <= reorder_level ORDER BY quantity ASC'
)->fetchAll();
$recent = db()->query(
    "SELECT o.order_id, o.customer_name, o.status, o.created_at,
            SUM(oi.quantity * oi.unit_price) AS total
     FROM orders o JOIN order_items oi ON oi.order_id = o.order_id
     GROUP BY o.order_id, o.customer_name, o.status, o.created_at
     ORDER BY o.created_at DESC LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Inventory System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
  <section class="kpis">
    <div class="card"><span class="label">Products</span><span class="value"><?= $totals['products'] ?></span></div>
    <div class="card"><span class="label">Stock value (sale)</span><span class="value"><?= number_format($totals['stock_value']) ?> EGP</span></div>
    <div class="card"><span class="label">Stock value (cost)</span><span class="value"><?= number_format($totals['stock_cost']) ?> EGP</span></div>
    <div class="card"><span class="label">Low-stock alerts</span><span class="value"><?= count($lowStock) ?></span></div>
  </section>

  <section class="grid">
    <div class="panel">
      <h2>Low stock</h2>
      <table>
        <thead><tr><th>SKU</th><th>Product</th><th>In stock</th><th>Reorder at</th></tr></thead>
        <tbody>
        <?php foreach ($lowStock as $p): ?>
          <tr class="<?= $p['quantity'] == 0 ? 'out' : 'low' ?>">
            <td><?= e($p['sku']) ?></td><td><?= e($p['product_name']) ?></td>
            <td><?= $p['quantity'] ?></td><td><?= $p['reorder_level'] ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$lowStock): ?><tr><td colspan="4">All products above reorder level.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="panel">
      <h2>Recent orders</h2>
      <table>
        <thead><tr><th>#</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $o): ?>
          <tr>
            <td><?= $o['order_id'] ?></td><td><?= e($o['customer_name']) ?></td>
            <td><span class="badge <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
            <td><?= number_format($o['total']) ?> EGP</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
</body>
</html>
