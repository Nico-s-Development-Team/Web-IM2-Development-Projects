<?php
include 'db_conn.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch') {
    // View all items
    $sql = "SELECT MenuItem_ID, Item_Name, Price, Quantity, Category, Image_URL FROM MenuItem_T";
    $result = $conn->query($sql);

    $menu = [];
    while ($row = $result->fetch_assoc()) {
        $menu[] = [
            'id' => $row['MenuItem_ID'],
            'name' => $row['Item_Name'],
            'price' => $row['Price'],
            'stock' => $row['Quantity'],
            'category' => $row['Category'],
            'image_url' => $row['Image_URL']
        ];
    }

    echo json_encode($menu);

} elseif ($action === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO MenuItem_T (Item_Name, Price, Quantity, Category, Image_URL) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiss", $name, $price, $stock, $category, $image_url);
    $stmt->execute();

    echo json_encode(['success' => true]);

} elseif ($action === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];   
    $category = $_POST['category'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE MenuItem_T SET Item_Name = ?, Price = ?, Quantity = ?, Category = ?, Image_URL = ? WHERE MenuItem_ID = ?");
    $stmt->bind_param("sdissi", $name, $price, $stock, $category, $image_url, $id);
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
