<?php include('particle-front/menu.php'); ?>

<?php
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];

    try {
        $orderCollection = $conn->selectCollection('orders');
        $order = $orderCollection->findOne(['_id' => stringToMongoId($orderID)]);

        if ($order) {
            // Get Food Details
            $foodID = $order['foodID'];
            $foodCollection = $conn->selectCollection('foods');
            $food = $foodCollection->findOne(['_id' => stringToMongoId($foodID)]);

            $foodTitle = $food ? $food['title'] : "Unknown Food";
            $image_name = $food ? $food['image_name'] : "";

            $quantity = $order['quantity'];
            $total = $order['total'];
            $order_date = $order['order_date'];
            $status = $order['status'];

        } else {
            header('location:' . SITEURL);
            exit();
        }
    } catch (Exception $e) {
        header('location:' . SITEURL);
        exit();
    }
} else {
    header('location:' . SITEURL);
    exit();
}
?>

<section class="order-section">
    <div class="container">
        <div class="receipt-card">
            <div class="success-icon">
                <span class="check-mark">✓</span>
            </div>

            <h2 class="text-center order-heading" style="margin-bottom: 10px;">Order Confirmed!</h2>
            <p class="text-center" style="color: #666; margin-bottom: 30px;">Thank you for your order.</p>

            <div class="receipt-details">
                <div class="receipt-item">
                    <span class="label">Order ID:</span>
                    <span class="value">#
                        <?php echo substr($orderID, -6); ?>
                    </span>
                </div>
                <div class="receipt-item">
                    <span class="label">Date:</span>
                    <span class="value">
                        <?php echo $order_date; ?>
                    </span>
                </div>
                <div class="receipt-item">
                    <span class="label">Status:</span>
                    <span class="value status-badge">
                        <?php echo $status; ?>
                    </span>
                </div>

                <div class="divider"></div>

                <div class="receipt-food-info">
                    <?php if ($image_name): ?>
                        <div class="receipt-img">
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Food">
                        </div>
                    <?php endif; ?>
                    <div class="food-name">
                        <h4>
                            <?php echo $foodTitle; ?>
                        </h4>
                        <p>Qty:
                            <?php echo $quantity; ?>
                        </p>
                    </div>
                    <div class="food-price-total">
                        $
                        <?php echo number_format($total, 2); ?>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="receipt-total">
                    <span class="label">Total Paid:</span>
                    <span class="value">$
                        <?php echo number_format($total, 2); ?>
                    </span>
                </div>
            </div>

            <div class="receipt-actions">
                <a href="<?php echo SITEURL; ?>" class="btn-confirm"
                    style="display: block; text-align: center; text-decoration: none;">Continue Shopping</a>
            </div>
        </div>
    </div>
</section>

</body>

</html>