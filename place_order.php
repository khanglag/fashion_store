<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    header('location: checkout.php?message=Please login/register to place an order');
    exit();
} else {
    if (isset($_POST['place_order'])) {

        // Lấy thông tin từ form
        $name = $_POST['user_name'];
        $phone = $_POST['user_phone'];
        $address = $_POST['user_address'];
        $payment_method = $_POST['payment_method'] ?? '';

        if (empty($payment_method)) {
            die("Vui lòng chọn hình thức thanh toán.");
        }

        if (!isset($_SESSION['user_id'])) {
            die("Error: user_id is missing from the session.");
        }

        $user_id = $_SESSION['user_id'];
        $order_status = "Pending";
        $order_cost = $_SESSION['total'];
        $order_date = date('Y-m-d H:i:s');

        if (empty($_SESSION['cart'])) {
            die("Giỏ hàng trống, không thể đặt hàng.");
        }

        // CHỈNH: Thêm payment_method vào truy vấn
        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_address, order_date, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("Lỗi chuẩn bị truy vấn SQL: " . $conn->error);
        }

        $stmt->bind_param('dsissss', $order_cost, $order_status, $user_id, $phone, $address, $order_date, $payment_method);

        if (!$stmt->execute()) {
            die("Lỗi khi thêm đơn hàng: " . $stmt->error);
        }

        $order_id = $stmt->insert_id;

        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $product_name = $item['product_name'];
            $product_price = $item['product_price'];
            $product_quantity = $item['product_quantity'];
            $product_size = $item['product_size'] ?? '5';
            $product_image = $item['product_image'];

            echo "<script>console.log(" . json_encode([
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'product_quantity' => $product_quantity,
                'product_size' => $product_size,
                'product_image' => $product_image
            ]) . ");</script>";

            $stmt2 = $conn->prepare("SELECT quantity FROM products WHERE product_id = ?");
            if (!$stmt2) {
                die("Lỗi chuẩn bị truy vấn SQL kiểm tra sản phẩm: " . $conn->error);
            }

            $stmt2->bind_param('i', $product_id);
            $stmt2->execute();
            $stmt2->bind_result($quantity);
            $stmt2->fetch();
            $stmt2->close();

            if ($quantity < $product_quantity) {
                die("Lỗi: Số lượng sản phẩm $product_name không đủ trong kho.");
            }

            $new_quantity = $quantity - $product_quantity;
            $stmt3 = $conn->prepare("UPDATE products SET quantity = ? WHERE product_id = ?");
            if (!$stmt3) {
                die("Lỗi chuẩn bị truy vấn SQL cập nhật số lượng sản phẩm trong kho: " . $conn->error);
            }

            $stmt3->bind_param('ii', $new_quantity, $product_id);
            if (!$stmt3->execute()) {
                die("Lỗi khi cập nhật số lượng sản phẩm trong kho: " . $stmt3->error);
            }

            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, product_image, order_date, product_quantity, product_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt1) {
                die("Lỗi chuẩn bị truy vấn SQL cho order_items: " . $conn->error);
            }

            $stmt1->bind_param('iisdssii', $order_id, $product_id, $product_name, $product_price, $product_image, $order_date, $product_quantity, $product_size);

            if (!$stmt1->execute()) {
                die("Lỗi khi thêm sản phẩm vào order_items: " . $stmt1->error);
            }
        }

        unset($_SESSION['cart']);

        header("location:payment.php?order_status=Order successfully");
        exit();
    }
}
?>
