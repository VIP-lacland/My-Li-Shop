<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Check if product ID is provided and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 2. Check if the product exists in the cart session
    if (isset($_SESSION['cart'][$id])) {
        // 3. Remove the product from the cart using unset()
        unset($_SESSION['cart'][$id]);
    }
}

// 4. Redirect the user back to the cart page to see the changes
header("Location: view-cart.php");
exit();
?>