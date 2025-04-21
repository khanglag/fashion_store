<?php
session_start();
require_once 'server/connection.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in'])) {
    header('location:login.php');
    exit();
}

// Lấy dữ liệu từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}

// Cập nhật dữ liệu khi người dùng chỉnh sửa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    $update_query = "UPDATE users SET user_name = ?, full_name = ?, user_email = ?, user_phone = ?, user_address = ?, user_gender = ?, user_dob = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssi", $user_name, $full_name, $email, $phone, $address, $gender, $dob, $user_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Thông tin đã được cập nhật thành công!";
        $_SESSION['user_name'] = $user_name;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_address'] = $address;
        $_SESSION['user_gender'] = $gender;
        $_SESSION['user_dob'] = $dob;
        header("Location: my_profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Cập nhật thông tin thất bại!";
    }
}
?>

<?php include('layouts/header.php'); ?>

<style>
/* CSS giữ nguyên từ giao diện cũ */
.breadcrumb {
    background-color: #f8f9fa;
    padding: 10px 10px;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 12px;
    list-style: none;
    display: flex;
    gap: 1px;
    align-items: center;
}

.breadcrumb-item {
    color: #6c757d;
    font-style: italic;
}

.breadcrumb-item a {
    color: #000;
    text-decoration: none;
    transition: color 0.3s ease;
    font-style: italic;
}

.breadcrumb-item a:hover {
    color: #0056b3;
    text-decoration: underline;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    padding: 0 8px;
}

.breadcrumb-item.active {
    color: #212529;
    font-style: normal;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style>

<!-- Account Page -->
<section class="my-5 py-5">
    <div class="container">
        <div class="row">
            <div class="account-update col-lg-8 col-md-10 col-sm-12 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile
                    </ol>
                </nav>

                <h3 class="font-weight-bold text-center text-uppercase">My Profile</h3>

                <!-- Account Menu -->
                <ul id="account-panel" class="nav nav-pills justify-content-center mb-4">
                    <li class="nav-item">
                        <a href="account.php" class="nav-link font-weight-bold" role="tab">
                            <i class="fas fa-user-alt"></i> My Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="my_orders.php" class="nav-link font-weight-bold" role="tab">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="account.php?logout=1" class="nav-link font-weight-bold" role="tab">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>

                <!-- Account Update Form -->
                <div class="account-update-form">
                <div class="row">
            <div class="account-update col-lg-6 col-md-8 col-sm-10 mx-auto">
                
                <form action="my_profile.php" method="POST">
                    <div class="form-group">
                        <label for="full_name">Họ và tên</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['user_phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($user['user_address']); ?></textarea>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; margin-bottom: 20px;">
                        <label for="gender" class="col-form-label" style="flex: 0 0 25%; font-weight: bold;">Giới tính</label>
                        <div style="flex: 1; display: flex; align-items: center; gap: 20px;">
                            <?php
                            $genders = ['Nam', 'Nữ', 'Khác'];
                            foreach ($genders as $gender) {
                                $id = 'gender_' . strtolower($gender);
                                $isChecked = ($user['user_gender'] == $gender) ? 'checked' : '';
                                echo '
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="gender" id="'.$id.'" value="'.$gender.'" '.$isChecked.' 
                                        style="width: 16px; height: 16px; cursor: pointer;">
                                    <label for="'.$id.'" style="cursor: pointer;">'.$gender.'</label>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dob">Ngày sinh</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['user_dob']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('layouts/footer.php') ?>
