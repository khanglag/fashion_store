<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Cài đặt bộ lọc
$category_ids = [11, 12, 13]; // Bạn có thể thay bằng mảng khác nếu muốn lọc nhiều loại
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

// Tính tổng số trang
$total_pages = ceil($total_products / $products_per_page);
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
                            <li class="breadcrumb-item active" aria-current="page">BOTTOMS</li>
                        </ol>
                    </nav>
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>
                <form id="searchForm" action="BOTTOMS.php" method="POST">
                    <div class="row mx-auto container">
                        <div class="row">
                            <!-- Search by Name -->
                            <div class="col-lg-12 mb-3">
                                <p class="text-uppercase fw-bold">Product Name</p>
                                <input style="width: 220px; height: 40px; font-size: 14px"
                                    type="text"
                                    name="keyword"
                                    id="keywordInput"
                                    class="form-control"
                                    placeholder="Enter product name..."
                                    value="<?= isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '' ?>">

                            </div>

                            <!-- Category Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Category</p>

                                <div class="form-check">
                                    <input type="radio" value="SHORTS" class="form-check-input" name="category"
                                        id="category_one" <?= (isset($_POST['category']) && $_POST['category'] == 'SHORTS') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="category_one">SHORTS</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="PANTS" class="form-check-input" name="category"
                                        id="category_two" <?= (isset($_POST['category']) && $_POST['category'] == 'PANTS') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="category_two">PANTS</label>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <div class="col-lg-12 mt-3">
                                <p class="text-uppercase fw-bold">Price Range</p>
                                <div class="d-flex align-items-center" style="gap: 8px;">
                                    <input type="text" id="minPriceDisplay" class="form-control text-center"
                                        style="width: 90px; height: 32px; padding: 4px 8px; font-size: 14px" value="0">
                                    <input type="hidden" name="min_price" id="minPriceInput" value="<?= $min_price ?>">

                                    <span class="mx-2 fw-bold">-</span>

                                    <input type="text" id="maxPriceDisplay" class="form-control text-center"
                                        style="width: 90px; height: 32px; padding: 4px 8px; font-size: 14px" value="0">
                                    <input type="hidden" name="max_price" id="maxPriceInput" value="<?= $max_price ?>">
                                </div>
                                <p class="text-uppercase fw-bold">Price: <span id="selectedPrice"><?= $min_price ?> - <?= $max_price ?></span> VND</p>
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
                <div class="row" id="product-container">
                    <?php while ($row = $products->fetch_assoc()) {
                        $link = match ($row['status_products_name']) {
                            'Sold Out' => "sold_out.php?product_id=" . $row['product_id'],
                            'Pre Order' => "pre_order.php?product_id=" . $row['product_id'],
                            default => "single_product.php?product_id=" . $row['product_id']
                        };
                    ?>
                        <div class="product text-center col-lg-3 col-md-6 col-sm-12">
                            <a href="<?php echo $link; ?>" class="product-link">
                                <div class="img-container">
                                    <div class="product-status <?php echo strtolower(str_replace(' ', '-', $row['status_products_name'])); ?>">
                                        <?php echo $row['status_products_name']; ?>
                                    </div>
                                    <img class="img-fluid mb-3" src="./assets/images/<?php echo $row['product_image']; ?>" alt="Product Image">
                                    <img class="img-fluid img-second" src="./assets/images/<?php echo $row['product_image2']; ?>" alt="Second Image">
                                </div>
                                <div class="star">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h3 class="p-product"><?php echo $row['product_name']; ?></h3>
                                <p class="p-price"><?php echo number_format($row['product_price'], 0, '.', '.') . ' VND'; ?></p>
                                <p class="p-price-discount">
                                    <?php
                                    if ($row['product_price_discount'] != 0) {
                                        echo number_format($row['product_price_discount'], 0, '.', '.') . ' VND';
                                    }
                                    ?>
                                </p>
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="container text-center pagination mt-5">
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <a href="BOTTOMS.php?page=<?php echo $page - 1; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    <<
                                        </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a href="BOTTOMS.php?page=<?php echo $i; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                            <li class="page-item">
                                <a href="BOTTOMS.php?page=<?php echo $page + 1; ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>" class="page-link">
                                    >>
                                </a>
                            </li>
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
    });
</script>
<script>
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function unformatNumber(str) {
        return str.replace(/,/g, '');
    }

    function bindPriceInput(displayId, hiddenId, defaultValue) {
        const display = document.getElementById(displayId);
        const hidden = document.getElementById(hiddenId);

        if (defaultValue && defaultValue !== "0") {
            display.value = formatNumber(defaultValue);
        }

        display.addEventListener('focus', () => {
            if (display.value === "0") display.value = "";
        });

        display.addEventListener('blur', () => {
            if (display.value === "") {
                display.value = "0";
                hidden.value = "0";
            }
        });

        display.addEventListener('input', () => {
            let raw = unformatNumber(display.value);
            if (!/^\d*$/.test(raw)) {
                raw = raw.replace(/\D/g, '');
            }
            hidden.value = raw;
            display.value = formatNumber(raw);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindPriceInput('minPriceDisplay', 'minPriceInput', "<?= $min_price ?>");
        bindPriceInput('maxPriceDisplay', 'maxPriceInput', "<?= $max_price ?>");
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Mảng category_ids
        var category_ids = [11, 12, 13];
        const min_price = document.getElementById('minPriceInput').value;
        const max_price = document.getElementById('maxPriceInput').value;

        $('#keywordInput, #minPrice, #maxPrice').on('change keyup', function() {
            var keyword = $('#keywordInput').val();
            $.ajax({
                url: 'search_products.php',
                method: 'POST',
                data: {
                    keyword: keyword,
                    min_price: min_price,
                    max_price: max_price,
                    category_ids: JSON.stringify(category_ids) // Chuyển mảng thành chuỗi JSON
                },
                success: function(response) {
                    $('#product-container').html(response);
                }
            });
        });
    });
</script>