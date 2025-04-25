<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;

// Kiểm tra trang hiện tại, mặc định là trang 1 nếu không có trang nào được chọn
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Danh sách các danh mục cần truy vấn
$categories = ['ACCESSORIES'];

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
// Truy vấn lấy sản phẩm thuộc các category_id = 1, 6, 7, 8, 9, 10 và trạng thái sản phẩm từ cơ sở dữ liệu
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
WHERE products.category_id =4
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
            case 'ACCESSORIES':
                header('Location: ACCESSORIES.php');
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
                            <li class="breadcrumb-item active" aria-current="page">ACCESSORIES</li>
                        </ol>
                    </nav>
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>
                <form action="ACCESSORIES.php" method="POST">
                    <div class="row mx-auto container">
                        <div class="row">
                            <!-- Category Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Category</p>

                                <div class="form-check">
                                    <input type="radio" value="ACCESSORIES" class="form-check-input" name="category"
                                        id="category_one" checked>
                                    <label class="form-check-label" for="category_one">ACCESSORIES</label>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Price Range</p>

                                <!-- Thanh kéo -->
                                <div id="priceSlider"></div>

                                <!-- Hiển thị giá -->
                                <p class="m-4 pt-4 text-uppercase fw-bold">
                                    Price: <span id="priceDisplay">1.000.000 - 8.000.000</span> VND
                                </p>

                                <!-- Hidden inputs để submit -->
                                <input type="hidden" name="min_price" id="minPrice" value="1000000">
                                <input type="hidden" name="max_price" id="maxPrice" value="8000000">
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
                    <h3 class="text-uppercase fs-3">ACCESSORIES</h3>
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

                <!-- Pagination Section -->
                <nav aria-label="Page navigation example">
                    <ul class="container text-center pagination mt-5">
                        <?php if ($page > 1) : ?>
                            <li class="page-item"><a href="ACCESSORIES.php?page=<?php echo $page - 1; ?>"
                                    class="page-link">
                                    << </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                                    href="ACCESSORIES.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                            <li class="page-item"><a href="ACCESSORIES.php?page=<?php echo $page + 1; ?>"
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
    });
</script>
<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet" />

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>