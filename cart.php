<?php
session_start();

function calculateTotalCart() {
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += (float)$item['product_price']  * (int)$item['product_quantity'];
        }
    }
    $_SESSION['total'] = $total;
}

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    // Nếu chưa đăng nhập => lưu sản phẩm và chuyển hướng login
    if (!isset($_SESSION['user_id'])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $product_array = array(
            'product_id' => $_POST['product_id'],
            'product_name' => htmlspecialchars($_POST['product_name']),
            'product_size' => $_POST['product_size'],
            'product_image' => htmlspecialchars($_POST['product_image']),
            'product_price' => (float)$_POST['product_price'],
            'product_quantity' => (int)$_POST['product_quantity']
        );

        $key = $_POST['product_id'] . '_' . $_POST['product_size'];
        $_SESSION['cart'][$key] = $product_array;
        calculateTotalCart();

        header("Location: login.php");
        exit();
    }

    // Đã đăng nhập thì xử lý bình thường
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_id = (int)$_POST['product_id'];
    $product_size = $_POST['product_size'];
    $key = $product_id . '_' . $product_size;

    // Kiểm tra xem sản phẩm đã có trong giỏ chưa
    if (!array_key_exists($key, $_SESSION['cart'])) {
        $product_array = array(
            'product_id' => $product_id,
            'product_name' => htmlspecialchars($_POST['product_name']),
            'product_size' => $product_size,
            'product_image' => htmlspecialchars($_POST['product_image']),
            'product_price' => (float)$_POST['product_price'],
            'product_quantity' => (int)$_POST['product_quantity']
        );
        $_SESSION['cart'][$key] = $product_array;
    } else {
        echo '<script>alert("Product already added to cart");</script>';
    }

    calculateTotalCart();
}

// Cập nhật số lượng
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $product_size = $_POST['product_size'];
    $key = $product_id . '_' . $product_size;
    $product_quantity = (int)$_POST['product_quantity'];

    if ($product_quantity > 0 && isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['product_quantity'] = $product_quantity;
    }
    calculateTotalCart();
}

// Xoá sản phẩm
if (isset($_POST['remove_product'])) {
    $product_id = (int)$_POST['product_id'];
    $product_size = $_POST['product_size'];
    $key = $product_id . '_' . $product_size;
    unset($_SESSION['cart'][$key]);
    calculateTotalCart();
}
?>

<?php include('layouts/header.php'); ?>

<section class="cart container my-5 pt-5">
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item active" aria-current="page">Your Cart</li>
            </ol>
        </nav>
        <h2 class="font-weight-bold">Your Cart</h2>
    </div>

    <table class="mt-3 pt-5">
        <tr>
            <th>Product</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Action</th>
            <th>Total</th>
        </tr>

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="./assets/images/<?php echo htmlspecialchars($item['product_image']); ?>" alt="">
                            <div>
                                <p class="pt-4"><?php echo htmlspecialchars($item['product_name']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="pt-4">
                            <?php
                            switch ($item['product_size']) {
                                case 1: echo "S"; break;
                                case 2: echo "M"; break;
                                case 3: echo "L"; break;
                                case 4: echo "XL"; break;
                                default: echo "Pre Size";
                            }
                            ?>
                        </p>
                    </td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="hidden" name="product_size" value="<?php echo $item['product_size']; ?>">
                            <input type="number" name="product_quantity" value="<?php echo $item['product_quantity']; ?>" min="1">
                            <button type="submit" name="update_quantity" class="remove-btn rounded-2">Update</button>
                        </form>
                    </td>
                    <td>
                        <p><?php echo number_format((float)$item['product_price'], 3, '.', '.') . ' VND'; ?></p>
                    </td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="hidden" name="product_size" value="<?php echo $item['product_size']; ?>">
                            <button type="submit" name="remove_product" class="remove-btn rounded-2">Remove</button>
                        </form>
                    </td>
                    <td>
                        <?php
                        $line_total = (float)$item['product_price'] * (int)$item['product_quantity'];
                        echo number_format($line_total, 3, '.', '.') . ' VND';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center fs-2 text-uppercase p-4">Your cart is empty</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-total" style="font-weight: bold;">
            <table>
                <tr>
                    <td>Total</td>
                    <td><?php echo number_format((float)$_SESSION['total'], 3, '.', '.') . ' VND'; ?></td>
                </tr>
            </table>
        </div>

        <div class="checkout-container">
            <form action="checkout.php" method="POST">
                <button class="checkout-btn rounded-2 text-uppercase text-white" name="check-out">
                    Process to Checkout
                </button>
            </form>
        </div>
    <?php endif; ?>
</section>

<?php include('layouts/footer.php'); ?>
