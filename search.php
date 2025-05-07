<?php
include('server/connection.php');

// Lấy từ khóa tìm kiếm
$query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : '%';

// Phân trang
$products_per_page = 8;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Khoảng giá
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 1;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000000;

// Phân loại (Category)
$category_filter = isset($_GET['category']) ? $_GET['category'] : null;
$category_ids = [];

if (!empty($category_filter)) {
    $category_parts = [
        '1' => 'TOPS',
        '3' => 'BAGS',
        '4' => 'ACCESSORIES',
        '11' => 'BOTTOMS'
    ];

    $category_name = $category_parts[$category_filter] ?? '';

    if ($category_name) {
        $sql_subcategories = "SELECT category_id FROM category WHERE category_name LIKE ? OR category_name = ?";
        $stmt_subcategories = $conn->prepare($sql_subcategories);
        $category_name_with_slash = $category_name . '/%';
        $stmt_subcategories->bind_param("ss", $category_name_with_slash, $category_name);
        $stmt_subcategories->execute();
        $result_subcategories = $stmt_subcategories->get_result();

        while ($row = $result_subcategories->fetch_assoc()) {
            $category_ids[] = $row['category_id'];
        }

        $category_ids[] = $category_filter;
    }
}

// Truy vấn tổng số sản phẩm
$sql_total = "
    SELECT COUNT(*) AS total_products
    FROM products
    LEFT JOIN status_products ON products.status_products_id = status_products.status_products_id
    WHERE products.product_name LIKE ? 
    AND products.product_price BETWEEN ? AND ?";

$params_total = ["sii", $query, $min_price, $max_price];
if (!empty($category_ids)) {
    $sql_total .= " AND products.category_id IN (" . implode(',', $category_ids) . ")";
}

$total_stmt = $conn->prepare($sql_total);
$total_stmt->bind_param(...$params_total);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_products = $total_result->fetch_assoc()['total_products'];
$total_pages = ceil($total_products / $products_per_page);

// Truy vấn sản phẩm cụ thể
$sql_products = "
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
    WHERE products.product_name LIKE ? 
    AND products.product_price BETWEEN ? AND ?";

$params_products = ["sii", $query, $min_price, $max_price];
if (!empty($category_ids)) {
    $sql_products .= " AND products.category_id IN (" . implode(',', $category_ids) . ")";
}

$sql_products .= " AND products.status_products_id != 5 LIMIT ? OFFSET ?";
$params_products[0] .= "ii";
$params_products[] = $products_per_page;
$params_products[] = $start_from;

$stmt = $conn->prepare($sql_products);
$stmt->bind_param(...$params_products);
$stmt->execute();
$products = $stmt->get_result();
?>

<?php include('layouts/header.php') ?>

<div class="container">
    <div class="row">

        <!-- Bộ lọc tìm kiếm -->
        <div class="col-lg-3 col-md-4 col-sm-12">
            <section id="search" class="my-5 py-5 ms-2">
                <div class="container mt-5 py-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                            <li class="breadcrumb-item active" aria-current="page">SEARCH</li>
                        </ol>
                    </nav>
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>

                <form action="search.php" method="GET">
                    <input type="hidden" name="query" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">

                    <!-- Category -->
                    <div class="col-lg-12">
                        <p class="text-uppercase fw-bold">Category</p>
                        <?php
                        $categories = [
                            '1' => 'TOPS',
                            '11' => 'BOTTOMS',
                            '3' => 'BAGS',
                            '4' => 'ACCESSORIES'
                        ];
                        foreach ($categories as $id => $label): ?>
                            <div class="form-check">
                                <input type="radio" value="<?= $id ?>" class="form-check-input" name="category" id="category_<?= $id ?>" <?php if ($category_filter == $id) echo 'checked'; ?>>
                                <label class="form-check-label" for="category_<?= $id ?>"><?= $label ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price -->
                    <div class="col-lg-12">
                        <p class="text-uppercase fw-bold">Price Range</p>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <input type="number" name="min_price" value="<?php echo $min_price; ?>" class="form-control text-center" style="width: 90px;" min="1">
                            <span class="mx-2 fw-bold">-</span>
                            <input type="number" name="max_price" value="<?php echo $max_price; ?>" class="form-control text-center" style="width: 90px;" max="10000000">
                        </div>
                    </div>

                    <div class="form-group m-4">
                        <hr class="mx-auto">
                        <input type="submit" name="search" value="Search" class="btn btn-primary">
                    </div>
                </form>
            </section>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9 col-md-8 col-sm-12">
            <section id="products" class="my-5 py-5">
                <div class="container text-center mt-5 py-5">
                    <h3 class="text-uppercase fs-3">SEARCH : <strong><?php echo htmlspecialchars($_GET['query'] ?? ''); ?></strong></h3>
                    <p class="text-muted">Found <?php echo $total_products; ?> results</p>
                    <hr class="mx-auto">
                </div>

                <?php if ($products->num_rows > 0): ?>
                    <div class="row">
                        <?php while ($row = $products->fetch_assoc()):
                            $status = strtolower(str_replace(' ', '-', $row['status_products_name']));
                            if ($row['status_products_name'] == 'Sold Out') {
                                $link = "sold_out.php?product_id=" . $row['product_id'];
                            } elseif ($row['status_products_name'] == 'Pre Order') {
                                $link = "pre_order.php?product_id=" . $row['product_id'];
                            } else {
                                $link = "single_product.php?product_id=" . $row['product_id'];
                            }
                        ?>
                            <div class="product text-center col-lg-3 col-md-6 col-sm-12">
                                <a href="<?= $link ?>" class="product-link">
                                    <div class="product-status <?= $status ?>">
                                        <?= $row['status_products_name'] ?>
                                    </div>
                                    <div class="img-container">
                                        <img class="img-fluid mb-3" src="./assets/images/<?= $row['product_image'] ?>" alt="Product Image">
                                        <img class="img-fluid img-second" src="./assets/images/<?= $row['product_image2']; ?>" alt="Second Image">
                                    </div>
                                    <div class="star">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <h3 class="p-product"><?= $row['product_name'] ?></h3>
                                    <p class="p-price"><?= number_format($row['product_price'], 0, ',', '.') ?> VND</p>
                                    <?php if ($row['product_price_discount'] != 0): ?>
                                        <p class="p-price-discount"><?= number_format($row['product_price_discount'], 0, ',', '.') ?> VND</p>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p class="alert alert-danger">No Search Results For: <strong><?php echo htmlspecialchars($_GET['query'] ?? ''); ?></strong></p>
                    </div>
                <?php endif; ?>

                <!-- Phân trang -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center mt-4">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">&laquo;</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">&raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </section>
        </div>
    </div>
</div>



<?php include('layouts/footer.php') ?>


<script>
    function updatePrice() {
        var minPrice = document.getElementById('minPriceInput').value;
        var maxPrice = document.getElementById('maxPriceInput').value;

        document.getElementById('selectedPrice').innerText = minPrice + " - " + maxPrice ;

        // Cập nhật giá trị cho các trường ẩn
        document.getElementById('minPrice').value = minPrice;
        document.getElementById('maxPrice').value = maxPrice;
    }
</script>

<!-- JavaScript -->
<!-- <script>
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
</script> -->