<?php
include('../server/connection.php');
if (isset($_POST['create_user'])) {

    $name = $_POST['user_name'];
    $full_name = $_POST['full_name'];
    $email = $_POST['user_email'];
    $password = md5($_POST['user_password']);
    $phone = $_POST['user_phone'];
    $address = $_POST['user_address'];
    $dob = $_POST['user_dob'];
    $gender = $_POST['user_gender'];
    $is_locked = 0; 

    $stmt = $conn->prepare("INSERT INTO users(user_name, full_name, user_email, user_password, user_phone, user_address, user_dob, user_gender, is_locked) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $name, $full_name, $email, $password, $phone, $address, $dob, $gender, $is_locked);

    if ($stmt->execute()) {
        header('Location: list_users.php?message=User added successfully');
    } else {
        header('Location: list_users.php?error=Error adding user');
    }
}
?>
<?php include('../admin/layouts/app.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_users.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <form action="create_user.php" method="POST">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="user_name">Username</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" required>
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="user_email">Email</label>
                                <input type="email" name="user_email" id="user_email" class="form-control" required>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="user_password">Password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control" required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="user_phone">Phone</label>
                                <input type="text" name="user_phone" id="user_phone" class="form-control" required>
                            </div>

                            <!-- Address -->
                            <div class="col-md-6 mb-3">
                                <label for="user_address">Address</label>
                                <input type="text" name="user_address" id="user_address" class="form-control" required>
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="user_dob">Date of Birth</label>
                                <input type="date" name="user_dob" id="user_dob" class="form-control">
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6 mb-3">
                                <label for="user_gender">Gender</label>
                                <select name="user_gender" id="user_gender" class="form-control">
                                    <option value="">Select gender</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" name="create_user">Create</button>
                    <a href="list_users.php" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
    </section>
</div>
<?php include('../admin/layouts/sidebar.php'); ?>
