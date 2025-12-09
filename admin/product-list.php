<?php
require_once('../admin/headeradmin.php');
require_once('../model/connect.php');

// Start session to handle flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>

    <title>Danh sách sản phẩm</title>
    <style>
        .product-img { max-width: 100px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>
    <!-- page content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Danh sách sản phẩm</h1>
                <a href="product-add.php" class="btn btn-success" style="margin-bottom: 20px;">
                    <i class="fa fa-plus"></i> Thêm sản phẩm
                </a>
            </div>

            <?php
            // Improved notifications
            $alerts = [
                'ps' => ['type' => 'success', 'message' => 'Bạn đã xóa sản phẩm thành công!'],
                'pf' => ['type' => 'danger', 'message' => 'Không thể xóa sản phẩm!'],
                'addps' => ['type' => 'success', 'message' => 'Bạn đã thêm sản phẩm thành công!'],
                'addpf' => ['type' => 'danger', 'message' => 'Thêm sản phẩm thất bại!'],
                'editps' => ['type' => 'success', 'message' => 'Bạn đã cập nhật sản phẩm thành công!'],
                'editpf' => ['type' => 'danger', 'message' => 'Cập nhật sản phẩm thất bại!'],
            ];
            foreach ($alerts as $key => $alert) {
                if (isset($_GET[$key])) {
                    echo "<div class='alert alert-{$alert['type']} alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>{$alert['message']}</div>";
                }
            } ?>

            <table width="100%" class="table table-striped table-bordered table-hover dt-responsive nowrap" id="dataTables-example">
                <thead>
                    <tr align="center">
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Mã danh mục</th>
                        <th>Hình ảnh</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Giảm giá</th>
                        <th>Chỉnh sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                // Lấy dữ liệu sản phẩm bằng PDO
                $sql = "SELECT * FROM products ORDER BY id DESC";

                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $rows = [];
                }

                $stt = 1; // Initialize counter for serial number
                if (!empty($rows)) {
                    foreach ($rows as $row) {

                        // Ảnh thumb
                        $thumbImage = (!empty($row['image'])) ? "../" . $row['image'] : "";
                        $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                ?>
                        <tr class="odd gradeX" align="center">
                            <td><?= $stt++; ?></td>
                            <td><?= $productName; ?></td>
                            <td><?= $row['category_id']; ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($thumbImage, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= $productName; ?>" class="product-img">
                            </td>
                            <td><?= number_format($row['price']); ?></td>
                            <td><?= htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= number_format($row['saleprice']); ?></td>

                            <td>
                                    <a href="product-edit.php?idProduct=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                        <i class="fa fa-pencil"></i> Sửa
                                    </a>
                            </td>
                            <td>
                                    <a href="product-delete.php?idProducts=<?= $row['id'] ?>" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Xóa
                                    </a>
                                </td>

                        </tr>
                <?php
                    }
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true
    });
});
</script>
</body>
</html>