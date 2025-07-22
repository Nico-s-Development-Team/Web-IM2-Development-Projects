<?php
require 'db_conn.php'; // your DB connection

$sql = "
    SELECT 
        d.Delivery_ID,
        d.Order_ID,
        d.Delivery_Status,
        d.Assigned_To,
        d.Completion_Time,
        c.Customer_Address
    FROM Delivery_T d
    INNER JOIN Order_T o ON d.Order_ID = o.Order_ID
    INNER JOIN Customer_T c ON o.Customer_ID = c.Customer_ID
    WHERE d.Delivery_Status IN ('Queued', 'Out for Delivery', 'Ready for Pickup')
";

$result = $conn->query($sql);

$deliveries = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $delivery = [
            'deliveryId' => $row['Delivery_ID'],
            'orderId' => '#' . $row['Order_ID'],
            'rider' => $row['Assigned_To'] ?? 'Unassigned',
            'status' => $row['Delivery_Status'],
            'address' => $row['Customer_Address']
        ];

        if (!empty($row['Completion_Time'])) {
            $delivery['completionTime'] = date('M d, Y H:i', strtotime($row['Completion_Time']));
        }

        $deliveries[] = $delivery;
    }
}

header('Content-Type: application/json');
echo json_encode($deliveries);
