<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Order</h1>

                <br /><br /><br />

                <?php 
                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                ?>
                <br><br>

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Food ID</th>
                        <th>Qty.</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>User ID</th>
                        <th>Actions</th>
                    </tr>

                    <?php 
                        try {
                            //Get all the orders from MongoDB
                            $collection = $conn->selectCollection('orders');
                            
                            //Find all orders sorted by newest first
                            $cursor = $collection->find(
                                [],
                                ['sort' => ['order_date' => -1]]
                            );
                            
                            //Get orders as array
                            $orders = iterator_to_array($cursor);
                            $count = count($orders);

                            $sn = 1; //Create a Serial Number and set its initial value as 1

                            if($count > 0)
                            {
                                //Order Available
                                foreach($orders as $order)
                                {
                                    //Get all the order details
                                    $id = mongoIdToString($order['_id']);
                                    $foodID = $order['foodID'];
                                    $qty = $order['quantity'];
                                    $total = $order['total'];
                                    $order_date = $order['order_date'];
                                    $status = $order['status'];
                                    $userID = $order['uID'];
                                    
                                    ?>

                                        <tr>
                                            <td><?php echo $sn++; ?>. </td>
                                            <td><?php echo $foodID; ?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td><?php echo $total; ?></td>
                                            <td><?php echo $order_date; ?></td>

                                            <td>
                                                <?php 
                                                    // Ordered, On Delivery, Delivered, Cancelled

                                                    if($status=="Ordered")
                                                    {
                                                        echo "<label>$status</label>";
                                                    }
                                                    elseif($status=="On Delivery")
                                                    {
                                                        echo "<label style='color: orange;'>$status</label>";
                                                    }
                                                    elseif($status=="Delivered")
                                                    {
                                                        echo "<label style='color: green;'>$status</label>";
                                                    }
                                                    elseif($status=="Cancelled")
                                                    {
                                                        echo "<label style='color: red;'>$status</label>";
                                                    }
                                                ?>
                                            </td>

                                            <td><?php echo $userID; ?></td>
                                            <td>
                                                <a href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>" class="btn-secondary">Update Order</a>
                                            </td>
                                        </tr>

                                    <?php

                                }
                            }
                            else
                            {
                                //Order not Available
                                echo "<tr><td colspan='8' class='error'>Orders not Available</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='8' class='error'>Error: " . $e->getMessage() . "</td></tr>";
                        }
                    ?>

 
                </table>
    </div>
    
</div>