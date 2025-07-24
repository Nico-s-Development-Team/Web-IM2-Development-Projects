<?php
session_start();
require 'db_conn.php';

// Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: home.html?error=not_logged_in");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch user data
$sql = "SELECT * FROM Customer_T WHERE Customer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['mobile_num'];
    $newPassword = $_POST['new_password'];

    // Update query (use prepared statements)
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE Customer_T SET Customer_FirstName=?, Customer_LastName=?, Customer_Email=?, Customer_Contact=?, Customer_Password=? WHERE Customer_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $contact, $hashedPassword, $customer_id);
    } else {
        $sql = "UPDATE Customer_T SET Customer_FirstName=?, Customer_LastName=?, Customer_Email=?, Customer_Contact=? WHERE Customer_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $contact, $customer_id);
    }

    $stmt->execute();
    header("Location: profile.php?success=1");
    exit;
}

?>
