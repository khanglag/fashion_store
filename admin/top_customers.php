<?php
session_start();
include('../server/connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include('../admin/layouts/app.php');

$topCustomers = [];

if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to_old = $_GET['to_date'];

    $to = date("Y-m-d", strtotime($to_old . ' +1 day'));

    // Truy vấn 5 khách hàng có tổng đơn hàng lớn nhất
    $query = "
        SELECT users.user_id, users.user_name, users.full_name, users.user_email, SUM(orders.order_cost) AS total_spent
        FROM orders
        JOIN users ON users.user_id = orders.user_id
        WHERE orders.order_date BETWEEN '$from' AND '$to' AND orders.order_status='delivered'
        GROUP BY users.user_id
        ORDER BY total_spent DESC
        LIMIT 5
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];

        // Lấy danh sách đơn hàng của từng khách hàng
        $orderQuery = "
            SELECT order_id, order_cost, order_date 
            FROM orders 
            WHERE user_id = $userId AND orders.order_status='delivered'
            AND order_date BETWEEN '$from' AND '$to'
            ORDER BY order_date DESC
        ";

        $orderResult = mysqli_query($conn, $orderQuery);
        $orderList = [];
        while ($order = mysqli_fetch_assoc($orderResult)) {
            $orderList[] = $order;
        }

        $row['orders'] = $orderList;
        $topCustomers[] = $row;
    }
}elseif (!empty($_GET['from_date']) && empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to_old = date('Y-m-d');

    $to = date("Y-m-d", strtotime($to_old . ' +1 day'));
    
    // Truy vấn 5 khách hàng có tổng đơn hàng lớn nhất
    $query = "
        SELECT users.user_id, users.user_name, users.full_name, users.user_email, SUM(orders.order_cost) AS total_spent
        FROM orders
        JOIN users ON users.user_id = orders.user_id
        WHERE orders.order_date BETWEEN '$from' AND '$to' AND orders.order_status='delivered'
        GROUP BY users.user_id
        ORDER BY total_spent DESC
        LIMIT 5
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];

        // Lấy danh sách đơn hàng của từng khách hàng
        $orderQuery = "
            SELECT order_id, order_cost, order_date 
            FROM orders 
            WHERE user_id = $userId AND orders.order_status='delivered'
            AND orders.order_date BETWEEN '$from' AND '$to'
            ORDER BY order_date DESC
        ";

        $orderResult = mysqli_query($conn, $orderQuery);
        $orderList = [];
        while ($order = mysqli_fetch_assoc($orderResult)) {
            $orderList[] = $order;
        }

        $row['orders'] = $orderList;
        $topCustomers[] = $row;
    }
}elseif (empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $to_old = $_GET['to_date'];

    $to = date("Y-m-d", strtotime($to_old . ' +1 day'));

    // Truy vấn 5 khách hàng có tổng đơn hàng lớn nhất
    $query = "
        SELECT users.user_id, users.user_name, users.full_name, users.user_email, SUM(orders.order_cost) AS total_spent
        FROM orders
        JOIN users ON users.user_id = orders.user_id
        WHERE orders.order_date <= '$to' AND orders.order_status='delivered'
        GROUP BY users.user_id
        ORDER BY total_spent DESC
        LIMIT 5
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];

        // Lấy danh sách đơn hàng của từng khách hàng
        $orderQuery = "
            SELECT order_id, order_cost, order_date 
            FROM orders 
            WHERE user_id = $userId AND orders.order_status='delivered'
            AND order_date <= '$to'
            ORDER BY order_date DESC
        ";

        $orderResult = mysqli_query($conn, $orderQuery);
        $orderList = [];
        while ($order = mysqli_fetch_assoc($orderResult)) {
            $orderList[] = $order;
        }

        $row['orders'] = $orderList;
        $topCustomers[] = $row;
    }
}else{

    // Truy vấn 5 khách hàng có tổng đơn hàng lớn nhất
    $query = "
        SELECT users.user_id, users.user_name, users.full_name, users.user_email, SUM(orders.order_cost) AS total_spent
        FROM orders
        JOIN users ON users.user_id = orders.user_id
        WHERE orders.order_status='delivered'
        GROUP BY users.user_id
        ORDER BY total_spent DESC
        LIMIT 5
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];

        // Lấy danh sách đơn hàng của từng khách hàng
        $orderQuery = "
            SELECT order_id, order_cost, order_date 
            FROM orders 
            WHERE user_id = $userId AND orders.order_status='delivered'
            ORDER BY order_date DESC
        ";

        $orderResult = mysqli_query($conn, $orderQuery);
        $orderList = [];
        while ($order = mysqli_fetch_assoc($orderResult)) {
            $orderList[] = $order;
        }

        $row['orders'] = $orderList;
        $topCustomers[] = $row;
    }
}
?>

<!-- Content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Top Customers Report</h1>
    </section>

    <section class="content">
        <div class="container-fluid">

            <form method="GET" class="mb-2 d-flex flex-wrap align-items-end">
                <div class="form-group col-md-3 me-4">
                    <label for="from_date">From Date:</label>
                    <input type="date" name="from_date" id="from_date" class="form-control"
                        value="<?php echo htmlspecialchars($_GET['from_date'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3 me-4">
                    <label for="to_date">To Date:</label>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                        value="<?php echo htmlspecialchars($_GET['to_date'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary">Thống kê</button>
                </div>
            </form>

            <?php if (!empty($topCustomers)): ?>
                <div class="card">
                    <div class="card-header bg-dark text-white text-center">
                        <h5 class="mb-0">Customers Report</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>Đơn hàng</th>
                                    <th>Tổng mua</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php foreach ($topCustomers as $customer): ?>
                                    <tr>
                                        <td><?php echo $index++; ?></td>
                                        <td><?php echo htmlspecialchars($customer['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['user_email']); ?></td>
                                        <td>
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($customer['orders'] as $order): ?>
                                                    <li>
                                                        <form action="../admin/order_details.php" method="POST" style="display: inline;" id="form_<?php echo $order['order_id']; ?>">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                            <a href="#" onclick="document.getElementById('form_<?php echo $order['order_id']; ?>').submit(); return false;">
                                                                #<?php echo $order['order_id']; ?>
                                                            </a>
                                                        </form>
                                                        -
                                                        <?php echo number_format($order['order_cost'], 3, '.', '.'); ?> VND
                                                        (<?php echo date('d/m/Y', strtotime($order['order_date'])); ?>)
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td><strong><?php echo number_format($customer['total_spent'], 3, '.', '.'); ?> VND</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif (isset($_GET['from_date'], $_GET['to_date'])): ?>
                <div class="alert alert-warning">No data available for the selected time range!!!</div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include('../admin/layouts/sidebar.php'); ?>
