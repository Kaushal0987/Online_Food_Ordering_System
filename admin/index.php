<?php include('partials/menu.php'); ?>

<!-- Main Content Section Starts -->
<div class="main-content">
    <div class="wrapper">

        <div class="welcome-panel">
            <h1>Welcome Back, <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'Admin'; ?>!
            </h1>
            <p>Get a quick overview of your food ordering system performance.</p>
            <div class="welcome-actions">
                <a href="manage-food.php" class="btn-upgrade">Manage Foods</a>
            </div>
        </div>

        <?php
        if (isset($_SESSION['login'])) {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>

        <h1>Dashboard Overview</h1>

        <div class="stats-grid">

            <div class="stats-card purple">
                <?php
                try {
                    $collection = $conn->selectCollection('users');
                    $count1 = $collection->countDocuments();
                } catch (Exception $e) {
                    $count1 = 0;
                }
                ?>
                <div>
                    <h2><?php echo $count1; ?></h2>
                    <span>Total Users</span>
                </div>
                <i class="fas fa-users"></i>
            </div>

            <div class="stats-card blue">
                <?php
                try {
                    $collection = $conn->selectCollection('foods');
                    $count2 = $collection->countDocuments();
                } catch (Exception $e) {
                    $count2 = 0;
                }
                ?>
                <div>
                    <h2><?php echo $count2; ?></h2>
                    <span>Food Items</span>
                </div>
                <i class="fas fa-utensils"></i>
            </div>

            <div class="stats-card orange">
                <?php
                try {
                    $collection = $conn->selectCollection('orders');
                    $count3 = $collection->countDocuments();
                } catch (Exception $e) {
                    $count3 = 0;
                }
                ?>
                <div>
                    <h2><?php echo $count3; ?></h2>
                    <span>Total Orders</span>
                </div>
                <i class="fas fa-shopping-cart"></i>
            </div>

            <div class="stats-card red">
                <?php
                try {
                    $collection = $conn->selectCollection('orders');
                    $pipeline = [
                        ['$match' => ['status' => 'Delivered']],
                        ['$group' => ['_id' => null, 'Total' => ['$sum' => '$total']]]
                    ];
                    $result = iterator_to_array($collection->aggregate($pipeline));
                    $total_revenue = isset($result[0]['Total']) ? $result[0]['Total'] : 0;
                } catch (Exception $e) {
                    $total_revenue = 0;
                }
                ?>
                <div>
                    <h2>$<?php echo number_format($total_revenue, 2); ?></h2>
                    <span>Total Revenue</span>
                </div>
                <i class="fas fa-dollar-sign"></i>
            </div>

        </div>


        <h1>Recent Orders</h1>

        <?php
        // Handle Status Update
        if (isset($_POST['update_status'])) {
            try {
                $order_id = $_POST['order_id'];
                $new_status = $_POST['status'];

                $orderCollection = $conn->selectCollection('orders'); // Re-select explicitly
                $updateResult = $orderCollection->updateOne(
                    ['_id' => stringToMongoId($order_id)],
                    ['$set' => ['status' => $new_status]]
                );

                if ($updateResult->getModifiedCount() > 0) {
                    echo "<div class='success'>Order status updated successfully.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>Failed to update status.</div>";
            }
        }
        ?>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Date</th>
                <th>Food</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            try {
                $collection = $conn->selectCollection('orders');
                $cursor = $collection->find([], ['limit' => 5, 'sort' => ['order_date' => -1]]);
                $recent_orders = iterator_to_array($cursor);

                if (count($recent_orders) > 0) {
                    $sn = 1;
                    foreach ($recent_orders as $order) {
                        $id = mongoIdToString($order['_id']);
                        $status = isset($order['status']) ? $order['status'] : 'Ordered';

                        $foodID = isset($order['foodID']) ? $order['foodID'] : 'N/A';

                        echo "<tr>";
                        echo "<td>" . $sn++ . "</td>";
                        echo "<td>" . (isset($order['order_date']) ? $order['order_date'] : '') . "</td>";
                        echo "<td>" . $foodID . "</td>";
                        echo "<td>$" . (isset($order['total']) ? number_format($order['total'], 2) : '0.00') . "</td>";
                        ?>
                        <td>
                            <form action="" method="POST" style="display:flex; gap:5px;">
                                <input type="hidden" name="order_id" value="<?php echo $id; ?>">
                                <select name="status" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                                    <option value="Ordered" <?php if ($status == "Ordered")
                                        echo "selected"; ?>>Ordered</option>
                                    <option value="On Delivery" <?php if ($status == "On Delivery")
                                        echo "selected"; ?>>On Delivery
                                    </option>
                                    <option value="Delivered" <?php if ($status == "Delivered")
                                        echo "selected"; ?>>Delivered</option>
                                    <option value="Cancelled" <?php if ($status == "Cancelled")
                                        echo "selected"; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-secondary"
                                    style="padding: 5px 10px; font-size: 0.8rem; cursor:pointer;">✓</button>
                            </form>
                        </td>
                        <?php
                        echo "<td><a href='update-order.php?id=$id' class='btn-secondary' style='padding: 5px 15px; font-size: 0.8rem;'>View</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No recent orders found.</td></tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='6' class='error'>Error loading orders</td></tr>";
            }
            ?>
        </table>

    </div>
</div>
<!-- Main Content Section Ends -->
</body>

</html>