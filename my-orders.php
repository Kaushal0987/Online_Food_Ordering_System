<?php include('particle-front/menu.php'); ?>

<?php
if (!isset($_SESSION['uID'])) {
    header('location:' . SITEURL . 'login.php');
    exit();
}
$uID = $_SESSION['uID'];
?>

<section class="menu-section">
    <div class="container">
        <h2 class="text-center"
            style="margin-bottom: 40px; font-family: var(--font-heading); color: var(--text-green);">My Orders</h2>

        <table class="tbl-full"
            style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            <thead style="background: var(--text-green); color: white;">
                <tr>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Food Item</th>
                    <th style="padding: 15px;">Qty</th>
                    <th style="padding: 15px;">Total</th>
                    <th style="padding: 15px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $orderCollection = $conn->selectCollection('orders');
                    $foodCollection = $conn->selectCollection('foods');
                    // Find orders for this user, sort by date desc
                    $cursor = $orderCollection->find(['uID' => $uID], ['sort' => ['order_date' => -1]]);
                    $orders = iterator_to_array($cursor);

                    if (count($orders) > 0) {
                        foreach ($orders as $order) {
                            $foodID = $order['foodID'];
                            $food = $foodCollection->findOne(['_id' => stringToMongoId($foodID)]);
                            $foodName = $food ? $food['title'] : "Unknown Item";
                            ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;">
                                    <?php echo $order['order_date']; ?>
                                </td>
                                <td style="padding: 15px; font-weight: 500; font-family: var(--font-heading);">
                                    <?php echo $foodName; ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php echo $order['quantity']; ?>
                                </td>
                                <td style="padding: 15px;">$
                                    <?php echo number_format($order['total'], 2); ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php
                                    $status = $order['status'];
                                    $color = "#333";
                                    if ($status == "Delivered")
                                        $color = "green";
                                    elseif ($status == "Cancelled")
                                        $color = "red";
                                    elseif ($status == "On Delivery")
                                        $color = "orange";

                                    echo "<span style='color:$color; font-weight:bold;'>$status</span>";
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center' style='padding: 30px;'>No orders found.</td></tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='5' class='error'>Error loading orders.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

</body>

</html>