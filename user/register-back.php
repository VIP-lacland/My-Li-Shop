<?php
session_start();
error_reporting(E_ALL);
require_once '../model/connect.php'; // $conn = PDO instance

if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email    = $_POST['email'] ?? '';
    $address  = $_POST['address'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    try {
        $sql = "INSERT INTO users 
                (fullname, username, password, email, phone, address, role)
                VALUES 
                (:fullname, :username, :password, :email, :phone, :address, :role)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fullname' => $fullname,
            ':username' => $username,
            ':password' => md5($password), // demo, production dùng password_hash
            ':email'    => $email,
            ':phone'    => $phone,
            ':address'  => $address,
            ':role'     => 1
        ]);

        // Lưu session để gửi email
        $_SESSION['new_user_email'] = $email;
        $_SESSION['new_user_name']  = $fullname;
        $_SESSION['new_user_account']  = $username;
        $_SESSION['new_user_password']  = $password;
        
        // Chuyển hướng sang send-email.php
        header("Location: send-email.php");
        exit();

    } catch (PDOException $e) {
        header("Location: login.php?rf=fail");
        exit();
    }
}
?>
