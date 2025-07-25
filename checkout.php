<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'db_conn.php';

// Parse JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    !$data || 
    !isset($data['userId']) || 
    !isset($data['basket']) || 
    !isset($data['deliveryMethod'])
) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid input: userId, basket, or deliveryMethod missing"]);
    exit;
}

$customerId = $data['userId'];
$basket = $data['basket'];
$deliveryMethod = $data['deliveryMethod']; // 'pickup' or 'delivery'

try {
    // Create Order
    $stmt = $conn->prepare("INSERT INTO Order_T (Customer_ID, Order_Date, Order_Status, Total_Amount) VALUES (?, NOW(), 'Pending', 0)");
    if (!$stmt) {
        throw new Exception("Order insert prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $customerId);
    if (!$stmt->execute()) {
        throw new Exception("Order insert execute failed: " . $stmt->error);
    }
    $orderId = $stmt->insert_id;

    // Fetch MenuItem_IDs into a map
    $menuMap = [];
    $result = $conn->query("SELECT MenuItem_ID, Item_Name FROM MenuItem_T");
    while ($row = $result->fetch_assoc()) {
        $menuMap[trim($row['Item_Name'])] = $row['MenuItem_ID'];
    }

    // Insert each basket item
    $itemStmt = $conn->prepare("INSERT INTO OrderItem_T (Order_ID, MenuItem_ID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
    if (!$itemStmt) {
        throw new Exception("Order item insert prepare failed: " . $conn->error);
    }

    $totalAmount = 0;

    foreach ($basket as $item) {
        $name = trim($item['name']);
        $qty = $item['quantity'];
        $price = $item['price'];
        $subtotal = $qty * $price;

        if (!isset($menuMap[$name])) {
            throw new Exception("Menu item not found in DB: '$name'");
        }

        $menuItemId = $menuMap[$name];

        $itemStmt->bind_param("iiid", $orderId, $menuItemId, $qty, $subtotal);
        if (!$itemStmt->execute()) {
            throw new Exception("Order item insert failed: " . $itemStmt->error);
        }

        // Decrement stock
        $stockUpdateStmt = $conn->prepare("UPDATE MenuItem_T SET Quantity = Quantity - ? WHERE MenuItem_ID = ?");
        if (!$stockUpdateStmt) {
            throw new Exception("Stock update prepare failed: " . $conn->error);
        }

        $stockUpdateStmt->bind_param("ii", $qty, $menuItemId);
        if (!$stockUpdateStmt->execute()) {
            throw new Exception("Stock update failed: " . $stockUpdateStmt->error);
        }

        $totalAmount += $subtotal;
    }

    // Update total amount
    $updateStmt = $conn->prepare("UPDATE Order_T SET Total_Amount = ? WHERE Order_ID = ?");
    if (!$updateStmt) {
        throw new Exception("Update total prepare failed: " . $conn->error);
    }

    $updateStmt->bind_param("di", $totalAmount, $orderId);
    if (!$updateStmt->execute()) {
        throw new Exception("Update total execute failed: " . $updateStmt->error);
    }

    // Insert into Delivery_T with delivery method
    // Only insert into Delivery_T if order status is 'Ready'
$statusCheckStmt = $conn->prepare("SELECT Order_Status FROM Order_T WHERE Order_ID = ?");
$statusCheckStmt->bind_param("i", $orderId);
$statusCheckStmt->execute();
$statusResult = $statusCheckStmt->get_result();

if ($statusResult && $row = $statusResult->fetch_assoc()) {
    if (strtolower($row['Order_Status']) === 'ready') {
        $deliveryStmt = $conn->prepare("INSERT INTO Delivery_T (Order_ID, Delivery_Method, Delivery_Status) VALUES (?, ?, 'Queued')");
        if (!$deliveryStmt) {
            throw new Exception("Delivery insert prepare failed: " . $conn->error);
        }

        $deliveryStmt->bind_param("is", $orderId, $deliveryMethod);
        if (!$deliveryStmt->execute()) {
            throw new Exception("Delivery insert failed: " . $deliveryStmt->error);
        }
    }
}
$statusCheckStmt->close();


    echo json_encode(["success" => true, "orderId" => $orderId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
