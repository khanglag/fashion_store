<?php
include('server/connection.php');
session_start();

// Kiểm tra đăng xuất
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
        exit();
    }
}

// Lấy thông tin người dùng hiện tại
$user_name = '';
$user_email = '';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT user_name, user_email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($user_name, $user_email);
    $stmt->fetch();
    $stmt->close();
}

// Xử lý cập nhật tài khoản
if (isset($_POST['update_account'])) {
    $new_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        header('location:account.php?error=Invalid email format');
        exit();
    }

    // Cập nhật tên và email
    $stmt = $conn->prepare('UPDATE users SET user_name = ?, user_email = ? WHERE user_id = ?');
    $stmt->bind_param('ssi', $new_user_name, $new_email, $_SESSION['user_id']);
    if (!$stmt->execute()) {
        header('location:account.php?error=Failed to update account details');
        exit();
    }

    // Nếu có đổi mật khẩu
    if (!empty($old_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            header('location:account.php?error=Passwords do not match');
            exit();
        } elseif (strlen($new_password) < 6) {
            header('location:account.php?error=Password too short');
            exit();
        } else {
            // Kiểm tra mật khẩu cũ
            $stmt = $conn->prepare('SELECT user_password FROM users WHERE user_id = ?');
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($hashed_old_password);
            $stmt->fetch();
            $stmt->close();

            if (md5($old_password) !== $hashed_old_password) {
                header('location:account.php?error=Old password is incorrect');
                exit();
            }

            // Cập nhật mật khẩu mới
            $hashed_new_password = md5($new_password); // Gợi ý: thay md5 bằng password_hash nếu có thời gian nâng cấp

            $stmt = $conn->prepare('UPDATE users SET user_password = ? WHERE user_id = ?');
            $stmt->bind_param('si', $hashed_new_password, $_SESSION['user_id']);
            if ($stmt->execute()) {
                header('location:account.php?message=Account updated successfully');
                exit();
            } else {
                header('location:account.php?error=Failed to update password');
                exit();
            }
        }
    } else {
        header('location:account.php?message=Account updated successfully');
        exit();
    }
}
?>

<?php include('layouts/header.php'); ?>

<section class="my-5 py-5">
    <div class="container">
        <div class="row">
            <div class="account-update col-lg-8 col-md-10 col-sm-12 mx-auto">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Account</li>
                    </ol>
                </nav>

                <h3 class="font-weight-bold text-center text-uppercase">My Account</h3>

                <!-- Account Menu -->
                <ul id="account-panel" class="nav nav-pills justify-content-center mb-4">
                    <li class="nav-item">
                        <a href="my_profile.php" class="nav-link font-weight-bold">
                            <i class="fas fa-user-alt"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="my_orders.php" class="nav-link font-weight-bold">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="account.php?logout=1" class="nav-link font-weight-bold">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>

                <!-- Form -->
                <div class="account-update-form">
                    <form id="account-update" action="account.php" method="POST">
                        <?php if (isset($_GET['message'])): ?>
                        <div class="alert alert-success"><?php echo $_GET['message']; ?></div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="user_name">Username</label>
                            <input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <input type="password" id="old_password" name="old_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="update_account" class="btn btn-primary w-100">Update Account</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
