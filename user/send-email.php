<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../user/register-back.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Kiểm tra session người vừa đăng ký
if (!isset($_SESSION['new_user_email']) || !isset($_SESSION['new_user_name'])) {
    die("Không có thông tin người dùng để gửi email.");
}

$toEmail = $_SESSION['new_user_email'];
$toName  = $_SESSION['new_user_name'];
$username = $_SESSION['new_user_account'];
$password = $_SESSION['new_user_password'];

$mail = new PHPMailer(true);

try {
    // ===== CẤU HÌNH SMTP =====
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'lacvn2468@gmail.com';      // email gửi
    $mail->Password   = 'fliv slbt xzqq jmcr';         // mật khẩu ứng dụng
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ===== THÔNG TIN NGƯỜI GỬI =====
    $mail->setFrom('lacvn2468@gmail.com', 'MyliShop');
    $mail->addAddress($toEmail, $toName);

    // ===== NỘI DUNG EMAIL =====
    $mail->isHTML(true);
    $mail->Subject = 'Chào mừng bạn đến với MyLiShop của chúng tôi';
    $mail->Body    = "
        <h2>Chào $toName</h2>
        <p>Tài khoản của bạn đã được tạo thành công.</p>
        <p>Bạn có thể đăng nhập bằng tên đăng nhập: $username và mật khẩu: $password đã đăng ký.</p>
        <p>Chúc bạn trải nghiệm vui vẻ</p>
    ";
    $mail->CharSet = 'UTF-8';

    $mail->send();

    // Xóa session sau khi gửi email xong
    unset($_SESSION['new_user_email'], $_SESSION['new_user_name']);

    // Chuyển về login.php với thông báo thành công
    header("Location: login.php?rs=email_sent");
    exit();

} catch (Exception $e) {
    // Nếu gửi mail thất bại, vẫn redirect về login nhưng báo lỗi
    header("Location: login.php?rf=email_fail");
    exit();
}
?>
