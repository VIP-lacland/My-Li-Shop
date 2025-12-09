<?php
    session_start();
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once '../model/connect.php';

    if (isset($_POST['submit']))
    {
        // Lấy dữ liệu từ POST với kiểm tra tồn tại
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        try {
            // Chuẩn bị câu truy vấn với tham số để tránh SQL injection
            $sql = "INSERT INTO users (fullname, username, password, email, phone, address, role)
                    VALUES (:fullname, :username, md5(:password), :email, :phone, :address, 1)";
            
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            
            // Thực thi truy vấn
            $res = $stmt->execute();
            
            if ($res) 
            {
                header("location:login.php?rs=success");
                exit();
            }
            else 
            {
                header("location:login.php?rf=fail");
                exit();
            }
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            header("location:login.php?rf=fail&error=" . urlencode($e->getMessage()));
            exit();
        }
    }
?>