<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "php_project");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['user_email'];

    // Truy vấn tìm kiếm email trong cơ sở dữ liệu
    $sql = "SELECT * FROM users WHERE user_email = ?";

    // Kiểm tra nếu câu lệnh SQL chuẩn bị thành công
    if ($stmt = $conn->prepare($sql)) {
        // Ràng buộc tham số và thực thi câu lệnh
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra xem email có tồn tại không
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Tạo một mã reset mật khẩu ngẫu nhiên
            $reset_code = bin2hex(random_bytes(16)); // Tạo mã reset mật khẩu
            $expire_time = date("Y-m-d H:i:s", strtotime("+1 hour")); // Mã reset có thời hạn 1 giờ

            // Lưu mã reset vào cơ sở dữ liệu
            $update_sql = "UPDATE users SET reset_code = ?, reset_expiry = ? WHERE user_email = ?";

            // Kiểm tra nếu câu lệnh SQL chuẩn bị thành công
            if ($update_stmt = $conn->prepare($update_sql)) {
                $update_stmt->bind_param("sss", $reset_code, $expire_time, $user_email);
                $update_stmt->execute();

                // Gửi email với mã reset mật khẩu
                $reset_link = "http://localhost/fashion_store/reset_password.php?code=" . $reset_code;
                $subject = "Yêu cầu đặt lại mật khẩu";
                $message = "Chào bạn,\n\nBạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấp vào liên kết dưới đây để thiết lập lại mật khẩu của bạn:\n\n" . $reset_link . "\n\nMã reset sẽ hết hạn sau 1 giờ.\n\nCảm ơn bạn!";
                $headers = "From: no-reply@yourdomain.com";

                // Gửi email
                if (mail($user_email, $subject, $message, $headers)) {
                    $_SESSION['status_message'] = "Mã reset mật khẩu đã được gửi đến email của bạn!";
                    header("Location: forgot_password.php"); // Điều hướng về trang này để hiển thị thông báo
                    exit;
                } else {
                    $_SESSION['status_message'] = "Đã có lỗi xảy ra trong khi gửi email!";
                    header("Location: forgot_password.php");
                    exit;
                }
            } else {
                die("Lỗi chuẩn bị câu lệnh cập nhật mã reset: " . $conn->error);
            }
        } else {
            $_SESSION['status_message'] = "Email không tồn tại trong hệ thống!";
            header("Location: forgot_password.php");
            exit;
        }
    } else {
        die("Lỗi chuẩn bị câu lệnh truy vấn: " . $conn->error);
    }
}
?>

<?php include('layouts/header.php') ?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="font-weight-bold text-uppercase">Quên mật khẩu</h2>
        <br class="mx-auto">
    </div>

    <div class="container text-center mt-3 pt-5">
        <div class="form-container d-flex justify-content-center">
            <form action="forgot_password.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="user_email" placeholder="Nhập email của bạn" required>
                </div>
                <button type="submit" class="btn">Gửi Mã Đặt Lại Mật Khẩu</button>
            </form>

            <?php if (isset($_SESSION['status_message'])): ?>
                <div class="alert alert-info mt-3">
                    <?php echo $_SESSION['status_message']; ?>
                    <?php unset($_SESSION['status_message']); ?>
                </div>
            <?php endif; ?>

            <div class="options">
                <a href="login.php">Đã nhớ mật khẩu? Đăng nhập</a>
            </div>
        </div>
    </div>

</section>

<?php include('layouts/footer.php') ?>

<style>
    .form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.input-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    width: 100%;
    max-width: 400px;
}
input[type="email"] {
    padding: 10px;
    font-size: 16px;
}
form {
    width: 100%;
    max-width: 400px;
}
.btn {
    margin-top: 10px;
}

</style>