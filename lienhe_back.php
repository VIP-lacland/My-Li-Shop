<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once 'model/connect.php';

if (isset($_POST['sendcontact'])) {
    $namect = $_POST['contact-name'];
    $emailct = $_POST['contact-email'];
    $subject = $_POST['contact-subject'];
    $contentct = $_POST['contact-content'];

    try {
        // Sử dụng prepared statement để tránh SQL Injection
        $sql = "INSERT INTO contacts(name, email, title, contents, created) 
                VALUES(:name, :email, :title, :contents, NOW())";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':name', $namect, PDO::PARAM_STR);
        $stmt->bindParam(':email', $emailct, PDO::PARAM_STR);
        $stmt->bindParam(':title', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':contents', $contentct, PDO::PARAM_STR);
        
        // Thực thi
        $result = $stmt->execute();
        
        if ($result) {
            header("location:lienhe.php?cs=success");
        } else {
            header("location:lienhe.php?cf=failed");
        }
        
    } catch(PDOException $e) {
        // Xử lý lỗi, có thể ghi log hoặc hiển thị thông báo
        error_log("Lỗi khi thêm contact: " . $e->getMessage());
        header("location:lienhe.php?cf=error");
    }
}
?>