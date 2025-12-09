<meta charset="utf-8">
<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../model/connect.php');
error_reporting(1);

if (isset($_POST['addProduct']))
{
    $keywordPr  = $_POST['txtKeyword']  ?? '';
    $descriptPr = $_POST['txtDescript'] ?? '';
    $status     = $_POST['status']      ?? 0;

    $namePr     = $_POST['txtName']     ?? '';
    $categoryPr = $_POST['category']    ?? '';
    $pricePr    = $_POST['txtPrice']    ?? 0;
    $salePricePr= $_POST['txtSalePrice']?? 0;
    $quantityPr = $_POST['txtNumber']   ?? 0;

    /* ======================= IMAGE HANDLE ======================= */

    $image = "";

    if (!empty($_FILES["FileImage"]["name"])) {

        $fileName = basename($_FILES["FileImage"]["name"]);
        $target_file = "../uploads/" . $fileName;   // recommend nằm trong folder riêng
        $uploadOk = 1;

        // Check file có đúng dạng ảnh không
        $check = getimagesize($_FILES["FileImage"]["tmp_name"]);
        if ($check === false) {
            header("Location: product-add.php?notimage=notimage");
            exit();
        }

        // Auto rename nếu file trùng
        if (file_exists($target_file)) {
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $file_base = pathinfo($fileName, PATHINFO_FILENAME);
            $newName = $file_base . "_" . time() . "." . $file_ext;
            $target_file = "../uploads/" . $newName;
            $fileName = $newName;
        }

        // Upload ảnh
        if (move_uploaded_file($_FILES["FileImage"]["tmp_name"], $target_file)) {
            $image = "uploads/" . $fileName;
        }
    } 
    else {
        // Không có ảnh → redirect báo lỗi
        header("Location: product-add.php?notimage=notimage");
        exit();
    }

    /* ======================= INSERT DB (PDO) ======================= */

    try {
        $sql = "INSERT INTO products 
                (name, category_id, image, description, price, saleprice, created, quantity, keyword, status) 
                VALUES 
                (:name, :category, :image, :descript, :price, :sale, NOW(), :quantity, :keyword, :status)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name',     $namePr);
        $stmt->bindParam(':category', $categoryPr);
        $stmt->bindParam(':image',    $image);
        $stmt->bindParam(':descript', $descriptPr);
        $stmt->bindParam(':price',    $pricePr);
        $stmt->bindParam(':sale',     $salePricePr);
        $stmt->bindParam(':quantity', $quantityPr);
        $stmt->bindParam(':keyword',  $keywordPr);
        $stmt->bindParam(':status',   $status);

        $stmt->execute();

        header("Location: product-list.php?addps=success");
        exit();

    } catch (PDOException $e) {
        // Debug nếu cần: echo $e->getMessage();
        header("Location: product-list.php?addpf=fail");
        exit();
    }
}
?>
