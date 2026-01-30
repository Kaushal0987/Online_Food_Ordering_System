<?php include('particle-front/menu.php'); ?>

<?php
if (!isset($_SESSION['uID'])) {
    header('location:' . SITEURL . 'login.php');
    exit();
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('location:' . SITEURL . 'cart.php');
    exit();
}

// Get User Details
$uID = $_SESSION['uID'];
$userCollection = $conn->selectCollection('users');
$user = $userCollection->findOne(['_id' => stringToMongoId($uID)]);

if ($user) {
    $username = $user['username'];
    $email = $user['email'];
    $address = $user['address'];
}
?>

<section class="order-section">
    <div class="container">
        <h2 class="text-center order-heading">Checkout</h2>

        <form action="" method="POST" class="order-form" style="max-width: 800px;">
            <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                <!-- Order Summary -->
                <div style="flex: 1; min-width: 300px;">
                    <h3 style="margin-bottom: 20px; font-family: var(--font-heading);">Order Summary</h3>
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 12px;">
                        <?php
                        $grand_total = 0;
                        $foodCollection = $conn->selectCollection('foods');
                        foreach ($_SESSION['cart'] as $id => $qty):
                            $food = $foodCollection->findOne(['_id' => stringToMongoId($id)]);
                            if ($food):
                                $sub = $food['price'] * $qty;
                                $grand_total += $sub;
                                ?>
                                <div
                                    style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 10px;">
                                    <div>
                                        <div style="font-weight: 600;">
                                            <?php echo $food['title']; ?>
                                        </div>
                                        <div style="font-size: 0.9em; color: #666;">Qty:
                                            <?php echo $qty; ?>
                                        </div>
                                    </div>
                                    <div style="font-weight: bold;">$
                                        <?php echo number_format($sub, 2); ?>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>

                        <div
                            style="display: flex; justify-content: space-between; margin-top: 20px; font-size: 1.2em; font-weight: bold; color: var(--text-green);">
                            <span>Total</span>
                            <span>$
                                <?php echo number_format($grand_total, 2); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div style="flex: 1; min-width: 300px;">
                    <h3 style="margin-bottom: 20px; font-family: var(--font-heading);">Delivery Details</h3>

                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="full-name" value="<?php echo $username; ?>" class="input-field"
                            required>
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $email; ?>" class="input-field" required>
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <textarea name="address" rows="3" class="input-field"
                            required><?php echo $address; ?></textarea>
                    </div>

                    <input type="submit" name="checkout" value="Place Order" class="btn-confirm">
                </div>
            </div>
        </form>
    </div>
</section>

<?php
if (isset($_POST['checkout'])) {
    try {
        $order_date = date("Y-m-d h:i:sa");
        $status = "Ordered";
        $orderCollection = $conn->selectCollection('orders');
        $success = true;

        foreach ($_SESSION['cart'] as $foodID => $qty) {
            $food = $foodCollection->findOne(['_id' => stringToMongoId($foodID)]);
            if ($food) {
                $total = $food['price'] * $qty;
                $result = $orderCollection->insertOne([
                    'foodID' => $foodID,
                    'quantity' => (int) $qty,
                    'total' => $total,
                    'order_date' => $order_date,
                    'status' => $status,
                    'uID' => $uID
                ]);

                if ($result->getInsertedCount() == 0)
                    $success = false;
            }
        }

        if ($success) {
            unset($_SESSION['cart']);
            // Redirect to My Orders
            echo "<script>window.location.href='" . SITEURL . "my-orders.php';</script>";
        } else {
            echo "<div class='error text-center'>Some items failed to order.</div>";
        }

    } catch (Exception $e) {
        echo "<div class='error text-center'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

</body>

</html>