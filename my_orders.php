<?php

session_start();
include('server/connection.php');

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
    }
}

if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];

    // Truy vấn để lấy tất cả đơn hàng của người dùng
    $stmt = $conn->prepare('SELECT * FROM orders 
                            JOIN order_items ON orders.order_id = order_items.order_id 
                            WHERE orders.user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    // Kiểm tra nếu không có đơn hàng
    if (!$orders) {
        echo "No orders found.";
        exit();
    }

    // Nhóm các đơn hàng theo order_id
    $ordersData = [];
    while ($row = $orders->fetch_assoc()) {
        $ordersData[$row['order_id']][] = $row;
    }
} else {
    echo "You are not logged in.";
    exit();
}

?>

<?php include('layouts/header.php') ?>

<!--Account page-->
<section class="my-5 py-5">
    <div class="container mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item"><a href="account.php">My Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Orders</li>
            </ol>
        </nav>

        <!-- Thanh Menu -->
        <div class="d-flex justify-content-center mb-5">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="account.php" class="nav-link font-weight-bold">
                        <i class="fas fa-user-alt"></i> My Account
                    </a>
                </li>
                <li class="nav-item">
                    <a href="my_orders.php" class="nav-link font-weight-bold active">
                        <i class="fas fa-shopping-bag"></i> My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="account.php?logout=1" class="nav-link font-weight-bold">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Bảng đơn hàng -->
        <div class="account-update text-center">
            <h3 class="text-uppercase mb-4">Your Orders</h3>
            <table class="orders mx-auto">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Cost</th>
                        <th>Quantity</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Recipient Address</th>
                        <th>Order Details</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Display orders -->
                    <?php foreach ($ordersData as $orderItems) {
                        $firstItem = $orderItems[0]; // Lấy thông tin của đơn hàng đầu tiên
                    ?>
                        <tr class="text-uppercase font-weight">
                            <td><?php echo $firstItem['order_id']; ?></td>
                            <td><?php echo number_format($firstItem['order_cost'], 3, '.', '.') . ' VND'; ?></td>
                            <td><?php echo array_sum(array_column($orderItems, 'product_quantity')); ?></td>
                            <td>
                                <?php
                                $status = strtolower($firstItem['order_status']); // Đảm bảo giá trị trong cơ sở dữ liệu là chữ thường
                                $statusClass = '';

                                switch ($status) {
                                    case 'pending':
                                        $statusClass = 'bg-warning';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'bg-info';
                                        break;
                                    case 'delivered':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary'; // Lớp mặc định nếu không khớp trạng thái
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $statusClass; ?> p-2 text-uppercase">
                                    <?php echo htmlspecialchars($firstItem['order_status']); ?>
                                </span>
                            </td>
                            
                            <td><?php echo $firstItem['order_date']; ?></td>
                            <td><?php echo $firstItem['user_address']; ?></td>
                            <td>
                                <form action="order_details.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $firstItem['order_id']; ?>">
                                    <input type="submit" name="order_details" class="btn custom-badge" value="Details">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include('layouts/footer.php') ?>