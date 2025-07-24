<?php
session_start();
require 'db_conn.php';

// Access control
if (!isset($_SESSION['customer_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Get JSON input from frontend
$data = json_decode(file_get_contents("php://input"), true);

// Input validation
$first_name = trim($data['first_name'] ?? '');
$last_name = trim($data['last_name'] ?? '');
$email = trim($data['email'] ?? '');
$mobile = trim($data['mobile'] ?? '');
$new_password = trim($data['new_password'] ?? '');
$current_password = trim($data['current_password'] ?? '');

// Optional: Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Optionally hash password if it was changed
$update_password = false;
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_password = true;
}

// Build query
if ($update_password) {
    $sql = "UPDATE Customer_T SET Customer_FirstName = ?, Customer_LastName = ?, Customer_Email = ?, Customer_ContactInfo = ?, Password = ? WHERE Customer_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $mobile, $hashed_password, $customer_id);
} else {
    $sql = "UPDATE Customer_T SET Customer_FirstName = ?, Customer_LastName = ?, Customer_Email = ?, Customer_ContactInfo = ? WHERE Customer_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $mobile, $customer_id);
}

// Execute and respond
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}

$stmt->close();
$conn->close();
?>
