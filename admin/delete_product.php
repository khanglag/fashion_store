<?php
include('../server/connection.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Kiểm tra xem sản phẩm đã được bán ra chưa
    $check_query = "SELECT COUNT(*) AS sold_count FROM order_items WHERE product_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Nếu sản phẩm đã được bán ra
    if ($row['sold_count'] > 0) {
        // Cập nhật sản phẩm để ẩn nó (ẩn trên website)
        $update_query = "UPDATE products SET status_products_id = 5 WHERE product_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('i', $product_id);

        if ($update_stmt->execute()) {
            header('location:list_products.php?message=Product hidden successfully');
        } else {
            header('location:list_products.php?error=Error hiding product');
        }
    } else {
        // Nếu sản phẩm chưa bán, hỏi lại người dùng và xoá sản phẩm
        echo "
        <script>
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'delete_product.php?product_id=$product_id&action=delete';
            } else {
                window.location.href = 'list_products.php';
            }
        </script>";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Xoá sản phẩm nếu người dùng xác nhận
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $stmt = $conn->prepare('DELETE FROM products WHERE product_id = ?');
        $stmt->bind_param('i', $product_id);
        if ($stmt->execute()) {
            header('location:list_products.php?message=Product deleted successfully');
        } else {
            header('location:list_products.php?error=Error deleting product');
        }
    }
}
?>
