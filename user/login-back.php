<?php
    session_start();
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once('../model/connect.php');

    if (isset($_POST['submit']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Chuẩn bị câu truy vấn với tham số để tránh SQL injection
            $sql = "SELECT * FROM users WHERE username = :username AND password = md5(:password)";
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            
            // Thực thi truy vấn
            $stmt->execute();
            
            // Đếm số dòng kết quả
            $rows = $stmt->rowCount();
            
            if ($rows > 0)
            {
                // Lấy dữ liệu người dùng
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $_SESSION['username'] = $username; // Initializing Session, Khởi tạo Session cho username
                $_SESSION['id-user'] = $user['id'];
                
                header("location:../index.php?ls=success");
                exit();

            } else {
                $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không hợp lệ!';
                
                header("location:../user/login.php?error=wrong");
                exit();
            }
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            $_SESSION['error'] = 'Đã xảy ra lỗi trong quá trình đăng nhập!';
            header("location:../user/login.php?error=system");
            exit();
        }
    } else {
        // echo 'lala';
    }
?>