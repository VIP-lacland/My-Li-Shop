<?php
require_once('../model/header.php');
require_once('../model/connect.php');

// Check for product ID
if (!isset($_GET['idProduct']) || !is_numeric($_GET['idProduct'])) {
    header('Location: product-list.php?editpf=1'); // Redirect if ID is missing or invalid
    exit();
}

$productId = $_GET['idProduct'];

// Fetch product data
try {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        // Product not found
        header('Location: product-list.php?editpf=1');
        exit();
    }
} catch (PDOException $e) {
    // Database error
    die("Error fetching product data: " . $e->getMessage());
}

// Fetch categories
try {
    $cat_sql = "SELECT * FROM categories ORDER BY name ASC";
    $cat_stmt = $conn->prepare($cat_sql);
    $cat_stmt->execute();
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Chỉnh sửa sản phẩm</title>
    <style>
        .current-img-preview { max-width: 150px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Chỉnh sửa sản phẩm</h1>
                </div>

                <div class="col-lg-7" style="padding-bottom:120px">
                    <form action="productedit-back.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idProduct" value="<?= htmlspecialchars($product['id']); ?>">

                        <!-- Tên sản phẩm -->
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" name="txtName" placeholder="Nhập tên sản phẩm" value="<?= htmlspecialchars($product['name']); ?>" required />
                        </div>

                        <!-- Danh mục -->
                        <div class="form-group">
                            <label>Danh mục sản phẩm</label>
                            <select class="form-control" name="category">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (empty($categories)): ?>
                                    <option disabled>Không thể tải danh mục</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Giá + Sale -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giá sản phẩm</label>
                                    <input type="number" class="form-control" name="txtPrice" placeholder="Nhập giá sản phẩm" value="<?= htmlspecialchars($product['price']); ?>" min="0" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giá giảm (nếu có)</label>
                                    <input type="number" class="form-control" name="txtSalePrice" placeholder="Nhập giá giảm" value="<?= htmlspecialchars($product['saleprice']); ?>" min="0" />
                                </div>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="form-group">
                            <label>Số lượng sản phẩm</label>
                            <input type="number" class="form-control" name="txtQuantity" placeholder="Nhập số lượng sản phẩm" value="<?= htmlspecialchars($product['quantity']); ?>" min="0" required />
                        </div>

                        <!-- Hình ảnh -->
                        <div class="form-group">
                            <label>Hình ảnh sản phẩm</label>
                            <p>Ảnh hiện tại:</p>
                            <?php if (!empty($product['image'])): ?>
                                <img src="../<?= htmlspecialchars($product['image']); ?>" alt="Ảnh sản phẩm" class="current-img-preview">
                            <?php else: ?>
                                <p>Chưa có ảnh.</p>
                            <?php endif; ?>
                            <br><br>
                            <label>Chọn ảnh mới để thay thế (để trống nếu không muốn thay đổi)</label>
                            <input type="file" name="FileImage">
                        </div>

                        <!-- Keyword -->
                        <div class="form-group">
                            <label>Từ khóa tìm kiếm</label>
                            <input class="form-control" name="txtKeyword" placeholder="Nhập từ khóa tìm kiếm" value="<?= htmlspecialchars($product['keyword']); ?>" />
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group">
                            <label>Mô tả sản phẩm</label>
                            <textarea class="form-control" rows="3" name="txtDescript"><?= htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="updateProduct" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-save"></i> Cập nhật
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="product-list.php" class="btn btn-default btn-block btn-lg">
                                    <i class="fa fa-times"></i> Hủy
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

```

I've also improved the UI for the `product-add.php` page to match this new edit page, ensuring a consistent and professional experience.

Here are the changes for `d:\Workspace\php\DemoProject\admin\product-add.php`:

```diff