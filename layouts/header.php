<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        session_destroy();
        header('location:login.php');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H_NL Store</title>
    <link href='./assets/images/logo.jpg' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" type="text/css" href="./assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">

    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/note.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

</head>


<style>
    /* General styles for the modal background */
    .cart-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeIn 0.5s ease-in-out;
    }

    .navbar .navbar-nav .nav-link {
        padding-top: 20px;
        color: rgb(255, 255, 255);
        /* Màu của thẻ a khi ở trạng thái bình thường */
    }


    /* Animations for fade-in effect */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Modal content styles */
    .cart-content {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        padding: 30px;
        width: 90%;
        max-width: 700px;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        position: relative;
        animation: zoomIn 0.4s ease-in-out;
    }

    /* Close button styles */
    .cart-content .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        transition: transform 0.3s, color 0.3s;
        z-index: 999;
        /* Đảm bảo nút đóng luôn hiển thị trên các phần tử khác */
    }

    .cart-content .close:hover {
        transform: rotate(90deg) scale(1.2);
        color: #e74c3c;
    }

    /* Heading styles */
    .cart-content h2 {
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        animation: fadeIn 0.8s ease-in-out;
    }

    /* Product list styles */
    .cart-content .product-list {
        margin: 20px 0;
    }

    .cart-content .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 12px;
        background: linear-gradient(145deg, #ffffff, #eeeeee);
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1), -3px -3px 10px rgba(255, 255, 255, 0.6);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .cart-content .product-item:hover {
        transform: scale(1.02);
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2), -5px -5px 20px rgba(255, 255, 255, 0.7);
    }

    /* Product details */
    .cart-content .product-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-content .product-info img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .cart-content .product-info p {
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }

    /* Price and quantity */
    .cart-content .product-price,
    .cart-content .product-quantity {
        font-size: 15px;
        font-weight: bold;
        color: #555;
        text-align: center;
    }

    /* Total price styles */
    .cart-content .total-price {
        font-size: 1.2rem;
        font-weight: bold;
        text-align: right;
        color: #333;
        margin-top: 20px;
        border-top: 2px solid #ddd;
        padding-top: 10px;
    }

    /* Buttons */
    .cart-content .btn {
        display: inline-block;
        padding: 12px 25px;
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
        /* Màu chữ trắng */
        background-color: #000;
        /* Nền nút màu đen */
        border-radius: 12px;
        margin-top: 20px;
        margin-right: 10px;
        text-align: center;
        text-decoration: none;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, background-color 0.3s, color 0.3s, box-shadow 0.3s;
    }

    .cart-content .btn:hover {
        transform: translateY(-3px);
        background-color: #fff;
        /* Nền nút chuyển sang trắng khi hover */
        color: #000;
        /* Màu chữ chuyển sang đen khi hover */
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.3);
    }


    /* Scrollbar styles */
    .cart-content::-webkit-scrollbar {
        width: 10px;
    }

    .nav-icons a i {
        padding-top: 20px;
        color: white;
        /* Thay đổi màu của icon thành trắng */
    }


    .cart-content::-webkit-scrollbar-thumb {
        background: linear-gradient(145deg, #000, #fff);
        border-radius: 10px;
    }

    .cart-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(145deg, #fff, #000);
    }

    .form-control {
        padding-top: 20px;
    }
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark fixed-top">
        <div class="container-fluid">
            <a href="index.php">
                <img src="./assets/images/logo.jpg" width="200px" height="60px" alt="Logo">
            </a>

            <!-- Menu chính -->
            <div class="navbar-nav ms-auto">

                <a class="nav-link menu-link" href="index.php">HOME</a>


                <div class="nav-item dropdown menu-list-item">
                    <a class="nav-link menu-link dropdown-toggle" href="all_product.php" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">PRODUCTS<i class=""
                            aria-hidden="true"></i></a>

                    <div class="megamenu-sub">


                        <div class="megamenu-sub-wrap megamenu-sub-level1 row">


                            <!-- ÁO -->

                            <div class="item col-2">
                                <a href="TOPS.php">TOPS</a>
                                <div class="megamenu-sub-level2">
                                    <div class="item">
                                        <a href="T-SHIRTS.php"><i class=""></i>T-SHIRTS</a>
                                    </div>
                                    <div class="item">
                                        <a href="SHIRTS.php"><i class=""></i>SHIRTS</a>
                                    </div>
                                    <div class="item">
                                        <a href="OUTERWEARS.php"><i class=""></i>OUTERWEARS</a>
                                    </div>
                                </div>
                            </div>

                            <div class="item col-2">
                                <a href="TOPS.php"></a>
                                <div class="megamenu-sub-level2">
                                    <div class="item">
                                        <a href="SWEATSHIRTS & HOODIES.php"><i class=""></i>SWEATSHIRTS & HOODIES</a>
                                    </div>
                                    <div class="item">
                                        <a href="SWEATERS & CARDIGANS.php"><i class=""></i>SWEATERS & CARDIGANS</a>
                                    </div>
                                </div>
                            </div>


                            <!-- QUẦN -->
                            <div class="item col-2">
                                <a href="BOTTOMS.php">BOTTOMS</a>
                                <div class="megamenu-sub-level2">
                                    <div class="item">
                                        <a href="SHORTS.php">
                                            <i class=""></i>SHORTS</a>
                                    </div>
                                    <div class="item">
                                        <a href="PANTS.php">
                                            <i class=""></i>PANTS</a>
                                    </div>
                                </div>

                            </div>

                            <!-- TÚI -->
                            <div class="item col-2">
                                <a href="BAGS.php">BAGS</a>
                                <div class="megamenu-sub-level2">
                                    <div class="item">
                                        <a href="MINI BAGS.php">
                                            <i class=""></i>MINI BAGS</a>
                                    </div>
                                    <div class="item">
                                        <a href="BIG BAGS.php">
                                            <i class=""></i>BIG BAGS</a>
                                    </div>
                                </div>
                            </div>
                            <!-- PHỤ KIỆN -->
                            <div class="item col-2">
                                <a href="ACCESSORIES.php">ACCESSORIES</a>
                            </div>
                        </div>
                    </div>
                </div>



                <a class="nav-link menu-link" href="about.php">ABOUTS</a>

                <form class="d-flex ms-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search Products"
                        aria-label="Search" required>
                    <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <div class="nav-icons ms-3">
                    <a href="javascript:void(0);" onclick="toggleCartPopup()">
                        <i class="fas fa-shopping-cart"></i></a>
                </div>

                <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Người dùng';
                ?>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle nav-link menu-link" id="userMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class=""></i> <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="account.php">
                                    <i class="fas fa-user-circle"></i> My Account
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="my_orders.php">
                                    <i class="fas fa-shopping-bag"></i> Your Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="account.php?logout=1">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="nav-link menu-link"><i class="fas fa-user"></i> </a>
                <?php } ?>




            </div>
        </div>
    </nav>







</body>

</html>
<!-- Cart Pop-up Modal -->
<div id="cartModal" class="cart-modal">
    <div class="cart-content">
        <span class="close" onclick="toggleCartPopup()">&times;</span>
        <h2>Your Cart</h2>

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>

                <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="./assets/images/<?php echo $value['product_image']; ?>"
                                    alt="<?php echo $value['product_name']; ?>">
                                <div>
                                    <p class="pt-4"><?php echo $value['product_name']; ?></p>
                                </div>

                            </div>
                        </td>

                        <td>
                            <div>
                                <p class="pt-4">
                                    <?php
                                    if ($value['product_size'] == 1) {
                                        echo "S";
                                    } elseif ($value['product_size'] == 2) {
                                        echo "M";
                                    } elseif ($value['product_size'] == 3) {
                                        echo "L";
                                    } elseif ($value['product_size'] == 4) {
                                        echo "XL";
                                    } else {
                                        echo "Pre Size"; // Giá trị mặc định nếu không khớp
                                    }
                                    ?>
                                </p>
                            </div>
                        </td>
                        <td><?php echo $value['product_quantity']; ?></td>
                        <td><?php echo number_format($value['product_price'], 3, '.', '.') . 'VND'; ?></td>
                        <td>
                            <?php
                            $line_total = (float)$value['product_price'] * (int)$value['product_quantity'];
                            echo number_format($line_total, 3, '.', '.') . '&nbsp;VND';
                            ?>
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td colspan="4" style="font-weight: bold;">Total</td>
                    <td style="font-weight: bold;"><?php echo number_format($_SESSION['total'], 3, '.', '.') . ' VND'; ?></td>
                </tr>
            </table>
            <a href="checkout.php" class="btn btn-dark">Proceed to Checkout</a>
            <a href="cart.php" class="btn btn-dark">Show Full</a>
        <?php } else { ?>
            <div class="empty-cart">
                <!-- Hình ảnh giỏ hàng trống -->
                <img src="./assets/images/empty-cart.png" alt="Giỏ hàng trống" style="max-width: 300px; display: block; margin: 0 auto;">
                <p>Your cart is empty.</p>
                <a href="index.php" class="btn btn-dark">Continue Shopping</a>
            </div>
        <?php } ?>
    </div>
</div>



<!-- cart -->
<script>
    function toggleCartPopup() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal.style.display === 'flex') {
            cartModal.style.display = 'none';
        } else {
            cartModal.style.display = 'flex';
        }
    }


    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById("cartModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }


    window.addEventListener('scroll', function() {
        let scrollPosition = window.scrollY; // Vị trí cuộn của trang
        let banner = document.querySelector('.home-slider');

        // Nếu người dùng cuộn xuống, thu nhỏ banner
        if (scrollPosition > 100) { // Bạn có thể thay đổi giá trị 100 để tùy chỉnh
            banner.classList.add('banner-shrunk');
            banner.classList.remove('banner-expanded');
        }
        // Nếu người dùng cuộn lên, phóng to banner
        else {
            banner.classList.add('banner-expanded');
            banner.classList.remove('banner-shrunk');
        }
    });
</script>