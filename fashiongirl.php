<?php 
require_once('model/connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Fashion MyLiShop - fashion mylishop"/>
    <meta name="description" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" />
    <link rel="icon" type="image/png" href="images/logohong.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
    <!-- button top -->
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>
    
    <!-- background -->
    <div class="container-fluid">
        <div id="bg">
            <?php
                try {
                    $sql = "SELECT image FROM slides WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id' => 1]);
                    
                    $row = $stmt->fetch();
                    if ($row) {
            ?>
                <img width="100%" height="100%" src="<?php echo htmlspecialchars($row['image']); ?>" alt="Background Image">
            <?php 
                    } else {
                        // Fallback image nếu không tìm thấy slide
                        echo '<img width="100%" height="100%" src="images/background.jpg" alt="Default Background">';
                    }
                } catch(PDOException $e) {
                    // Fallback nếu có lỗi
                    echo '<img width="100%" height="100%" src="images/background.jpg" alt="Default Background">';
                    error_log("Background image error: " . $e->getMessage());
                }
            ?>
        </div>
    </div><!-- /background -->

    <!-- Header -->
    <?php include("model/header.php"); ?>
    <!-- /header -->

    <div class="main">
        <!-- Content -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="product-main">
                        <!-- Thời Trang Nữ -->
                        <div class="title-product-main">
                            <h3 class="section-title">Thời Trang Nữ</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    try {
                                        $sql = "SELECT id, image, name, price FROM products WHERE category_id = :category_id";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute([':category_id' => 2]);
                                        
                                        $productCount = 0;
                                        while ($kq = $stmt->fetch()) {
                                            $productCount++;
                                            // Đảm bảo an toàn XSS
                                            $product_id = htmlspecialchars($kq['id']);
                                            $product_image = htmlspecialchars($kq['image']);
                                            $product_name = htmlspecialchars($kq['name']);
                                            $product_price = htmlspecialchars($kq['price']);
                                ?>                        
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="thumbnail">
                                                <div class="hoverimage1">
                                                    <img src="<?php echo $product_image; ?>" 
                                                         alt="<?php echo $product_name; ?>" 
                                                         width="100%" height="300"
                                                         onerror="this.src='images/default-product.jpg'">
                                                </div>
                                                <div class="name-product">
                                                    <?php echo $product_name; ?>
                                                </div>
                                                <div class="price">
                                                    Giá: <?php echo number_format($product_price, 0, ',', '.') . ' đ'; ?>
                                                </div>
                                                <div class="product-info">
                                                    <a href="addcart.php?id=<?php echo $product_id; ?>" class="btn-mua-hang">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Mua hàng  <label style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a>
                                                    <a href="detail.php?id=<?php echo $product_id; ?>" class="btn-chi-tiet">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Chi Tiết <label style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a>
                                                </div><!-- /product-info -->
                                            </div><!-- /thumbnail -->
                                        </div><!-- /col -->
                                <?php 
                                        }
                                        
                                        // Nếu không có sản phẩm nào
                                        if ($productCount === 0) {
                                            echo '<div class="col-md-12 text-center"><p class="text-muted">Hiện chưa có sản phẩm nào trong danh mục này.</p></div>';
                                        }
                                        
                                    } catch(PDOException $e) {
                                        echo '<div class="col-md-12 text-center"><p class="text-danger">Không thể tải danh sách sản phẩm. Vui lòng thử lại sau.</p></div>';
                                        error_log("Error loading women's fashion products: " . $e->getMessage());
                                    }
                                ?>
                            </div><!-- /row -->
                        </div><!-- /Thời Trang Nữ -->
            
                    </div> <!-- /product-main -->
                </div> <!-- /col -->

            </div><!-- /row -->
        </div><!-- /container -->
    </div>
    <!-- /main -->

    <!-- partner -->
    <div class="container">
        <div class="title-product-main">
            <h3 class="section-title">Hãng Thời Trang Nổi Tiếng</h3>
        </div>
        <?php include("model/partner.php"); ?>
    </div>
    <!-- /partner -->

    <!-- footer -->
    <div class="container">
        <?php include("model/footer.php"); ?>
    </div>
    <!-- /footer -->

    
<script>
    new WOW().init();
    
    // Xử lý khi hình ảnh bị lỗi
    document.addEventListener('DOMContentLoaded', function() {
        var images = document.querySelectorAll('.hoverimage1 img');
        images.forEach(function(img) {
            img.addEventListener('error', function() {
                this.src = 'images/default-product.jpg';
            });
        });
    });
</script>
</body>
</html>