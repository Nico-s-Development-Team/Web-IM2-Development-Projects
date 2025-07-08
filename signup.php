<?php
include 'db_conn.php'; // Make sure this file correctly connects to your MySQL database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['first_name'];
    $lastName  = $_POST['last_name'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
    $contact   = $_POST['contact'];
    $address   = $_POST['address'];

    $code = rand(100000, 999999); // 6-digit code
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    
    $stmt = $conn->prepare("INSERT INTO Customer_T (Customer_FirstName, Customer_LastName, Customer_Email, Customer_ContactInfo, Customer_Address, PasswordHash) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $contact, $address, $password);

    if ($stmt->execute()) {
        $customerID = $conn->insert_id;

        // Insert into Verification_T
        $vstmt = $conn->prepare("INSERT INTO Verification_T (Customer_ID, Code, Expiry) VALUES (?, ?, ?)");
        $vstmt->bind_param("iss", $customerID, $code, $expiry);
        $vstmt->execute();

        header("Location: orderPage.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
