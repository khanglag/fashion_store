<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;

// Kiểm tra trang hiện tại, mặc định là trang 1 nếu không có trang nào được chọn
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Danh sách các danh mục cần truy vấn
$categories = ['TOPS/SWEATSHIRTS & HOODIES'];

// Kiểm tra nếu có yêu cầu tìm kiếm từ người dùng
if (isset($_POST['search'])) {
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Truy vấn tổng số sản phẩm trong các danh mục theo điều kiện tìm kiếm
    $stmt = $conn->prepare('
        SELECT COUNT(*) as total 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name IN (?) 
        AND product_price BETWEEN ? AND ?
    ');

    // Tạo mảng tham số cho bind_param
    $params = array_merge($categories, [$min_price, $max_price]);
    $stmt->bind_param(str_repeat('s', count($categories)) . 'ii', ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm theo trang hiện tại trong các danh mục
    $stmt = $conn->prepare('
        SELECT products.* 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name IN (?) 
        AND product_price BETWEEN ? AND ? 
        LIMIT ? OFFSET ?
    ');

    // Tạo mảng tham số cho bind_param
    $params = array_merge($categories, [$min_price, $max_price, $products_per_page, $start_from]);
    $stmt->bind_param(str_repeat('s', count($categories)) . 'iiii', ...$params);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    // Truy vấn tổng số sản phẩm trong các danh mục khi không có điều kiện tìm kiếm
    $stmt = $conn->prepare('
        SELECT COUNT(*) as total 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name IN (?)
    ');

    // Tạo mảng tham số cho bind_param
    $stmt->bind_param(str_repeat('s', count($categories)), ...$categories);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm trong các danh mục khi không có điều kiện tìm kiếm
    $stmt = $conn->prepare('
        SELECT products.* 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name IN (?)
        LIMIT ? OFFSET ?
    ');

    // Tạo mảng tham số cho bind_param
    $params = array_merge($categories, [$products_per_page, $start_from]);
    $stmt->bind_param(str_repeat('s', count($categories)) . 'ii', ...$params);
    $stmt->execute();
    $products = $stmt->get_result();
}
$stmt = $conn->prepare("
SELECT 
    products.product_id, 
    products.product_name, 
    products.product_price, 
    products.product_price_discount, 
    products.product_image, 
    products.product_image2, 
    COALESCE(status_products.status_products_name, 'Unknown') AS status_products_name
FROM products
LEFT JOIN status_products 
ON products.status_products_id = status_products.status_products_id
WHERE products.category_id = 9
LIMIT ? OFFSET ?
");
$stmt->bind_param('ii', $products_per_page, $start_from); // Đảm bảo đã khai báo các biến $products_per_page và $start_from
$stmt->execute();
$products = $stmt->get_result();  
$total_pages = ceil($total_products / $products_per_page); // Tổng số trang dựa trên tổng số sản phẩm
?>

<?php
    // Kiểm tra xem form có được submit hay không
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kiểm tra xem danh mục nào đã được chọn
        if (isset($_POST['category'])) {
            $category = $_POST['category'];

            // Chuyển hướng dựa trên giá trị danh mục đã chọn
            switch($category) {
                case 'T-SHIRTS':
                    header('Location: T-SHIRTS.php');
                    exit();
                case 'SHIRTS':
                    header('Location: SHIRTS.php');
                    exit();
                case 'SWEATERS & CARDIGANS':
                    header('Location: SWEATERS & CARDIGANS.php');
                    exit();
                case 'SWEATSHIRTS & HOODIES':
                    header('Location: SWEATSHIRTS & HOODIES.php');
                    exit();
                case 'OUTERWEARS':
                    header('Location: OUTERWEARS.php');
                    exit();
                default:
                    break;
            }
        }
    }
    ?>
<?php include('layouts/header.php') ?>
  
                
                <div class="container">
    <div class="row">
        
        <!-- Search Section (Filter) -->
        <div class="col-lg-3 col-md-4 col-sm-12">
            <section id="search" class="my-5 py-5 ms-2">
                <div class="container mt-5 py-5">
                                      <!-- Breadcrumb -->
                                      <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item"><a href="TOPS.php">TOPS</a></li>
                <li class="breadcrumb-item active" aria-current="page">SWEATSHIRTS & HOODIES</li>
                </ol>
                </nav>
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>
                <form action="SWEATSHIRTS & HOODIES.php" method="POST">
                    <div class="row mx-auto container">
                        <div class="row">
                            <!-- Category Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Category</p>

                                <div class="form-check">
                                    <input type="radio" value="T-SHIRTS" class="form-check-input" name="category"
                                        id="category_one">
                                    <label class="form-check-label" for="category_one">T-SHIRTS</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="SHIRTS" class="form-check-input" name="category"
                                        id="category_two">
                                    <label class="form-check-label" for="category_two">SHIRTS</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="SWEATERS & CARDIGANS" class="form-check-input" name="category"
                                        id="category_three">
                                    <label class="form-check-label" for="category_three">SWEATERS & CARDIGANS</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="SWEATSHIRTS & HOODIES" class="form-check-input" name="category"
                                        id="category_four">
                                    <label class="form-check-label" for="category_four">SWEATSHIRTS & HOODIES</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="OUTERWEARS" class="form-check-input" name="category"
                                        id="category_four">
                                    <label class="form-check-label" for="category_four">OUTERWEARS</label>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Price Range</p>
                                <input type="range" name="price" value="5000" class="form-range w-100" min="1"
                                    max="1000000" id="priceRange" oninput="updatePriceLabel(this.value)">
                                <div class="w-100">
                                    <span style="float: left;">1</span>
                                    <span style="float: right;">1.000.000 VND</span>
                                </div>
                                <!-- Display the selected price -->
                                <p class="m-4 pt-4 text-uppercase fw-bold">Price: <span id="selectedPrice">5000</span>
                                    VND</p>

                                <!-- Hidden input fields to store the min and max price (for backend usage) -->
                                <input type="hidden" name="min_price" id="minPrice" value="1">
                                <input type="hidden" name="max_price" id="maxPrice" value="1000000">
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-4">
                        <hr class="mx-auto">
                        <input type="submit" name="search" value="Search" class="btn btn-primary">
                    </div>
                </form>
            </section>
        </div>

        <!-- Products Section -->
        <div class="col-lg-9 col-md-8 col-sm-12">
            <section id="products" class="my-5 py-5">
            <div class="container text-center mt-5 py-5">
                    <h3 class="text-uppercase fs-3">SWEATSHIRTS & HOODIES</h3>
                    <hr class="mx-auto">
                </div>
                <div class="row">
                      <!-- Products Section -->
                      <?php while ($row = $products->fetch_assoc()) { 
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
                            <div class="img-container">
                            <div class="product-status <?php echo strtolower(str_replace(' ', '-', $row['status_products_name'])); ?>">
                        <?php echo $row['status_products_name']; ?>
                    </div>
                                <img class="img-fluid mb-3" src="./assets/images/<?php echo $row['product_image'] ?>" alt="Product Image">
                                <img class="img-fluid img-second" src="./assets/images/<?php echo $row['product_image2']; ?>" alt="Second Image">
                            </div>
                            <div class="star">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
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
                        <li class="page-item"><a href="TOPS.php?page=<?php echo $page - 1; ?>"
                                class="page-link"> << </a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                                href="TOPS.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                        <li class="page-item"><a href="TOPS.php?page=<?php echo $page + 1; ?>"
                                class="page-link"> >> </a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </section>
        </div>
    </div>
</div>
<?php include('layouts/footer.php') ?>

<script>
function updatePriceLabel(value) {
    document.getElementById('selectedPrice').textContent = value;
    document.getElementById('maxPrice').value = value;
}
</script>
<!-- JavaScript -->
<script>
    // Lắng nghe sự thay đổi trên các radio button
    document.querySelectorAll('input[name="category"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            // Khi một radio button được chọn, chuyển hướng tới trang tương ứng
            var category = this.value.toLowerCase(); // Lấy giá trị của category (TOPS, BOTTOMS, BAGS)
            if (category) {
                window.location.href = category + '.php'; // Chuyển hướng đến trang tương ứng
            }
        });
    });
</script>