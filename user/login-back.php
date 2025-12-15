<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../model/connect.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Chuẩn bị câu lệnh SQL với tham số
        $sql = "SELECT * FROM users WHERE username = :username AND password = md5(:password)";
        $stmt = $conn->prepare($sql);
        
        // Bind tham số
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        
        // Thực thi truy vấn
        $stmt->execute();
        
        // Lấy số lượng bản ghi
        $rows = $stmt->rowCount();
        
        if ($rows > 0) {
            // Lấy dữ liệu người dùng
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['username'] = $username;
            $_SESSION['id-user'] = $user['id'];
            
            header("location:../view-cart.php?ls=success");
            exit();
        } else {
            $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không hợp lệ!';
            header("location:../user/login.php?error=wrong");
            exit();
        }
    } catch (PDOException $e) {
        // Xử lý lỗi (trong môi trường production nên ghi log thay vì hiển thị)
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = 'Đã có lỗi xảy ra. Vui lòng thử lại sau!';
        header("location:../user/login.php?error=system");
        exit();
    }
}