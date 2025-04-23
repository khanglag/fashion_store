<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

$products = null;
$total_products = 0;

// Nếu có tìm kiếm
if (isset($_POST['search'])) {
    $category = $_POST['category'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Đếm tổng sản phẩm phù hợp
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name = ? 
        AND product_price BETWEEN ? AND ?
        AND status_products_id != 5
    ");
    $stmt->bind_param("sii", $category, $min_price, $max_price);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm tương ứng
    $stmt = $conn->prepare("
        SELECT 
            products.product_id, 
            products.product_name, 
            products.product_price, 
            products.product_price_discount, 
            products.product_image, 
            products.product_image2, 
            products.status_products_id, 
            COALESCE(status_products.status_products_name, 'Unknown') AS status_products_name
        FROM products
        JOIN category ON products.category_id = category.category_id 
        LEFT JOIN status_products ON products.status_products_id = status_products.status_products_id
        WHERE category.category_name = ? 
        AND product_price BETWEEN ? AND ?
        AND products.status_products_id != 5
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("siiii", $category, $min_price, $max_price, $products_per_page, $start_from);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    // Không có tìm kiếm
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM products 
        WHERE status_products_id != 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm thường
    $stmt = $conn->prepare("
        SELECT 
            products.product_id, 
            products.product_name, 
            products.product_price, 
            products.product_price_discount, 
            products.product_image, 
            products.product_image2, 
            products.status_products_id, 
            COALESCE(status_products.status_products_name, 'Unknown') AS status_products_name
        FROM products
        LEFT JOIN status_products ON products.status_products_id = status_products.status_products_id
        WHERE products.status_products_id != 5
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $products_per_page, $start_from);
    $stmt->execute();
    $products = $stmt->get_result();
}

$total_pages = ceil($total_products / $products_per_page);
?>



<?php include('layouts/header.php') ?>
<!--Image background-->
<section id="home">
    <!-- <div class="container">
        <h5>NEW ARRIVAL</h5>
        <h1><span>Best Price</span> This SeaSon</h1>
        <p>Eshop offer the best product for the most affordable of price</p>
        <button>Shop Now</button>
    </div> -->

    <!-- banner -->
    <div class="home-slider">
        <img src="./assets/images/banner.jpg" alt="Banner Image" class="banner-img">
        <img src="./assets/images/banner3.jpg" alt="Banner Image3" class="banner-img">
        <img src="./assets/images/banner2.jpg" alt="Banner Image2" class="banner-img">
    </div>


</section>
<!--Clothes-->
<section id="featured" class="my-5 py-5">

    <div class="container text-center mt-5 py-5">
        <h3 class="text-uppercase fs-1">ALL PRODUCTS</h3>
        <hr>
        <p class="fs-4">Here you can check out for our product</p>
    </div>


    <div class="row mx-auto container-fluid">
    </div>
</section>

<div class="container">
    <div class="row">
        <!-- Products Section -->
        <?php while ($row = $products->fetch_assoc()) {
            if( $row['status_products_id'] == 5) {
                continue; // Bỏ qua sản phẩm có status_products_id = 5
            }
            // Kiểm tra trạng thái sản phẩm
            if ($row['status_products_name'] == 'Sold Out') {
                $link = "sold_out.php?product_id=" . $row['product_id'];
            } elseif ($row['status_products_name'] == 'Pre Order') {
                $link = "pre_order.php?product_id=" . $row['product_id'];
            } else {
                $link = "single_product.php?product_id=" . $row['product_id'];
            }
        ?>
            <div class="product text-center col-lg-3 col-md-6 col-sm-12">
                <a href="<?php echo $link; ?>" class="product-link">
                    <div class="img-container">
                        <div class="product-status <?php echo strtolower(str_replace(' ', '-', $row['status_products_name'])); ?>">
                            <?php echo $row['status_products_name']; ?>
                        </div>
                        <img class="img-fluid mb-3" src="./assets/images/<?php echo $row['product_image'] ?>">
                        <!-- Ảnh sản phẩm thứ hai sẽ xuất hiện khi hover -->
                        <img class="img-fluid img-second" src="./assets/images/<?php echo $row['product_image2']; ?>">
                    </div>
                    <div class="star">
                        <i class="fas fa-star "></i>
                        <i class="fas fa-star "></i>
                        <i class="fas fa-star "></i>
                        <i class="fas fa-star "></i>
                        <i class="fas fa-star "></i>
                    </div>
                    <h3 class="p-product"><?php echo $row['product_name'] ?></h3>
                    <p class="p-price"><?php echo number_format($row['product_price'], 0, '.', '.') . ' VND'; ?></p>
                    <p class="p-price-discount">
                        <?php
                        if ($row['product_price_discount'] != 0) {
                            // Định dạng giá với dấu chấm cách 3 chữ số và thêm "VND"
                            echo number_format($row['product_price_discount'], 0, '.', '.') . ' VND';
                        } else {
                            echo ''; // Hiển thị khoảng trống nếu giá giảm bằng 0
                        }
                        ?>
                    </p>
                    <a href="single_product.php?product_id=<?php echo $row['product_id'] ?>"></a>
            </div>
        <?php } ?>
    </div>

    <!-- Pagination Section -->
    <nav aria-label="Page navigation example">
        <ul class="container text-center pagination mt-5">
            <?php if ($page > 1) : ?>
                <li class="page-item"><a href="index.php?page=<?php echo $page - 1; ?>"
                        class="page-link">
                        << </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                        href="all_product.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $total_pages) : ?>
                <li class="page-item"><a href="all_product.php?page=<?php echo $page + 1; ?>"
                        class="page-link"> >> </a></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>
</div>


<?php include('layouts/footer.php') ?>

<script>
    function updatePriceLabel(value) {
        document.getElementById('selectedPrice').textContent = value;
        document.getElementById('maxPrice').value = value;
    }
</script>