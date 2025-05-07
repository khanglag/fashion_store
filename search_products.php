<?php
include('server/connection.php');
// Lấy các giá trị từ POST
$keyword = isset($_POST['keyword']) ? '%' . trim($_POST['keyword']) . '%' : '%';
$category_ids = isset($_POST['category_ids']) ? json_decode($_POST['category_ids'], true) : null;
$min_price = isset($_POST['min_price']) ? $_POST['min_price'] : 0;
$max_price = isset($_POST['max_price']) ? $_POST['max_price'] : PHP_INT_MAX;

// Chuẩn bị câu truy vấn SQL với các điều kiện lọc
$sql = "
    SELECT 
        product_id, product_name, product_price, product_price_discount,
        product_image, product_image2, 
        COALESCE(status_products.status_products_name, 'Unknown') AS status_products_name
    FROM products
    LEFT JOIN status_products ON products.status_products_id = status_products.status_products_id
    WHERE product_name LIKE ? 
    AND products.status_products_id != 5
";

// Thêm điều kiện lọc theo category_ids nếu có
if ($category_ids && count($category_ids) > 0) {
    // Tạo điều kiện IN cho mảng category_ids
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $sql .= " AND category_id IN ($placeholders)";
}

// Thêm điều kiện lọc theo giá nếu có
$sql .= " AND product_price BETWEEN ? AND ?";

// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);

// Gắn các tham số vào câu truy vấn
$types = str_repeat('s', count($category_ids)); // Tạo kiểu dữ liệu cho các tham số category_ids
$params = array_merge([$keyword], $category_ids, [$min_price, $max_price]);

// Gắn tham số vào câu truy vấn
$stmt->bind_param("s" . str_repeat('i', count($category_ids)) . "ii", ...$params);

$stmt->execute();
$result = $stmt->get_result();

// Render kết quả
while ($row = $result->fetch_assoc()) {
    if ($row['status_products_name'] == 'Sold Out') {
        $link = "sold_out.php?product_id=" . $row['product_id'];
    } elseif ($row['status_products_name'] == 'Pre Order') {
        $link = "pre_order.php?product_id=" . $row['product_id'];
    } else {
        $link = "single_product.php?product_id=" . $row['product_id'];
    }

    echo '<div class="product text-center col-lg-3 col-md-6 col-sm-12">
            <a href="' . $link . '" class="product-link">
                <div class="img-container">
                    <div class="product-status ' . strtolower(str_replace(' ', '-', $row['status_products_name'])) . '">
                        ' . $row['status_products_name'] . '
                    </div>
                    <img class="img-fluid mb-3" src="./assets/images/' . $row['product_image'] . '" alt="Product Image">
                    <img class="img-fluid img-second" src="./assets/images/' . $row['product_image2'] . '" alt="Second Image">
                </div>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="p-product">' . $row['product_name'] . '</h3>
                <p class="p-price">' . number_format($row['product_price'], 0, '.', '.') . ' VND</p>
                <p class="p-price-discount">';
    if ($row['product_price_discount'] != 0) {
        echo number_format($row['product_price_discount'], 0, '.', '.') . ' VND';
    }
    echo        '</p>
            </a>
        </div>';
}

if ($result->num_rows == 0) {
    echo '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
}
