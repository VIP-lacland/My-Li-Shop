<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to redirect back to the previous page
function redirect_back() {
    // If we need to redirect for an error, we can still use this.
    $previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $previous_page");
    exit();
}

// 1. Check if product ID is provided and is a valid number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['cart_message'] = ['type' => 'error', 'text' => 'ID sản phẩm không hợp lệ!'];
    redirect_back();
}

require_once('model/connect.php');

$id = (int)$_GET['id'];
$quantity = isset($_GET['quantity']) && is_numeric($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($quantity <= 0) {
    $quantity = 1;
}

$message = null;

// 2. Fetch product from database to ensure it exists
try {
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = :id AND status = 0");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // 3. Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // 4. Add or update product in cart
        if (isset($_SESSION['cart'][$id])) {
            // Product already in cart, update quantity
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            // Product not in cart, add it with all details
            $_SESSION['cart'][$id] = [
                'quantity' => $quantity,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image']
            ];
        }
        $message = ['type' => 'success', 'text' => 'Sản phẩm đã được thêm vào giỏ hàng!'];
    } else {
        $message = ['type' => 'error', 'text' => 'Sản phẩm không tồn tại hoặc đã hết hàng!'];
    }
} catch (PDOException $e) {
    $message = ['type' => 'error', 'text' => 'Lỗi cơ sở dữ liệu!'];
    // You should log the error for debugging: error_log($e->getMessage());
}

// 5. Display the confirmation page instead of redirecting
$previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions a {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .continue-shopping {
            background-color: #6c757d;
            color: #fff;
        }
        .continue-shopping:hover {
            background-color: #5a6268;
        }
        .view-cart {
            background-color: #007bff;
            color: #fff;
        }
        .view-cart:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo htmlspecialchars($message['type']); ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        <div class="actions">
            <a href="<?php echo htmlspecialchars($previous_page); ?>" class="continue-shopping">Tiếp tục mua sắm</a>
            <a href="view-cart.php" class="view-cart">Xem giỏ hàng</a>
        </div>
    </div>
</body>
</html>