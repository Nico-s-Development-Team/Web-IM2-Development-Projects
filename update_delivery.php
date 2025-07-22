<?php
// Display errors for debugging (DEV ONLY)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get POST data safely
$deliveryId  = $_POST['deliveryId'] ?? null;
$newStatus   = $_POST['deliveryStatus'] ?? null;
$assignedTo  = $_POST['assignedTo'] ?? null;

if (!$deliveryId || !$newStatus) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing deliveryId or status']);
    exit;
}

$completionTime = ($newStatus === 'Delivered') ? date('Y-m-d H:i:s') : null;

// Step 1: Update Delivery_T
$stmt = $conn->prepare("UPDATE Delivery_T SET Delivery_Status = ?, Assigned_To = ?, Completion_Time = ? WHERE Delivery_ID = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssi", $newStatus, $assignedTo, $completionTime, $deliveryId);

if ($stmt->execute()) {

    // Step 2: If delivered, update the related Order_T to Completed
    if ($newStatus === 'Delivered') {
        // First, get the Order_ID from Delivery_T
        $getOrderId = $conn->prepare("SELECT Order_ID FROM Delivery_T WHERE Delivery_ID = ?");
        if ($getOrderId) {
            $getOrderId->bind_param("i", $deliveryId);
            $getOrderId->execute();
            $getOrderId->bind_result($orderId);
            $getOrderId->fetch();
            $getOrderId->close();

            if ($orderId) {
                // Now update the order status
                $updateOrder = $conn->prepare("UPDATE Order_T SET Order_Status = 'Completed' WHERE Order_ID = ?");
                if ($updateOrder) {
                    $updateOrder->bind_param("i", $orderId);
                    $updateOrder->execute();
                    $updateOrder->close();
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'error' => 'Order update prepare failed: ' . $conn->error]);
                    exit;
                }
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Order ID fetch failed: ' . $conn->error]);
            exit;
        }
    }

    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
