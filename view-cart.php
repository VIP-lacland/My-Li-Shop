<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('model/connect.php');

$cart = $_SESSION['cart'] ?? [];
$total_items = 0;
$grand_total = 0;

if (!empty($cart)) {
    foreach ($cart as $item) {
        $total_items += $item['quantity'];
        $grand_total += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng Của Bạn</title>
    <!-- Adding Bootstrap for form styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .cart-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #0056b3;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .cart-table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        .cart-table td {
            vertical-align: middle;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .product-info {
            display: flex;
            align-items: center;
        }
        .product-name {
            font-weight: 500;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .remove-btn {
            color: #dc3545;
            text-decoration: none;
            font-size: 1.2em;
        }
        .remove-btn:hover {
            color: #a71d2a;
        }
        .cart-summary {
            text-align: right;
            margin-top: 30px;
        }
        .cart-summary h3 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .cart-summary .grand-total {
            color: #28a745;
            font-weight: bold;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        .action-btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .continue-shopping-btn {
            background-color: #6c757d;
            color: #fff;
        }
        .continue-shopping-btn:hover {
            background-color: #5a6268;
        }
        .checkout-btn {
            background-color: #007bff;
            color: #fff;
        }
        .checkout-btn:hover {
            background-color: #0056b3;
        }
        .empty-cart {
            text-align: center;
            padding: 50px;
        }
        .empty-cart i {
            font-size: 5em;
            color: #ccc;
        }
        .empty-cart p {
            font-size: 1.2em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1><i class="fa fa-shopping-cart"></i> Giỏ Hàng Của Bạn</h1>

        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <i class="fa fa-shopping-basket"></i>
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="index.php" class="action-btn continue-shopping-btn">Bắt đầu mua sắm</a>
            </div>
        <?php else: ?>
            <form action="place-order.php" method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th colspan="2">Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tạm tính</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                    <tr>
                        <td style="width: 100px;">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                        </td>
                        <td>
                            <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        </td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                        <td>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                        </td>
                        <td><strong><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> ₫</strong></td>
                        <td>
                            <!-- Link to remove item, e.g., remove_from_cart.php?id=... -->
                            <a href="remove-from-cart.php?id=<?php echo $id; ?>" class="remove-btn" title="Xóa sản phẩm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Tổng cộng: <span class="grand-total"><?php echo number_format($grand_total, 0, ',', '.'); ?> ₫</span></h3>
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3 style="text-align: center; margin-bottom: 20px;">Thông tin giao hàng</h3>
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" name="customer_name" id="name" class="form-control" placeholder="Nhập họ tên đầy đủ" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="customer_email" id="email" class="form-control" placeholder="Nhập email của bạn" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" name="customer_phone" id="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea name="customer_address" id="address" class="form-control" rows="3" placeholder="Nhập địa chỉ nhận hàng" required></textarea>
                    </div>
                </div>
            </div>

            <div class="cart-actions">
                <a href="index.php" class="action-btn continue-shopping-btn"><i class="fa fa-arrow-left"></i> Tiếp tục mua sắm</a>
                <button type="submit" class="action-btn checkout-btn">Hoàn tất đơn hàng <i class="fa fa-check"></i></button>
            </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
    // The old script is no longer needed as we are using a standard form submission.
    // It has been removed.
    </script>
</body>
</html>