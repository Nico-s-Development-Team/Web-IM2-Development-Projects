<?php
require 'db_conn.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$sql = "
    SELECT 
        d.Order_ID,
        d.Assigned_To,
        d.Delivery_Status,
        d.Completion_Time,
        c.Customer_Address
    FROM Delivery_T d
    INNER JOIN Order_T o ON d.Order_ID = o.Order_ID
    INNER JOIN Customer_T c ON o.Customer_ID = c.Customer_ID
    WHERE d.Delivery_Status IN ('Delivered', 'Failed', 'Cancelled')
    ORDER BY d.Completion_Time DESC
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);

$deliveries = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $deliveries[] = $row;
    }
}

echo json_encode($deliveries);
