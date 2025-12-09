<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once '../model/connect.php'; // connect.php phải tạo PDO instance $pdo
error_reporting(2);

// Xóa sản phẩm theo id
if (isset($_GET['idProducts'])) {
    $idProduct = $_GET['idProducts'];

    try {
        // Prepare statement
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $idProduct]);

        if ($stmt->rowCount()) {
            echo "<script>window.location = 'product-list.php?ps=success';</script>";
        } else {
            echo "<script>window.location = 'product-list.php?pf=fail';</script>";
        }
    } catch (PDOException $e) {
        // Nếu có lỗi PDO
        echo "<script>window.location = 'product-list.php?pf=fail';</script>";
        // Có thể log $e->getMessage() ra file log để debug
    }
}
?>
