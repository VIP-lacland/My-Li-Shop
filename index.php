<?php
    session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once('model/connect.php');
    $prd = 0;
    if (!empty($_SESSION['cart'])) {
        // Sum the quantity of all items in the cart
        $prd = array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="title" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="description" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" /> -->
    <link rel="icon" type="image/png" href="images/logohong.png">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" type="text/css" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'> -->

    <!-- customer js -->
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <!-- customer css -->
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <style>
        /* Modern button styles */
        .thumbnail {
            position: relative;
            overflow: hidden; /* Ensures buttons don't overflow */
            border: 1px solid #e9e9e9;
            transition: box-shadow 0.3s ease;
        }
        .thumbnail:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .thumbnail .product-info {
            opacity: 0; /* Hide buttons by default */
            position: absolute;
            bottom: -20px; /* Start slightly below */
            left: 0;
            right: 0;
            padding: 15px;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            transition: all 0.35s ease;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .thumbnail:hover .product-info {
            opacity: 1; /* Show on hover */
            bottom: 0; /* Slide up */
        }
        .product-info .btn {
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 8px 15px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        .product-info .btn-buy {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .product-info .btn-buy:hover {
            background-color: #0069d9; /* A slightly different blue for hover */
            border-color: #0062cc;
            color: #000; /* Change font color to black on hover */
        }
        .product-info .btn-buy:active { /* Style for when the button is clicked */
            background-color: #0056b3; /* A darker blue for the active state */
            border-color: #004085;
            color: #8ec5ff; /* Light blue text color for click state */
            transform: translateY(1px); /* Gives a subtle "press" effect */
        }
        .product-info .btn-detail {
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            border-color: #ccc;
        }
        .product-info .btn-detail:hover {
            background-color: #fff;
            color: #007bff;
            border-color: #007bff;
        }
        .product-info .btn-detail:active { /* Style for when the button is clicked */
            background-color: #e9ecef;
            border-color: #007bff;
            transform: translateY(1px); /* Gives a subtle "press" effect */
        }
    </style>

</head>

<body>
    <!-- button top -->
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- Header -->
    <?php include 'model/header.php'; ?>
    <!-- /header -->

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php include("model/slide.php"); ?>
            </div>
        </div>
        
        <?php include("model/banner.php"); ?>
        
        <div class="row">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="product-main">
                        <!-- sản phẩm mới -->
                        <div class="title-product-main">
                            <h3 class="section-title">Sản phẩm mới</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    $sql = "SELECT id,image,name,price FROM products WHERE category_id=3 AND status = 0 LIMIT 8";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    while ($kq = $stmt->fetch()) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>" title="Thêm vào giỏ hàng">
                                                <button type="button" class="btn btn-buy">
                                                    <i class="fa fa-shopping-cart"></i> Mua
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id']; ?>" title="Xem chi tiết">
                                                <button type="button" class="btn btn-detail">
                                                    <i class="fa fa-info-circle"></i> Chi tiết
                                                </button>
                                            </a>
                                        </div><!-- /product-info -->
                                    </div><!-- /thumbnail -->
                                </div><!-- /col -->
                                <?php } ?>
                            </div><!-- /row -->
                        </div><!-- /sản phẩm mới -->

                        <!-- Thời Trang Nam -->
                        <div class="title-product-main">
                            <h3 class="section-title">Thời Trang Nam</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    $sql = "SELECT id,image,name,price FROM products WHERE category_id=1 LIMIT 8";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    
                                    while ($kq = $stmt->fetch()) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>" title="Thêm vào giỏ hàng">
                                                <button type="button" class="btn btn-buy">
                                                    <i class="fa fa-shopping-cart"></i> Mua
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id']; ?>" title="Xem chi tiết">
                                                <button type="button" class="btn btn-detail">
                                                    <i class="fa fa-info-circle"></i> Chi tiết
                                                </button>
                                            </a>
                                        </div><!-- /product-info -->
                                    </div><!-- /thumbnail -->
                                </div><!-- /col -->
                                <?php } ?>
                            </div><!-- /row -->
                        </div><!-- /Thời Trang Nam -->

                        <!-- Thời Trang Nữ -->
                        <div class="title-product-main">
                            <h3 class="section-title">Thời Trang Nữ</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    $sql = "SELECT id,image,name,price FROM products WHERE category_id=2 LIMIT 8";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    
                                    while ($kq = $stmt->fetch()) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>" title="Thêm vào giỏ hàng">
                                                <button type="button" class="btn btn-buy">
                                                    <i class="fa fa-shopping-cart"></i> Mua
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id']; ?>" title="Xem chi tiết">
                                                <button type="button" class="btn btn-detail">
                                                    <i class="fa fa-info-circle"></i> Chi tiết
                                                </button>
                                            </a>
                                        </div><!-- /product-info -->
                                    </div><!-- /thumbnail -->
                                </div><!-- /col -->
                                <?php } ?>
                            </div><!-- /row -->
                        </div><!-- /Thời Trang Nữ -->

                    </div> <!-- /product-main -->
                </div> <!-- /col -->

            </div><!-- /row -->
        </div><!-- /container -->
    </div>
    <!-- /Main Content -->

    <!-- Partner -->
    <div class="container">
        <?php include("model/partner.php"); ?>
    </div>
    <!-- /Partner -->

    <!-- Footer -->
    <?php include("model/footer.php"); ?>
    <!-- /footer -->
</body>
</html>