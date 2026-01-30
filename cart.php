<?php include('particle-front/menu.php'); ?>

<?php
// Handle specific cart actions
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header('location:' . SITEURL . 'cart.php');
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $val) {
        if ($val <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $val;
        }
    }
    header('location:' . SITEURL . 'cart.php');
}
?>

<section class="menu-section">
    <div class="container">

        <h2 class="text-center"
            style="margin-bottom: 40px; font-family: var(--font-heading); color: var(--text-green);">Your Cart</h2>

        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>

            <form action="" method="POST">
                <table class="tbl-full" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                            <th style="padding: 15px;">Product</th>
                            <th style="padding: 15px;">Price</th>
                            <th style="padding: 15px;">Quantity</th>
                            <th style="padding: 15px;">Total</th>
                            <th style="padding: 15px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        $foodCollection = $conn->selectCollection('foods');

                        foreach ($_SESSION['cart'] as $foodID => $qty):
                            try {
                                $food = $foodCollection->findOne(['_id' => stringToMongoId($foodID)]);
                                if ($food):
                                    $title = $food['title'];
                                    $price = $food['price'];
                                    $image_name = $food['image_name'];
                                    $sub_total = $price * $qty;
                                    $grand_total += $sub_total;
                                    ?>
                                    <tr style="border-bottom: 1px dashed var(--border-color);">
                                        <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                                            <?php if ($image_name): ?>
                                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>"
                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                            <?php endif; ?>
                                            <span style="font-weight: 500; font-family: var(--font-heading);">
                                                <?php echo $title; ?>
                                            </span>
                                        </td>
                                        <td style="padding: 15px;">$
                                            <?php echo $price; ?>
                                        </td>
                                        <td style="padding: 15px;">
                                            <input type="number" name="qty[<?php echo $foodID; ?>]" value="<?php echo $qty; ?>" min="1"
                                                style="width: 60px; padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                                        </td>
                                        <td style="padding: 15px; font-weight: bold;">$
                                            <?php echo number_format($sub_total, 2); ?>
                                        </td>
                                        <td style="padding: 15px;">
                                            <a href="?remove=<?php echo $foodID; ?>"
                                                style="color: #e74c3c; font-size: 0.9em;">Remove</a>
                                        </td>
                                    </tr>
                                <?php
                                endif;
                            } catch (Exception $e) {
                                continue;
                            }
                        endforeach;
                        ?>
                    </tbody>
                </table>

                <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                    <button type="submit" name="update_cart" class="btn-primary"
                        style="background: transparent; border: 1px solid var(--text-green); color: var(--text-green); padding: 10px 20px;">Update
                        Cart</button>

                    <div style="text-align: right;">
                        <h3 style="margin-bottom: 15px; font-family: var(--font-heading);">Total: $
                            <?php echo number_format($grand_total, 2); ?>
                        </h3>
                        <?php
                        if (isset($_SESSION['login-status'])) {
                            echo '<a href="' . SITEURL . 'checkout.php" class="btn-confirm" style="display: inline-block; text-decoration: none;">Proceed to Checkout</a>';
                        } else {
                            echo '<a href="' . SITEURL . 'login.php" class="btn-confirm" style="display: inline-block; text-decoration: none;">Login to Checkout</a>';
                        }
                        ?>
                    </div>
                </div>
            </form>

        <?php else: ?>
            <div class="text-center" style="padding: 50px;">
                <p>Your cart is empty.</p>
                <a href="<?php echo SITEURL; ?>" class="btn-primary"
                    style="margin-top: 20px; display: inline-block; background: var(--text-green); color: white; padding: 10px 20px;">Browse
                    Menu</a>
            </div>
        <?php endif; ?>

    </div>
</section>

</body>

</html>