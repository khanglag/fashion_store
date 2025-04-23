<?php
session_start();  // Start session at the very top
include('layouts/header.php');

?>
   <section id="home">
    <!-- banner -->
    <div class="home-slider">
    <img src="./assets/images/banner.jpg" alt="Banner Image" class="banner-img">

    <img src="./assets/images/banner2.jpg" alt="Banner Image2" class="banner-img">
</div>
</section>

<!-- Featured Section -->
    <section id="featured" class="my-5 py-5">

<div class="container text-center mt-5 py-5">
    <h3 class="text-uppercase fs-1">New Product</h3>
    <hr>
    <p class="fs-4">Here you can check out for our product</p>
</div>

<div class="row mx-auto container-fluid">
</div>
</section>

<div class="container">
<div class="row">
        <?php include('server/get_featured_product.php'); ?>

        <?php while ($row = $featured_product->fetch_assoc()) {  ?>
        <div class="product text-center col-lg-3 col-md-6 col-sm-12">
        <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>" class="product-link">
            <div class="img-container">
                <div class="product-status new-product"> <!-- Hiển thị label "New Product" -->
                    New Product
                </div>
                <!-- Ảnh sản phẩm chính -->
                <img class="img-fluid mb-3" src="./assets/images/<?php echo $row['product_image']; ?>">

                <!-- Ảnh sản phẩm thứ hai sẽ xuất hiện khi hover -->
                <img class="img-fluid img-second" src="./assets/images/<?php echo $row['product_image2']; ?>">
            </div>
            <div class="star">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3 class="p-product"><?php echo $row['product_name']; ?></h3>
            <p class="p-price"><?php  echo number_format($row['product_price'], 0, '.', '.') . ' VND';?></p>
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

            <a href="single_product.php?product_id=<?php echo $row['product_id'] ?>">
               
                </a>
                    </div>
                    <?php } ?>
                </div>
                </div>
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

              <?php
                    include('server/connection.php');
                    // Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
                    $products_per_page = 8;

                    // Kiểm tra trang hiện tại, mặc định là trang 1 nếu không có trang nào được chọn
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $start_from = ($page - 1) * $products_per_page;

                                    
                    // Kiểm tra nếu có yêu cầu tìm kiếm từ người dùng
                    if (isset($_POST['search'])) {
                        $category = $_POST['category']; // Tên danh mục đã chọn
                        $min_price = $_POST['min_price'];
                        $max_price = $_POST['max_price'];

                        // Truy vấn tổng số sản phẩm theo điều kiện tìm kiếm
                        $stmt = $conn->prepare('
                            SELECT COUNT(*) as total 
                            FROM products 
                            JOIN category ON products.category_id = category.category_id 
                            WHERE category.category_name = ? AND product_price BETWEEN ? AND ?
                            AND products.status_products_id != 5
                        ');
                        $stmt->bind_param('sii', $category, $min_price, $max_price);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $total_products = $result->fetch_assoc()['total'];

                        // Truy vấn sản phẩm theo trang hiện tại
                        $stmt = $conn->prepare('
                            SELECT products.* 
                            FROM products 
                            JOIN category ON products.category_id = category.category_id 
                            WHERE category.category_name = ? AND product_price BETWEEN ? AND ? 
                            AND products.status_products_id != 5
                            LIMIT ? OFFSET ?
                        ');
                        $stmt->bind_param('siiii', $category, $min_price, $max_price, $products_per_page, $start_from);
                        $stmt->execute();
                        $products = $stmt->get_result();
                    } else {
                        // Truy vấn tổng số sản phẩm khi không có điều kiện tìm kiếm
                        $stmt = $conn->prepare('SELECT COUNT(*) as total FROM products');
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $total_products = $result->fetch_assoc()['total'];

                        // Truy vấn sản phẩm khi không có điều kiện tìm kiếm
                        $stmt = $conn->prepare('SELECT * FROM products LIMIT ? OFFSET ?');
                        $stmt->bind_param('ii', $products_per_page, $start_from);
                        $stmt->execute();
                        $products = $stmt->get_result();
                    }
                            $total_pages = ceil($total_products / $products_per_page); // Tổng số trang dựa trên tổng số sản phẩm
                  
                                // Truy vấn lấy sản phẩm mới nhất từ cơ sở dữ liệu
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
                                   

               ?>

<div class="container">
    <div class="row">
        
                    <!-- Products Section -->
                    <?php while ($row = $products->fetch_assoc()) { 
                        if( $row['status_products_id'] == 5) {
                            continue; // Bỏ qua sản phẩm có status_products_id = 5
                        }
                                    // Kiểm tra trạng thái sản phẩm, nếu sản phẩm đã "Sold Out", "Pre Order"
                    if ($row['status_products_name'] == 'Sold Out') {
                        // Nếu sản phẩm đã Sold Out, chuyển hướng đến trang sold_out.php khi người dùng click vào
                        $link = "sold_out.php?product_id=" . $row['product_id'];
                    } elseif ($row['status_products_name'] == 'Pre Order') {
                        // Nếu sản phẩm là "Pre Order", chuyển hướng đến trang pre_order.php
                        $link = "pre_order.php?product_id=" . $row['product_id'];
                    } else {
                        // Nếu sản phẩm còn hàng, chuyển hướng đến trang single_product.php
                        $link = "single_product.php?product_id=" . $row['product_id'];
                    }

                    ?>
                    <div class="product text-center col-lg-3 col-md-6 col-sm-12">
                    <a href="<?php echo $link; ?>" class="product-link">
               
                   
                    <div class="product-status <?php echo strtolower(str_replace(' ', '-', $row['status_products_name'])); ?>">
                        <?php echo $row['status_products_name']; ?>
                    </div>
                    
                    <div class="img-container">
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
                        <p class="p-price"><?php  echo number_format($row['product_price'], 0, '.', '.') . ' VND';?></p>
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

                        </a>
                    </div>
                    <?php } ?>
    </div>

                <!-- Pagination Section -->
                <nav aria-label="Page navigation example">
                    <ul class="container text-center pagination mt-5">
                        <?php if ($page > 1) : ?>
                        <li class="page-item"><a href="index.php?page=<?php echo $page - 1; ?>"
                                class="page-link"> << </a></li>
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

<script>
function updatePriceLabel(value) {
    document.getElementById('selectedPrice').textContent = value;
    document.getElementById('maxPrice').value = value;
}

</script>
    
</section>

<?php include('layouts/footer.php'); ?>


