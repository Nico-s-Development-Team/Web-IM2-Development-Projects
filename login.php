<?php
session_start();
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $emailOrUsername = $_POST['email'];
    $password = $_POST['password'];

    // 1. Check Customer_T
    $stmt = $conn->prepare("SELECT Customer_ID, PasswordHash, is_verified FROM Customer_T WHERE Customer_Email = ?");
    $stmt->bind_param("s", $emailOrUsername);
    $stmt->execute();
    $customerResult = $stmt->get_result();

    if ($customerResult->num_rows === 1) {
        $user = $customerResult->fetch_assoc();

        if ($user['is_verified'] == 0) {
            header("Location: index.html?error=not_verified");
            exit();
        } elseif (password_verify($password, $user['PasswordHash'])) {
            $_SESSION['user_role'] = 'customer';
            $_SESSION['customer_id'] = $user['Customer_ID'];
            header("Location: orderPage.php?login=success");
            exit();
        } else {
            header("Location: home.html?error=wrong_password");
            exit();
        }
    }

    // 2. Check Employee_T (admins)
    $stmt = $conn->prepare("SELECT Employee_ID, PasswordHash, Role FROM Employee_T WHERE Username = ?");
    $stmt->bind_param("s", $emailOrUsername);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult->num_rows === 1) {
        $admin = $adminResult->fetch_assoc();

        if (password_verify($password, $admin['PasswordHash'])) {
            $_SESSION['user_role'] = 'admin';
            $_SESSION['admin_id'] = $admin['Employee_ID'];
            $_SESSION['admin_role'] = $admin['Role']; // Optional: if you want role-based access later
            header("Location: dashboard.html?login=success");
            exit();
        } else {
            header("Location: home.html?error=wrong_password");
            exit();
        }
    }

    // 3. If not found
    header("Location: home.html?error=user_not_found");
    exit();
}
?>
