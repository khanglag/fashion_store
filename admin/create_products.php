<?php
include('../server/connection.php');

if (isset($_POST['create_product'])) {
    // Kiểm tra và xác thực dữ liệu đầu vào
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_status = $_POST['product_status'];
    $product_description = $_POST['product_description'];
    $product_price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
    $product_price_discount = filter_var($_POST['product_price_discount'], FILTER_VALIDATE_FLOAT);
    $product_color = $_POST['product_color'];
    $quantity = $_POST['quantity'];

    // Kiểm tra nếu giá trị của sản phẩm hợp lệ
    if ($product_price === null || $product_price < 0) {
        echo "Price is not valued";
        exit; // Dừng lại nếu giá không hợp lệ
    }
    if ($product_price_discount === null || $product_price_discount < 0) {
        echo "Price Discount is not valued";
        exit; // Dừng lại nếu giá không hợp lệ
    }
    // Xử lý upload hình ảnh
    $image_files = ['image', 'image2', 'image3', 'image4']; // Mảng lưu trữ các trường input để tải lên
    $image_names = []; // Lưu trữ tên các hình ảnh đã tải lên
    $max_size = 7 * 1024 * 1024; // Giới hạn kích thước file (7MB)

    foreach ($image_files as $key => $image) {
        if (isset($_FILES[$image]) && $_FILES[$image]['error'] === UPLOAD_ERR_OK) {
            // Lấy thông tin về file upload
            $file_tmp_name = $_FILES[$image]['tmp_name'];
            $file_name = $_FILES[$image]['name'];
            $file_size = $_FILES[$image]['size'];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);

            // Kiểm tra loại file (ví dụ chỉ cho phép upload hình ảnh)
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($extension), $allowed_extensions)) {
                die("Lỗi: Chỉ cho phép tải lên các tệp hình ảnh (jpg, jpeg, png, gif).");
            }

            // Kiểm tra kích thước file
            if ($file_size > $max_size) {
                die("Lỗi: Tệp quá lớn. Vui lòng chọn tệp nhỏ hơn 7MB.");
            }

            // Tạo tên file mới để tránh trùng lặp
            $image_name = $product_name . ($key + 1) . '.' . $extension; // Sử dụng tên sản phẩm nhập vào
            $image_path = "../assets/images/" . $image_name;

            // Di chuyển file tải lên vào thư mục đích
            if (move_uploaded_file($file_tmp_name, $image_path)) {
                $image_names[] = $image_name;
            } else {
                die("Lỗi tải lên tệp $file_name.");
            }
        } else {
            // Nếu không có hình ảnh, thêm giá trị NULL
            $image_names[] = null;
        }
    }

    // Kiểm tra mảng $image_names có đủ 4 phần tử
    if (count($image_names) < 4) {
        die("Error: Not enough image names.");
    }

    // Kết nối cơ sở dữ liệu MySQL
    $conn = new mysqli("localhost", "root", "", "php_project");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Chuẩn bị câu lệnh SQL
    $stmt = $conn->prepare('
        INSERT INTO products 
        (product_name, category_id, status_products_id, product_description, product_image, product_image2, product_image3, product_image4, product_price,product_price_discount, product_color, quantity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)
    ');

    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Ràng buộc tham số với kiểu dữ liệu phù hợp
    $stmt->bind_param(
        "siisssssddsi",  // Kiểu dữ liệu của các tham số: s = string, i = integer, d = double
        $product_name,     // product_name: chuỗi (string)
        $product_category, // category_id: số nguyên (integer)
        $product_status,
        $product_description, // product_description: chuỗi (string)
        $image_names[0],   // product_image: chuỗi (string)
        $image_names[1],   // product_image2: chuỗi (string)
        $image_names[2],   // product_image3: chuỗi (string)
        $image_names[3],   // product_image4: chuỗi (string)
        $product_price,    // product_price: số thực (double)
        $product_price_discount,
        $product_color,  // product_color: chuỗi (string)
        $quantity,
    );

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        header('Location: list_products.php?message=Product added successfully');
        exit; // Thêm exit sau khi redirect
    } else {
        header('Location: list_products.php?error=Error product failed');
        exit; // Thêm exit sau khi redirect
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>


<?php include('../admin/layouts/app.php') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_products.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <form action="create_products.php" method="POST" enctype="multipart/form-data">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" id="product_name"
                                                class="form-control" placeholder="Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_category">Product Category</label>
                                            <select name="product_category" id="product_category" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <?php
                                                // Fetch categories from the database
                                                $result = $conn->query('SELECT category_id, category_name FROM category');
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['category_id']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_status">Product Status</label>
                                            <select name="product_status" id="product_status" class="form-control" required>
                                                <option value="">Select Status</option>
                                                <?php
                                                // Fetch categories from the database
                                                $result = $conn->query('SELECT status_products_id, status_products_name FROM status_products');
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['status_products_id']) . '">' . htmlspecialchars($row['status_products_name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_description">Description</label>
                                            <textarea name="product_description" id="product_description" cols="98"
                                                rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php foreach (['image', 'image2', 'image3', 'image4'] as $imageField): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Upload <?php echo ucfirst($imageField); ?></h2>
                                    <img id="<?php echo $imageField; ?>_preview" style="display:none; width: 100px; height: auto;" />
                                    <br>
                                    <input type="file" name="<?php echo $imageField; ?>" id="<?php echo $imageField; ?>"
                                        class="form-control" onchange="previewImage(this, '<?php echo $imageField; ?>_preview')" required>
                                    <br>
                                    
                                </div>
                            </div>
                        <?php endforeach; ?>


                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product_price">Price</label>
                                    <input type="number" name="product_price" id="product_price" class="form-control"
                                        placeholder="Price" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product_price_discount">Price Discount</label>
                                    <input type="number" name="product_price_discount" id="product_price_discount" class="form-control"
                                        placeholder="Price Discount ( Optional )" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product_color">Product Color</label>
                                    <input type="text" name="product_color" id="product_color" class="form-control"
                                        placeholder="Product Color">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="quantity">Product Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                        placeholder="Product Quantity">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" name="create_product">Create</button>
                    <a href="list_products.php" class="btn btn-primary">Cancel</a>
                </div>
            </div>


        </form>

    </section>
</div>

<?php include('../admin/layouts/sidebar.php') ?>
<script>
    function previewImage(input, previewId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>