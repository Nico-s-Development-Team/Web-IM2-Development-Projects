<?php
include 'db_conn.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch') {
    // View all items
    $sql = "SELECT MenuItem_ID, Item_Name, Price, Quantity FROM MenuItem_T";
    $result = $conn->query($sql);

    $menu = [];
    while ($row = $result->fetch_assoc()) {
        $menu[] = [
            'id' => $row['MenuItem_ID'],
            'name' => $row['Item_Name'],
            'price' => $row['Price'],
            'stock' => $row['Quantity']
        ];
    }

    echo json_encode($menu);

} elseif ($action === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO MenuItem_T (Item_Name, Price, Quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $name, $price, $stock);
    $stmt->execute();

    echo json_encode(['success' => true]);

} elseif ($action === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE MenuItem_T SET Item_Name = ?, Price = ?, Quantity = ? WHERE MenuItem_ID = ?");
    $stmt->bind_param("sdii", $name, $price, $stock, $id);
    $stmt->execute();

    echo json_encode(['success' => true]);

} elseif ($action === 'delete') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM MenuItem_T WHERE MenuItem_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['success' => true]);

} else {
    echo json_encode(['error' => 'Invalid action']);
}

?>
