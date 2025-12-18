<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['latest_order_id'])) {
    header('Location: index.php');
    exit();
}

// Lấy mã đơn hàng từ session và xóa nó đi để tránh hiển thị lại khi tải lại trang
$order_id = $_SESSION['latest_order_id'];
unset($_SESSION['latest_order_id']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công - MyLiShop</title>
    <link rel="icon" type="image/png" href="images/logohong.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .success-container {
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounceIn 0.8s;
        }
        .success-container h1 {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        .success-container p {
            font-size: 18px;
            color: #555;
            margin-bottom: 25px;
        }
        .order-id {
            font-size: 20px;
            font-weight: bold;
            color: #d9534f;
            background-color: #f9f2f2;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php include 'model/header.php'; ?>
    <div class="container">
        <div class="success-container">
            <div class="success-icon"><i class="fa fa-check-circle"></i></div>
            <h1>Đặt Hàng Thành Công!</h1>
            <p>Cảm ơn bạn đã tin tưởng và mua sắm tại MyLiShop. Đơn hàng của bạn đã được ghi nhận.</p>
            <p>Mã đơn hàng của bạn là: <strong class="order-id"><?php echo htmlspecialchars($order_id); ?></strong></p>
            <p style="margin-top: 30px;">
                <a href="index.php" class="btn btn-primary btn-lg">Tiếp tục mua sắm</a>
                <a href="order-history.php" class="btn btn-info btn-lg">Xem lịch sử đơn hàng</a>
            </p>
        </div>
    </div>
    <?php include 'model/footer.php'; ?>
</body>
</html>