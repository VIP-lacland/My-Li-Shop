<?php
session_start();

if (!isset($_SESSION['latest_order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_SESSION['latest_order_id'];
unset($_SESSION['latest_order_id']); // Unset to prevent showing this page on refresh
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="alert alert-success text-center" style="margin-top: 50px;">
        <h1>Thank You!</h1>
        <p>Your order has been placed successfully.</p>
        <p>Your Order ID is: <strong><?php echo $order_id; ?></strong></p>
        <p><a href="index.php" class="btn btn-primary">Continue Shopping</a> <a href="order-history.php" class="btn btn-info">View Order History</a></p>
    </div>
</div>
</body>
</html>