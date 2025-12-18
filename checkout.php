<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('model/connect.php');

if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

// Calculate total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hoàn Tất Đơn Hàng - MyLiShop</title>
    <link rel="icon" type="image/png" href="images/logohong.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .checkout-container {
            margin-top: 40px;
            margin-bottom: 60px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .checkout-title {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            color: #333;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .table tfoot th {
            font-size: 18px;
        }
        .total-amount {
            color: #d9534f;
            font-weight: bold;
        }
        .form-group label {
            font-weight: 600;
        }
        .btn-place-order {
            background-color: #ff0066;
            color: white;
            font-weight: bold;
            padding: 12px 0;
            font-size: 18px;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-place-order:hover {
            background-color: #e6005c;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'model/header.php'; ?>

<div class="container">
    <div class="checkout-container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1 class="checkout-title"><i class="fa fa-check-square-o"></i> Hoàn Tất Đơn Hàng</h1>

                <h3 class="section-title">Tóm Tắt Đơn Hàng</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="text-right"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-right"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> ₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Tổng cộng:</th>
                            <th class="text-right total-amount"><?php echo number_format($total, 0, ',', '.'); ?> ₫</th>
                        </tr>
                    </tfoot>
                </table>

                <h3 class="section-title" style="margin-top: 40px;">Thông Tin Giao Hàng</h3>
                <form action="place-order.php" method="POST">
                    <div class="form-group"><label for="name">Họ và tên</label><input type="text" name="customer_name" id="name" class="form-control" placeholder="Nhập họ tên đầy đủ" required></div>
                    <div class="form-group"><label for="email">Email</label><input type="email" name="customer_email" id="email" class="form-control" placeholder="Nhập email của bạn" required></div>
                    <div class="form-group"><label for="phone">Số điện thoại</label><input type="tel" name="customer_phone" id="phone" class="form-control" placeholder="Nhập số điện thoại" required></div>
                    <div class="form-group"><label for="address">Địa chỉ nhận hàng</label><textarea name="customer_address" id="address" class="form-control" rows="3" placeholder="Vui lòng nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố)" required></textarea></div>
                    <button type="submit" class="btn btn-place-order btn-lg btn-block">XÁC NHẬN ĐẶT HÀNG</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'model/footer.php'; ?>

</body>
</html>