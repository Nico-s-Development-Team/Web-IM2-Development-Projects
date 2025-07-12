<?php
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

} elseif ($action === 'update_status') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Order_T SET Order_Status = ? WHERE Order_ID = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>
