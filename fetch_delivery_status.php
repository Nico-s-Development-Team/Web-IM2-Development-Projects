<?php
require_once 'db_conn.php';

header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['error' => 'No order ID provided']);
    exit;
}

$stmt = $conn->prepare("SELECT Delivery_Status, Assigned_To FROM Delivery_T WHERE Order_ID = ?");
if (!$stmt) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode([
        'status' => $row['Delivery_Status'] ?? 'Processing your Order...',
        'rider' => $row['Assigned_To'] ?? 'Unassigned'
    ]);
} else {
    echo json_encode([
        'status' => 'Processing your Order...',
        'rider' => 'Unassigned'
    ]);
}

$stmt->close();
$conn->close();
