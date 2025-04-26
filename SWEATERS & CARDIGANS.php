<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Cài đặt bộ lọc
$category_ids = [8]; // Bạn có thể thay bằng mảng khác nếu muốn lọc nhiều loại
$min_price = isset($_POST['min_price']) ? (int)$_POST['min_price'] : 0;
$max_price = isset($_POST['max_price']) ? (int)$_POST['max_price'] : 10000000;

// Kiểm tra có tìm kiếm không
if (isset($_POST['search'])) {
    // Đếm tổng sản phẩm phù hợp
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM products 
        WHERE category_id IN ($placeholders) 
        AND product_price BETWEEN ? AND ?
        AND products.status_products_id != 5
    ");
    $params = array_merge($category_ids, [$min_price, $max_price]);
    $stmt->bind_param(str_repeat('i', count($category_ids)) . 'ii', ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn danh sách sản phẩm
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
        WHERE category_id IN ($placeholders)
        AND product_price BETWEEN ? AND ?
        AND products.status_products_id != 5
        LIMIT ? OFFSET ?
    ");
    $params = array_merge($category_ids, [$min_price, $max_price, $products_per_page, $start_from]);
    $stmt->bind_param(str_repeat('i', count($category_ids)) . 'iiii', ...$params);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    // Khi không tìm kiếm, chỉ lọc theo danh mục
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM products 
        WHERE category_id IN ($placeholders)
        AND products.status_products_id != 5
    ");
    $stmt->bind_param(str_repeat('i', count($category_ids)), ...$category_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

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
        WHERE category_id IN ($placeholders)
        AND products.status_products_id != 5
        LIMIT ? OFFSET ?
    ");
    $params = array_merge($category_ids, [$products_per_page, $start_from]);
    $stmt->bind_param(str_repeat('i', count($category_ids)) . 'ii', ...$params);
    $stmt->execute();
    $products = $stmt->get_result();
}
<<<<<<< HEAD
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
WHERE products.category_id = 8
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
        switch ($category) {
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

=======

// Tính tổng số trang
$total_pages = ceil($total_products / $products_per_page);
?>

>>>>>>> ke
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
                            <li class="breadcrumb-item active" aria-current="page">SWEATERS & CARDIGANS</li>
                        </ol>
                    </nav>
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>
                <form id="searchForm" action="SWEATERS & CARDIGANS.php" method="POST">
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
                                        id="category_three" checked>
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
                            <div class="col-lg-12 mt-3">
                                <p class="text-uppercase fw-bold">Price Range</p>
<<<<<<< HEAD

                                <!-- Thanh kéo -->
                                <div id="priceSlider"></div>

                                <!-- Hiển thị giá -->
                                <p class="m-4 pt-4 text-uppercase fw-bold">
                                    Price: <span id="priceDisplay">1.000.000 - 8.000.000</span> VND
                                </p>

                                <!-- Hidden inputs để submit -->
                                <input type="hidden" name="min_price" id="minPrice" value="1000000">
                                <input type="hidden" name="max_price" id="maxPrice" value="8000000">
=======
                                <div class="d-flex align-items-center" style="gap: 8px;">
                                    <input type="number" name="min_price" id="minPriceInput" value="<?= $min_price ?>" class="form-control text-center" style="width: 90px;" min="1" max="10000000">
                                    <span class="mx-2 fw-bold">-</span>
                                    <input type="number" name="max_price" id="maxPriceInput" value="<?= $max_price ?>" class="form-control text-center" style="width: 90px;" min="1" max="10000000">
                                </div>
                                <p class="text-uppercase fw-bold">Price: <span id="selectedPrice"><?= $min_price ?> - <?= $max_price ?></span> VND</p>
>>>>>>> ke
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
                    <h3 class="text-uppercase fs-3">SWEATERS & CARDIGANS</h3>
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
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="container text-center pagination mt-5">
                        <?php if ($page > 1) : ?>
<<<<<<< HEAD
                            <li class="page-item"><a href="TOPS.php?page=<?php echo $page - 1; ?>"
                                    class="page-link">
                                    << </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                                    href="TOPS.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                            <li class="page-item"><a href="TOPS.php?page=<?php echo $page + 1; ?>"
                                    class="page-link"> >> </a></li>
=======
                            <li class="page-item">
                                <a href="SWEATERS & CARDIGANS.php?page=<?php echo $page - 1; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    <<
                                        </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a href="SWEATERS & CARDIGANS.php?page=<?php echo $i; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                            <li class="page-item">
                                <a href="SWEATERS & CARDIGANS.php?page=<?php echo $page + 1; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    >>
                                </a>
                            </li>
>>>>>>> ke
                        <?php endif; ?>
                    </ul>
                </nav>

        </div>

    </div>
</div>
<?php include('layouts/footer.php') ?>
<script>
    document.getElementById('minPriceInput').addEventListener('input', updatePrice);
    document.getElementById('maxPriceInput').addEventListener('input', updatePrice);

    function updatePrice() {
        var minPrice = document.getElementById('minPriceInput').value;
        var maxPrice = document.getElementById('maxPriceInput').value;
        document.getElementById('selectedPrice').textContent = minPrice + ' - ' + maxPrice;
    }
</script>

<script>
<<<<<<< HEAD
    document.addEventListener("DOMContentLoaded", function() {
        var priceSlider = document.getElementById("priceSlider");

        noUiSlider.create(priceSlider, {
            start: [1, 10000000],
            connect: true,
            step: 1000,
            range: {
                min: 1,
                max: 10000000
            },
            tooltips: true,
            format: {
                to: function(value) {
                    return Math.round(value).toLocaleString();
                },
                from: function(value) {
                    return Number(value.replace(/,/g, ''));
                }
            }
        });

        priceSlider.noUiSlider.on("update", function(values, handle) {
            const minVal = Number(values[0].replace(/,/g, ''));
            const maxVal = Number(values[1].replace(/,/g, ''));

            document.getElementById("priceDisplay").innerText = values[0] + " - " + values[1];
            document.getElementById("minPrice").value = minVal;
            document.getElementById("maxPrice").value = maxVal;
        });
    });
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
=======
    document.getElementById("searchForm").addEventListener("submit", function(e) {
        const selectedCategory = document.querySelector('input[name="category"]:checked');

        const minPrice = document.getElementById('minPriceInput').value;
        const maxPrice = document.getElementById('maxPriceInput').value;

        if (selectedCategory) {
            const category = selectedCategory.value.toLowerCase(); // xử lý dấu cách nếu có
            const currentURL = category + ".php";
            this.action = currentURL + `?page=1&min_price=${minPrice}&max_price=${maxPrice}&category=${category}`; // chuyển hướng tới trang phù hợp
        } else {
            // Không chọn gì thì giữ nguyên action hiện tại (không thay đổi URL)
            // Có thể để this.action = window.location.pathname nếu bạn muốn chắc chắn
            this.action = window.location.pathname + `?page=1&min_price=${minPrice}&max_price=${maxPrice}`;
        }
>>>>>>> ke
    });
</script>
<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet" />

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>