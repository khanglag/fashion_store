<?php
session_start();
include('../server/connection.php');

// Lấy các tham số lọc từ GET
$status_filter = isset($_GET['order_status']) ? $_GET['order_status'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$user_address = isset($_GET['user_address']) ? $_GET['user_address'] : '';

// Xây dựng phần điều kiện WHERE
$where_conditions = [];
$bindings = [];

if ($status_filter) {
    $where_conditions[] = "orders.order_status = ?";
    $bindings[] = $status_filter;
}

if ($start_date && $end_date) {
    $where_conditions[] = "orders.order_date BETWEEN ? AND ?";
    $bindings[] = $start_date . ' 00:00:00';
    $bindings[] = $end_date . ' 23:59:59';
} elseif ($start_date) {
    $where_conditions[] = "orders.order_date >= ?";
    $bindings[] = $start_date . ' 00:00:00';
} elseif ($end_date) {
    $where_conditions[] = "orders.order_date <= ?";
    $bindings[] = $end_date . ' 23:59:59';
}


if ($user_address) {
    $where_conditions[] = "orders.user_address LIKE ?";
    $bindings[] = "%" . $user_address . "%";
}

// Xây dựng câu truy vấn WHERE
$where_sql = '';
if (count($where_conditions) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
}

// Xác định số bản ghi trên mỗi trang
$limit = 12;

// Lấy số trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Tính vị trí bắt đầu
$offset = ($page - 1) * $limit;

// Lấy danh sách đơn hàng
$stmt = $conn->prepare('
    SELECT orders.*, users.user_name, users.user_email
    FROM orders 
    INNER JOIN users ON orders.user_id = users.user_id
    ' . $where_sql . '
    ORDER BY orders.order_id DESC
    LIMIT ?, ?
');

$types = str_repeat('s', count($bindings)) . 'ii';
$bind_values = [...$bindings, $offset, $limit];
$stmt->bind_param($types, ...$bind_values);
$stmt->execute();
$orders = $stmt->get_result();

// Đếm tổng đơn hàng để tính tổng số trang
$stmt_total = $conn->prepare('SELECT COUNT(*) AS total FROM orders ' . $where_sql);
if (count($bindings) > 0) {
    $types_total = str_repeat('s', count($bindings));
    $stmt_total->bind_param($types_total, ...$bindings);
}
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$total_orders = $total_row['total'];

// Tính tổng số trang
$total_pages = ceil($total_orders / $limit);
?>

<?php include('../admin/layouts/app.php') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <form action="list_orders.php" method="POST" onsubmit="return confirm('Are you sure you want to delete all orders? This action cannot be undone.');">
                        <button type="submit" class="btn btn-danger">Delete All Orders</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body table-responsive p-0">
                    <?php if (isset($_GET['message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($_GET['message']); ?>
                        </div>
                        <script>
                            history.replaceState(null, '', window.location.pathname);
                        </script>
                    <?php endif; ?>

                    <form method="GET" action="list_orders.php" class="p-3">
                        <div class="row g-3 align-items-center">
                            <!-- Lọc theo trạng thái -->
                            <div class="col-md-3">
                                <label for="order_status" class="form-label">Order Status</label>
                                <select class="form-control" name="order_status" id="order_status">
                                    <option value="">-- All --</option>
                                    <option value="pending" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="confirmed" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="delivered" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="cancelled" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>

                            <!-- Ngày bắt đầu -->
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">From Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
                            </div>

                            <!-- Ngày kết thúc -->
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">To Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
                            </div>

                            <!-- Địa chỉ giao hàng -->
                            <div class="col-md-3">
                                <label for="user_address" class="form-label">Delivery Location</label>
                                <input type="text" class="form-control" name="user_address" id="user_address" placeholder="District, City..." value="<?= $_GET['user_address'] ?? '' ?>">
                            </div>

                            <div class="col-md-12 mt-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                                <a href="list_orders.php?page=1" class="btn btn-secondary" style="margin-left: 16px;">Reset</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Order Cost</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <th>Order Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = $total_orders - (($page - 1) * $limit);
                            while ($order = $orders->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $stt--; ?></td>
                                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['user_phone']); ?></td>
                                    <td><?php echo number_format($order['order_cost'], 3, '.', '.'); ?></td>
                                    <td>
                                        <?php
                                        $status = $order['order_status'];
                                        $statusClass = match (strtolower($status)) {
                                            'pending' => 'bg-warning',
                                            'confirmed' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?> p-2 text-uppercase">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td>
                                        <form action="../admin/order_details.php" method="POST">
                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                            <input type="submit" name="order_details" style="background-color: coral; color: white; border-radius: 8px; padding: 8px 16px; border: none; cursor: pointer;" value="Details">
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <ul class="pagination m-0 float-right">
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>">«</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>">»</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</div>

<?php include('../admin/layouts/sidebar.php') ?>
