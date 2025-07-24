<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_conn.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch') {
    $sql = "SELECT o.Order_ID, c.Customer_FirstName, c.Customer_LastName, o.Order_Date, o.Order_Status, o.Total_Amount 
            FROM Order_T o
            JOIN Customer_T c ON o.Customer_ID = c.Customer_ID
            ORDER BY o.Order_Date DESC";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'id' => $row['Order_ID'],
            'customer' => $row['Customer_FirstName'] . ' ' . $row['Customer_LastName'],
            'date' => $row['Order_Date'],
            'status' => $row['Order_Status'],
            'total' => $row['Total_Amount']
        ];
    }
    echo json_encode($orders);
    exit;
}

elseif ($action === 'update_status') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Order_T SET Order_Status = ? WHERE Order_ID = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

elseif ($action === 'details' && isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT o.Order_ID, o.Order_Date, o.Order_Status AS Status, o.Total_Amount,
                               c.Customer_FirstName, c.Customer_LastName
                            FROM Order_T o
                            JOIN Customer_T c ON o.Customer_ID = c.Customer_ID
                            WHERE o.Order_ID = ?");

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $stmt_items = $conn->prepare("SELECT m.Item_Name AS Menu_Name, oi.Quantity, oi.Subtotal AS Price
                                        FROM OrderItem_T oi
                                        JOIN MenuItem_T m ON oi.MenuItem_ID = m.MenuItem_ID
                                        WHERE oi.Order_ID = ?");
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $items_result = $stmt_items->get_result();
        $items = [];

        while ($row = $items_result->fetch_assoc()) {
            $items[] = $row;
        }

        echo json_encode([
            'success' => true,
            'order' => $order,
            'items' => $items
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found.']);
    }
    exit;
}

else {
    echo json_encode(['error' => 'Invalid action']);
    exit;
}
?>
