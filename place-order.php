<?php
session_start();
require_once('model/connect.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

// Sanitize and retrieve customer data
$customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
$customer_phone = filter_input(INPUT_POST, 'customer_phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$customer_address = filter_input(INPUT_POST, 'customer_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Calculate total
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

try {
    // Start transaction with PDO
    $conn->beginTransaction();
    
    // 1. Insert into `orders` table
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$customer_name, $customer_email, $customer_phone, $customer_address, $total_amount]);
    
    $order_id = $conn->lastInsertId();

    // 2. Insert into `order_details` table
    $stmt_details = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt_details->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
    }

    // Commit transaction
    $conn->commit();

    // 3. Clear the cart
    unset($_SESSION['cart']);
    
    // 4. Redirect to a success page
    $_SESSION['latest_order_id'] = $order_id;
    header('Location: order-success.php');
    exit();

} catch (PDOException $exception) {
    $conn->rollBack();
    // You should log the error and show a user-friendly message
    // For now, we'll just die with the error.
    die('Order placement failed: ' . $exception->getMessage());
}

?>