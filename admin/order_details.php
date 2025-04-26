<?php
include('../server/connection.php');

// Khởi tạo biến
$order_details = null;
$orders = null;

// Kiểm tra nếu `order_id` tồn tại và có chi tiết đơn hàng
// if (isset($_POST['order_details']) && isset($_POST['order_id'])) {
    if (isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        $order_status = $_POST['order_status'];
        // Kiểm tra trạng thái hiện tại của đơn hàng
        $stmt_check_status = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
        $stmt_check_status->bind_param('i', $order_id);
        $stmt_check_status->execute();
        $result = $stmt_check_status->get_result();
        $current_status = $result->fetch_assoc()['order_status'];
        // Đảm bảo trạng thái không thể thay đổi ngược
        if (($current_status == 'delivered' || $current_status == 'cancelled') && $current_status != $order_status) {
            echo "Không thể cập nhật trạng thái nữa vì đơn hàng đã được giao hoặc hủy.";
            exit;
        }
    
        $valid_statuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
        if (!in_array($order_status, $valid_statuses)) {
            echo "Trạng thái không hợp lệ.";
            exit;
        }
    
    
        $status_order = ['pending' => 0, 'confirmed' => 1, 'delivered' => 2, 'cancelled' => 3];
    
        if ($status_order[$order_status] < $status_order[$current_status]) {
            echo "Không thể cập nhật trạng thái ngược lại.";
            exit;
        }
        // Cập nhật trạng thái
        $stmt2 = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $stmt2->bind_param('si', $order_status, $order_id);
    
        if ($stmt2->execute()) {
            header('location:list_orders.php?message=Order status updated successfully');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
elseif (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Truy vấn đơn hàng
    $stmt = $conn->prepare('SELECT * FROM orders INNER JOIN users ON orders.user_id = users.user_id WHERE order_id = ?');
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    if ($orders->num_rows === 0) {
        die("Không tìm thấy đơn hàng.");
    }

    // Truy vấn sản phẩm trong đơn hàng
    $stmt1 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt1->bind_param('i', $order_id);
    $stmt1->execute();
    $order_details = $stmt1->get_result();

    if ($order_details === false) {
        die("Lỗi: " . $conn->error);
    }
}  else {
    echo "No order ID provided.";
    exit;
}
?>


<?php include('../admin/layouts/app.php') ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Details</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_orders.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <?php if ($orders && $orders->num_rows > 0) { ?>
                                <?php foreach ($orders as $order) { ?>
                                    <div class="row invoice-info" style="margin-bottom:15px;">
                                        <div class="col-sm-6 invoice-col" style=" margin-right: 20px;">
                                            <h1 class="h5 mb-3">Shipping Address</h1>
                                            <address>
                                                <b>Full name: </b><?php echo htmlspecialchars($order['full_name']); ?><br>
                                                <b>Address: </b><?php echo htmlspecialchars($order['user_address']); ?><br>
                                                <b>Phone number: </b><?php echo htmlspecialchars($order['user_phone']); ?><br>
                                                <b>Email: </b> <?php echo htmlspecialchars($order['user_email']); ?>
                                            </address>
                                        </div>
                                        <div class="col-sm-4 invoice-col" >
                                            <br>
                                            <b>Order ID:</b> <?php echo htmlspecialchars($order['order_id']); ?><br>
                                            <b>Total:</b> <?php echo number_format($order['order_cost'], 3, '.', '.'); ?> VND<br>
                                            <b>Status:</b>
                                            <?php
                                            $statusClass = '';

                                            switch ($order['order_status']) {
                                                case 'confirmed':
                                                    $statusClass = 'bg-info'; // Xanh dương
                                                    break;
                                                case 'delivered':
                                                    $statusClass = 'bg-success'; // Xanh lá cây
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger'; // Đỏ
                                                    break;
                                                    
                                                default:
                                                    $statusClass = 'bg-warning'; // Vàng
                                                    break;
                                            }

                                            ?>
                                            <span class="badge <?php echo $statusClass; ?> p-2 text-uppercase">
                                                <?php echo htmlspecialchars($order['order_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p>No order details available.</p>
                            <?php } ?>
                        </div>

                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Size</th>
                                        <th width="100">Price</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($order_details && $order_details->num_rows > 0) {
                                        $subtotal = 0;
                                        while ($row = $order_details->fetch_assoc()) {
                                            $total = $row['product_price'] * $row['product_quantity']; // Giả định số lượng là 1
                                            $subtotal += $total;
                                            $query = "SELECT product_name, product_quantity, product_size, product_price FROM order_items WHERE order_id = ?";
                                            echo "<tr>
                                                    <td>" . htmlspecialchars($row['product_name']) . "</td>
                                                    <td>" . htmlspecialchars($row['product_quantity']) . "</td>
                                                    <td>";

                                            // Check product_size and display corresponding size
                                            switch ($row['product_size']) {
                                                case 1:
                                                    echo 'S'; // product_size = 1 => S
                                                    break;
                                                case 2:
                                                    echo 'M'; // product_size = 2 => M
                                                    break;
                                                case 3:
                                                    echo 'L'; // product_size = 3 => L
                                                    break;
                                                case 4:
                                                    echo 'XL'; // product_size = 4 => XL
                                                    break;
                                                default:
                                                    echo 'Pre Size'; // Default, if other value is set
                                                    break;
                                            }
                                            echo "<td>" . number_format($row['product_price'], 3, '.', '.') . "&nbsp;VND</td>
                                                        <td>" . number_format($total, 3, '.', '.') . "&nbsp;VND</td>
                                                      </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No products found.</td></tr>";
                                    }
                                    ?>

                                    <tr>
                                        <th colspan="4" class="text-right p-3">Subtotal:</th>
                                        <td><?php echo number_format($subtotal, 3, '.', '.'); ?> VND</td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Shipping:</th>
                                        <td>0.00 VND</td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Grand Total:</th>
                                        <td style="font-weight: bold;"><?php echo number_format($subtotal, 3, '.', '.') . "&nbsp;VND"; ?> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <form action="order_details.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <div class="mb-3">
                                    <?php
                                    // So sánh trạng thái order với strcasecmp không phân biệt chữ hoa chữ thường
                                    if (strcasecmp($order['order_status'], 'delivered') == 0 || strcasecmp($order['order_status'], 'cancelled') == 0) { ?>
                                        <input type="hidden" name="order_status" value="<?php echo $order['order_status']; ?>" />
                                        <input type="text" class="form-control" value="<?php echo ucfirst($order['order_status']); ?>" disabled />
                                    <?php } else { ?>
                                        <select name="order_status" id="order_status" class="form-control">
                                            <?php
                                            $status_options = ['Pending','pending', 'confirmed', 'delivered', 'cancelled'];
                                            foreach ($status_options as $status) {
                                                // Sử dụng strcasecmp để so sánh không phân biệt chữ hoa chữ thường
                                                $selected = (strcasecmp($order['order_status'], $status) == 0) ? 'selected' : '';

                                                if ((strcasecmp($order['order_status'], 'confirmed') == 0 && in_array($status, ['delivered', 'cancelled'])) ||
                                                    (strcasecmp($order['order_status'], 'pending') == 0 && in_array($status, ['confirmed', 'cancelled', 'delivered']))
                                                ) {
                                                    echo "<option value=\"$status\" $selected>" . ucfirst($status) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary" name="update_status"
                                        <?php echo (strcasecmp($order['order_status'], 'delivered') == 0 || strcasecmp($order['order_status'], 'cancelled') == 0) ? 'disabled' : ''; ?>
                                        style="display: block; margin: 0 auto;">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>



                    </div>
                </div>
    </section>
    <!-- /.content -->
</div>

<?php include('../admin/layouts/sidebar.php'); ?>