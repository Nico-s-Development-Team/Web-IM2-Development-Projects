<?php
// fetch_menu.php
require 'db_conn.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';

if (!$category) {
    echo json_encode(['error' => 'Category required']);
    exit;
}

$query = "SELECT * FROM MenuItem_T WHERE Category = ? ORDER BY Item_Name ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
?>
