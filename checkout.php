<?php
session_start();
require_once 'server/connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user_id'])) {
    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $user_id = $_SESSION['user_id'];
    $query = "SELECT full_name, user_phone, user_address FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    // Người dùng chưa đăng nhập
    header('location:login.php');
    exit();
}

// Kiểm tra nếu form checkout được gửi
if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
        echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location.href='cart.php';</script>";
        exit();
    }

    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $customer_name = $user['full_name'];
    $customer_phone = $user['user_phone'];
    $customer_address = $user['user_address'];

    // Lưu thông tin đơn hàng
    $order_date = date("Y-m-d H:i:s");
    $order_status = strtolower('pending'); // Đảm bảo trạng thái là chữ thường trước khi truyền vào SQL
    $order_query = "INSERT INTO orders (customer_id, order_date, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("iss", $user_id, $order_date, $order_status);

    $stmt->execute();
    $order_id = $stmt->insert_id;
    $real_query = addslashes("INSERT INTO orders (customer_id, order_date, status) VALUES ($user_id, '$order_date', 'pending')");

    echo "<script>alert(\"$real_query\");</script>";


    // Lưu từng sản phẩm trong giỏ hàng
    $detail_query = "INSERT INTO order_details (order_id, product_id, product_name, product_size_id, product_price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($detail_query);
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("iisidi", $order_id, $item['product_id'], $item['product_name'], $item['product_size_id'], $item['product_price'], $item['product_quantity']);
        $stmt->execute();
    }

    // Xóa giỏ hàng và chuyển hướng về trang chủ
    unset($_SESSION['cart']);
    header("Location: index.php");
    exit();
}

function calculateTotalCart()
{
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            $price = $value['product_price'];
            $quantity = $value['product_quantity'];
            $total += ($price * $quantity);
        }
        $_SESSION['total'] = $total;
    } else {
        $_SESSION['total'] = 0;
    }
}

calculateTotalCart();
?>
<?php include('layouts/header.php'); ?>

<!-- Checkout page -->
<section class="my-5 py-5">
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Your Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Check Out</li>
            </ol>
        </nav>
    </div>

    <h2 class="text-uppercase text-center">Check out</h2>
    <hr class="mx-auto">

    <form action="place_order.php" method="POST">
        <div class="container">
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_GET['message'] ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="d-flex justify-content-between w-100">
                    <!-- Shipping Address -->
                    <div class="col-md-6">
                        <div class="sub-title">
                            <h3>Shipping Address</h3>
                        </div>

                        <div class="card-body shadow-lg border-0 checkout-form">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="user_name" name="user_name"
                                            value="<?php echo htmlspecialchars($user['full_name']); ?>"
                                            placeholder="Nhập họ và tên" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="user_phone" name="user_phone"
                                            value="<?php echo htmlspecialchars($user['user_phone']); ?>"
                                            placeholder="Nhập số điện thoại" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="user_address" id="user_address" class="form-control"
                                            placeholder="Nhập địa chỉ giao hàng" required><?php echo htmlspecialchars($user['user_address']); ?></textarea>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-5">
                        <div class="sub-title">
                            <h3>Order Summary</h3>
                        </div>

                        <div class="cart-summery shadow-lg border-0">
                            <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                                <div class="d-flex pb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                    <h6 style="flex: 2; word-wrap: break-word;">
                                        <?php echo $value['product_name'] ?> x <?php echo $value['product_quantity']  ?>
                                    </h6>
                                    <h6 style="flex: 1; text-align: right;">
                                        <?php


                                        echo number_format((float)$value['product_price'] * (int)$value['product_quantity'], 3, '.', '.') . ' VND';
                                        ?>
                                    </h6>
                                </div>
                            <?php } ?>

                            <div class="d-flex pb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h6 style="flex: 2; font-weight: bold;">Shipping</h6>
                                <h6 style="flex: 1; text-align: right;">0 VND</h6>
                            </div>
                            <div class="d-flex summery-end" style="display: flex; justify-content: space-between; align-items: center;">
                                <h6 style="flex: 2; font-weight: bold;">Total</h6>
                                <h6 style="flex: 1; text-align: right; font-weight: bold;"><?php echo number_format($_SESSION['total'], 3, '.', '.') . ' VND'; ?></h6>
                            </div>

                            <style>
                                .payment-method input[type="radio"] {
                                    display: none;
                                }

                                .payment-method input[type="radio"]:checked+div {
                                    border-color: #28a745;
                                    background-color: #f0fff4;
                                    box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
                                    transform: scale(1.05);
                                }
                            </style>

                            <div class="payment-method" style="margin-top: 20px;">
                                <h6 style="font-weight: bold; flex:2; margin-bottom: 20px;">Payment Method</h6>
                                <div style="display: flex; flex-direction: row; justify-content: center; gap: 20px;">

                                    <!-- Cash -->
                                    <label style="cursor: pointer; text-align: center;">
                                        <input type="radio" name="payment_method" value="cash">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 10px; padding: 10px; transition: all 0.3s ease; width: 120px;">
                                            <img src="assets/images/cash_icon.png" alt="Cash" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                            <span style="margin-top: 8px; font-weight: bold; font-size: 14px; color: #333;">CASH</span>
                                        </div>
                                    </label>

                                    <!-- Bank -->
                                    <label style="cursor: pointer; text-align: center;">
                                        <input type="radio" name="payment_method" value="bank">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 10px; padding: 10px; transition: all 0.3s ease; width: 120px;">
                                            <img src="assets/images/bank_icon.png" alt="Bank" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                            <span style="margin-top: 8px; font-weight: bold; font-size: 14px; color: #333;">BANK</span>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <button class="btn btn-success" name="place_order" style="width: 180px; padding: 10px;">Place Order </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</section>

<script>
    document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            console.log("Selected Payment Method: ", this.value);
        });
    });
</script>

<?php include('layouts/footer.php'); ?>