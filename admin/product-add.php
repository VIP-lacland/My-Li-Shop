<?php
require_once('../model/header.php');
require_once('../model/connect.php');

// Fetch categories
try {
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categories = []; // Handle error gracefully
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
    <title>Thêm sản phẩm</title>
</head>
<body>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Thêm sản phẩm</h1>
            </div>

            <div class="col-lg-7" style="padding-bottom:120px">
                <form action="productadd-back.php" method="POST" enctype="multipart/form-data">

                    <!-- Tên sản phẩm -->
                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input type="text" class="form-control" name="txtName" placeholder="Nhập tên sản phẩm" required />
                    </div>

                    <!-- Danh mục -->
                    <div class="form-group">
                        <label>Danh mục sản phẩm</label>
                        <select class="form-control" name="category">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
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
                                <input type="number" class="form-control" name="txtPrice" placeholder="Nhập giá sản phẩm" min="0" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giá giảm (nếu có)</label>
                                <input type="number" class="form-control" name="txtSalePrice" placeholder="Nhập giá giảm" value="0" min="0" />
                            </div>
                        </div>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-group">
                        <label>Số lượng sản phẩm</label>
                        <input type="number" class="form-control" name="txtQuantity" placeholder="Nhập số lượng sản phẩm" min="0" required />
                    </div>

                    <!-- Hình ảnh -->
                    <div class="form-group">
                        <label>Chọn hình ảnh sản phẩm</label>
                        <input type="file" name="FileImage" required>
                    </div>

                    <!-- Keyword -->
                    <div class="form-group">
                        <label>Từ khóa tìm kiếm</label>
                        <input class="form-control" name="txtKeyword" placeholder="Nhập từ khóa tìm kiếm" />
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group">
                        <label>Mô tả sản phẩm</label>
                        <textarea class="form-control" rows="3" name="txtDescript"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" name="addProduct" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-plus"></i> Thêm mới
                            </button>
                        </div>

                        <div class="col-md-6">
                            <button type="reset" class="btn btn-default btn-block btn-lg">
                                <i class="fa fa-refresh"></i> Thiết lập lại
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>