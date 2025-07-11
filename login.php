<?php
session_start();
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT Customer_ID, PasswordHash, is_verified FROM Customer_T WHERE Customer_Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['is_verified'] == 0) {
            header("Location: index.html?error=not_verified");
            exit();
        } elseif (password_verify($password, $user['PasswordHash'])) {
            $_SESSION['customer_id'] = $user['Customer_ID'];
            header("Location: orderPage.php?login=success");
            exit();
        } else {
            header("Location: home.html?error=wrong_password");
            exit();
        }
    } else {
        header("Location: home.html?error=email_not_found");
        exit();
    }
}
?>
