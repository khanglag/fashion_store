<?php
include('../server/connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $stmt = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $users = $stmt->get_result();
} else if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $full_name = $_POST['full_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_phone = $_POST['user_phone'];
    $user_address = $_POST['user_address'];
    $user_dob = $_POST['user_dob'];
    $user_gender = $_POST['user_gender'];
    $is_locked = $_POST['is_locked'];

    $stmt1 = $conn->prepare('UPDATE users SET user_name = ?, full_name = ?, user_email = ?, user_password = ?, user_phone = ?, user_address = ?, user_dob = ?, user_gender = ?, is_locked = ? WHERE user_id = ?');
    $stmt1->bind_param('ssssssssii', $user_name, $full_name, $user_email, $user_password, $user_phone, $user_address, $user_dob, $user_gender, $is_locked, $user_id);

    if ($stmt1->execute()) {
        header('location:list_users.php?message=User updated successfully');
    } else {
        header('location:list_users.php?error=Error updating user');
    }
}
?>

<?php include('../admin/layouts/app.php') ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cập nhật người dùng</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_users.php" class="btn btn-primary">Quay lại</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <form action="edit_user.php" method="POST">
            <div class="container-fluid">
                <div class="card">
                    <?php foreach ($users as $user) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id'] ?>">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="user_name">Tên đăng nhập</label>
                                    <input type="text" name="user_name" id="user_name" class="form-control"
                                        value="<?php echo $user['user_name'] ?>" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="full_name">Tên đầy đủ</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control"
                                        value="<?php echo $user['full_name'] ?>" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="user_email">Email</label>
                                    <input type="email" name="user_email" id="user_email" class="form-control"
                                        value="<?php echo $user['user_email'] ?>" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="user_password">Mật khẩu</label>
                                    <input type="password" name="user_password" id="user_password" class="form-control"
                                        value="<?php echo $user['user_password'] ?>" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="user_phone">Số điện thoại</label>
                                    <input type="text" name="user_phone" id="user_phone" class="form-control"
                                        value="<?php echo $user['user_phone'] ?>" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="user_address">Địa chỉ</label>
                                    <input type="text" name="user_address" id="user_address" class="form-control"
                                        value="<?php echo $user['user_address'] ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="user_dob">Ngày sinh</label>
                                    <input type="date" name="user_dob" id="user_dob" class="form-control"
                                        value="<?php echo $user['user_dob'] ?>">
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="user_gender">Giới tính</label>
                                    <select name="user_gender" id="user_gender" class="form-control">
                                        <option value="Nam" <?php if ($user['user_gender'] == 'Nam') echo 'selected'; ?>>Nam</option>
                                        <option value="Nữ" <?php if ($user['user_gender'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                                        <option value="Khác" <?php if ($user['user_gender'] == 'Khác') echo 'selected'; ?>>Khác</option>
                                    </select>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="is_locked">Trạng thái tài khoản</label>
                                    <select name="is_locked" id="is_locked" class="form-control">
                                        <option value="0" <?php if ($user['is_locked'] == 0) echo 'selected'; ?>>Mở khóa</option>
                                        <option value="1" <?php if ($user['is_locked'] == 1) echo 'selected'; ?>>Khóa</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" name="update_user">Cập nhật</button>
                    <a href="list_users.php" class="btn btn-outline-dark ml-3">Hủy</a>
                </div>
            </div>
        </form>

    </section>

</div>
<?php include('../admin/layouts/sidebar.php'); ?>