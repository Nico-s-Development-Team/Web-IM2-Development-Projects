<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['first_name'];
    $lastName  = $_POST['last_name'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $contact   = $_POST['contact'];
    $address   = $_POST['address'];

    // Check if email already exists
    $check = $conn->prepare("SELECT Customer_ID FROM Customer_T WHERE Customer_Email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Email already registered
        header("Location: main.html?signup=exists");
        exit();
    }

    // Insert into Customer_T
    $stmt = $conn->prepare("INSERT INTO Customer_T (Customer_FirstName, Customer_LastName, Customer_Email, Customer_ContactInfo, Customer_Address, PasswordHash) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $contact, $address, $password);

    if ($stmt->execute()) {
        $customerID = $conn->insert_id;
        $code = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insert into Verification_T
        $vstmt = $conn->prepare("INSERT INTO Verification_T (Customer_ID, Code, Expiry) VALUES (?, ?, ?)");
        $vstmt->bind_param("iss", $customerID, $code, $expiry);
        $vstmt->execute();

        header("Location: orderPage.php?signup=success");
        exit();
    } else {
        header("Location: home.html?signup=error");
        exit();
    }
}
?>
