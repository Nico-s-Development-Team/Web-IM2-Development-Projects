<?php
include 'db_conn.php';

// Total Orders Today
$today = date('Y-m-d');
$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_orders FROM Order_T WHERE DATE(Order_Date) = ?");
$stmt1->bind_param("s", $today);
$stmt1->execute();
$result1 = $stmt1->get_result()->fetch_assoc();

// Total Sales
$stmt2 = $conn->prepare("SELECT SUM(Total_Amount) AS total_sales FROM Order_T WHERE Order_Status IN ('Completed')");
$stmt2->execute();
$result2 = $stmt2->get_result()->fetch_assoc();

// Pending Orders
$stmt3 = $conn->prepare("SELECT COUNT(*) AS pending_orders FROM Order_T WHERE Order_Status = 'Pending'");
$stmt3->execute();
$result3 = $stmt3->get_result()->fetch_assoc();

echo json_encode([
  'totalOrders' => $result1['total_orders'] ?? 0,
  'totalSales' => $result2['total_sales'] ?? 0,
  'pendingOrders' => $result3['pending_orders'] ?? 0
]);
?>
