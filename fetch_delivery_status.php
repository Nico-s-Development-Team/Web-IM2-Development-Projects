<?php
require_once 'db_conn.php';

header('Content-Type: application/json'); // or 'text/plain' if not using JSON

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['error' => 'No order ID provided']);
    exit;
}

$stmt = $conn->prepare("SELECT Delivery_Status FROM Delivery_T WHERE Order_ID = ?");
if (!$stmt) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['status' => $row['Delivery_Status']]);
} else {
    echo json_encode(['status' => 'Processing your Order...']);
}

$stmt->close();
$conn->close();
