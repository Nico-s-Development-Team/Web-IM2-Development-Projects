<?php
require 'db_conn.php';

header('Content-Type: application/json');

// Example: Fetch top 5 best-selling or manually flagged products
$query = "SELECT * FROM MenuItem_T WHERE Recommended = 1 ORDER BY RAND() LIMIT 5";
$result = $conn->query($query);

$recommended = [];
while ($row = $result->fetch_assoc()) {
    $recommended[] = $row;
}

echo json_encode($recommended);
?>
