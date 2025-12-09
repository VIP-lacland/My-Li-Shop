<?php
include '../model/header.php';
require_once("../model/connect.php"); // connect.php tạo PDO instance $pdo
error_reporting(2);

// Thông báo sửa sản phẩm
if (isset($_GET['es'])) echo "<script>alert('Bạn đã sửa sản phẩm thành công!');</script>";
if (isset($_GET['ef'])) echo "<script>alert('Sửa sản phẩm thất bại!');</script>";

// Lấy dữ liệu sản phẩm
$product = null;
$categories = [];

if (isset($_GET['idProduct'])) {
    $idProduct = $_GET['idProduct'];

    // Lấy sản phẩm
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $idProduct]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $thumImage = "../" . $product['image'];

        // Lấy danh mục
        $stmtCat = $pdo->query("SELECT * FROM categories");
        $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Xử lý submit edit
if (isset($_POST['editProduct']) && $product) {
    $namePr = $_POST['txtName'] ?? '';
    $categoryPr = $_POST['category'] ?? 0;
    $pricePr = $_POST['txtPrice'] ?? 0;
    $salePricePr = $_POST['txtSalePrice'] ?? 0;
    $quantityPr = $_POST['txtNumber'] ?? 0;
    $keywordPr = $_POST['txtKeyword'] ?? '';
    $descriptPr = $_POST['txtDescript'] ?? '';
    $status = $_POST['status'] ?? 0;

    // Xử lý ảnh
    $image = $_FILES['FileImage']['name'] ?? '';
    if ($image) {
        $target_file = "../" . basename($image);
        $check = getimagesize($_FILES["FileImage"]["tmp_name"]);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES["FileImage"]["tmp_name"], $target_file)) {
                $image = $product['image']; // giữ ảnh cũ nếu upload lỗi
            }
        } else {
            $image = $product['image']; // giữ ảnh cũ nếu file không phải ảnh
        }
    } else {
        $image = $product['image']; // giữ ảnh cũ nếu không chọn file
    }

    // Update sản phẩm với PDO
    $sql = "UPDATE products SET name = :name, category_id = :category, image = :image, description = :description, price = :price, saleprice = :saleprice, quantity = :quantity, keyword = :keyword, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name' => $namePr,
        'category' => $categoryPr,
        'image' => $image,
        'description' => $descriptPr,
        'price' => $pricePr,
        'saleprice' => $salePricePr,
        'quantity' => $quantityPr,
        'keyword' => $keywordPr,
        'status' => $status,
        'id' => $idProduct
    ]);

    if ($result) {
        header("Location: product-edit.php?idProduct=$idProduct&es=editsuccess");
        exit();
    } else {
        header("Location: product-edit.php?idProduct=$idProduct&ef=editfail");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Chỉnh sửa sản phẩm</title>
</head>
<body>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12"><h1 class="page-header">Chỉnh sửa sản phẩm</h1></div>
            <div class="col-lg-7" style="padding-bottom:120px">
                <?php if ($product): ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input type="text" class="form-control" name="txtName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Danh mục sản phẩm</label>
                        <select class="form-control" name="category">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Chọn hình ảnh sản phẩm</label>
                        <input type="file" name="FileImage">
                        <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <img src="<?php echo $thumImage; ?>" width="150" height="150">
                    </div>

                    <div class="form-group">
                        <label>Mô tả sản phẩm</label>
                        <textarea class="form-control" rows="3" name="txtDescript"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Giá sản phẩm</label>
                            <input type="number" class="form-control" name="txtPrice" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Phần trăm giảm</label>
                            <input type="number" class="form-control" name="txtSalePrice" value="<?php echo $product['saleprice']; ?>" min="0" max="50">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Số lượng sản phẩm</label>
                        <input type="number" class="form-control" name="txtNumber" value="<?php echo $product['quantity']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Từ khóa tìm kiếm</label>
                        <input type="text" class="form-control" name="txtKeyword" value="<?php echo htmlspecialchars($product['keyword']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Tình trạng sản phẩm</label>
                        <label class="radio-inline"><input type="radio" name="status" value="0" <?php echo ($product['status']==0)?'checked':''; ?>> Còn hàng</label>
                        <label class="radio-inline"><input type="radio" name="status" value="1" <?php echo ($product['status']==1)?'checked':''; ?>> Hết hàng</label>
                    </div>

                    <button type="submit" name="editProduct" class="btn btn-warning btn-lg">Chỉnh sửa sản phẩm</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
