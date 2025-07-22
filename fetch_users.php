<?php
require 'db_conn.php';

$sql = "SELECT Customer_ID, Customer_FirstName, Customer_LastName, Customer_Email, Customer_ContactInfo, Customer_Address FROM Customer_T";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'name' => $row['Customer_FirstName'] . ' ' . $row['Customer_LastName'],
            'email' => $row['Customer_Email'],
            'role' => 'Customer', // All are customers unless you have other roles
            'phone' => $row['Customer_ContactInfo'],
            'address' => $row['Customer_Address']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($users);
