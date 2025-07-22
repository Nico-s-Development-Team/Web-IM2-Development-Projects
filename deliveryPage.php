<?php
session_start();
require_once 'db_conn.php'; // DB connection

$order_id = $_GET['order_id'] ?? null;
$order_items = [];
$subtotal = 0;

if (!$order_id) {
  die("Error: No order ID provided.");
}

$stmt = $conn->prepare("
  SELECT oi.Quantity, oi.Subtotal, mi.Item_Name 
  FROM OrderItem_T oi
  JOIN MenuItem_T mi ON oi.MenuItem_ID = mi.MenuItem_ID
  WHERE oi.Order_ID = ?
");

if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('i', $order_id);
$stmt->execute();

$result = $stmt->get_result();
if (!$result) {
  die("Execution failed: " . $stmt->error);
}

if ($result->num_rows === 0) {
  $empty_order = true;
} else {
  while ($row = $result->fetch_assoc()) {
    $subtotal += $row['Subtotal'];
    $order_items[] = [
      'name' => $row['Item_Name'],
      'quantity' => $row['Quantity'],
      'price' => $row['Subtotal'] / $row['Quantity'],
      'total' => $row['Subtotal']
    ];
  }
}

$stmt->close();

$delivery_fee = 40;
$total = $subtotal + $delivery_fee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nico's Delivery - Delivery</title>
  <link rel="stylesheet" href="deliveryPageStyle.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="wrapper">
    <a href="orderPage.php" class="backBtn">&#8249;</a>

    <div class="delivery-container">
      <div class="delivery-details-container">
        <div class="delivery-details-inside-container">
          <p id="delivery-status-indicator">
            Delivery Status: <b>Preparing your Order...</b>
          </p>
          <img src="img/deliriderr.gif" alt="Delivery Rider" id="delivery-delivery-rider" />
          <p id="delivery-time-indicator">Estimated delivery time</p>
          <p id="delivery-time">25 Min</p>
        </div>
      </div>

      <div class="order-details-container">
        <div class="order-details-inside-container">
          <h3 class="order-details-header">Order Details</h3>

          <div class="order-details-div">
            <div class="order-details-category"><p>Order Number:</p></div>
            <div class="order-details-category-value"><p><b>#<?= htmlspecialchars($order_id) ?></b></p></div>
          </div>

          <div class="order-details-div">
            <div class="order-details-category"><p>Delivery Address:</p></div>
            <div class="order-details-category-value"><p><b>404, Talamban, Cebu</b></p></div>
          </div>

          <hr class="delivery-hrline" />

          <?php if (!empty($order_items)): ?>
            <?php foreach ($order_items as $item): ?>
              <div class="order-details-div order-details-items">
                <div class="order-details-quantity"><p><?= $item['quantity'] ?>x</p></div>
                <div class="order-details-item-name"><p><?= htmlspecialchars($item['name']) ?></p></div>
                <div class="order-details-price"><p>PHP <?= number_format($item['total'], 2) ?></p></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-500">No order items found.</p>
          <?php endif; ?>

          <hr class="delivery-hrline" />

          <div class="order-details-div">
            <div class="order-details-category"><p>Item Subtotal</p></div>
            <div class="order-details-category-value"><p>PHP <?= number_format($subtotal, 2) ?></p></div>
          </div>

          <div class="order-details-div">
            <div class="order-details-category"><p>Delivery Fee</p></div>
            <div class="order-details-category-value"><p>PHP <?= number_format($delivery_fee, 2) ?></p></div>
          </div>

          <hr class="delivery-hrline" />

          <div class="order-details-div">
            <div class="order-details-category"><p><b>Total</b> (Incl. Delivery Fee)</p></div>
            <div class="order-details-category-value"><p><b>PHP <?= number_format($total, 2) ?></b></p></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($debug): ?>
  <pre>
    <?php print_r($order_items); ?>
  </pre>
  <?php endif; ?>

  <script src="delivery_status.js"></script>
</body>
</html>
