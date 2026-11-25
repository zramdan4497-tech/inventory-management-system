<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $allowed = ['pending', 'shipped', 'delivered', 'cancelled'];
    if (in_array($_POST['status'], $allowed, true)) {
        $stmt = db()->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
        $stmt->execute([$_POST['status'], (int) $_POST['order_id']]);
    }
    header('Location: orders.php');
    exit;
}

$orders = db()->query(
    "SELECT o.*, SUM(oi.quantity * oi.unit_price) AS total, COUNT(oi.order_item_id) AS items
     FROM orders o JOIN order_items oi ON oi.order_id = o.order_id
     GROUP BY o.order_id, o.customer_name, o.status, o.created_at
     ORDER BY o.created_at DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders - Inventory System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
  <div class="panel">
    <h2>Orders</h2>
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Items</th><th>Total</th><th>Placed</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= $o['order_id'] ?></td>
          <td><?= e($o['customer_name']) ?></td>
          <td><?= $o['items'] ?></td>
          <td><?= number_format($o['total']) ?> EGP</td>
          <td><?= e($o['created_at']) ?></td>
          <td>
            <form method="post" class="status-form">
              <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
              <select name="status" onchange="this.form.submit()">
                <?php foreach (['pending','shipped','delivered','cancelled'] as $s): ?>
                  <option value="<?= $s ?>" <?= $s === $o['status'] ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
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
