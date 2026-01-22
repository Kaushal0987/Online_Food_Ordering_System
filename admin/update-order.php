<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Order</h1>
        <br><br>


        <?php 
        
            //CHeck whether id is set or not
            if(isset($_GET['id']))
            {
                try {
                    //Get the Order Details
                    $id = $_GET['id'];

                    //Get all other details based on this id from MongoDB
                    $collection = $conn->selectCollection('orders');
                    $order = $collection->findOne(['_id' => stringToMongoId($id)]);

                    if($order)
                    {
                        //Detail Available
                        $foodID = $order['foodID'];
                        $qty = $order['quantity'];
                        $status = $order['status'];
                        $userID = $order['uID'];
                    }
                    else
                    {
                        //Detail not Available
                        //Redirect to Manage Order
                        header('location:'.SITEURL.'admin/manage-order.php');
                        exit();
                    }
                } catch (Exception $e) {
                    $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-order.php');
                    exit();
                }
            }
            else
            {
                //Redirect to Manage Order Page
                header('location:'.SITEURL.'admin/manage-order.php');
                exit();
            }
        
        ?>

        <form action="" method="POST">
        
            <table class="tbl-30">
                <tr>
                    <td>Food ID</td>
                    <td>
                        <input type="text" name="foodID" value="<?php echo $foodID; ?>" readonly>
                    </td>
                </tr>

                <tr>
                    <td>Qty</td>
                    <td>
                        <input type="number" name="qty" value="<?php echo $qty; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status">
                            <option <?php if($status=="Ordered"){echo "selected";} ?> value="Ordered">Ordered</option>
                            <option <?php if($status=="On Delivery"){echo "selected";} ?> value="On Delivery">On Delivery</option>
                            <option <?php if($status=="Delivered"){echo "selected";} ?> value="Delivered">Delivered</option>
                            <option <?php if($status=="Cancelled"){echo "selected";} ?> value="Cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>User ID: </td>
                    <td>
                        <input type="text" name="userID" value="<?php echo $userID; ?>">
                    </td>
                </tr>

                <tr>
                    <td clospan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <input type="submit" name="submit" value="Update Order" class="btn-secondary">
                    </td>
                </tr>
            </table>
        
        </form>


        <?php 
            //CHeck whether Update Button is Clicked or Not
            if(isset($_POST['submit']))
            {
                try {
                    //Get All the Values from Form
                    $id = $_POST['id'];
                    $foodID = $_POST['foodID'];
                    $qty = (int)$_POST['qty'];
                    $status = $_POST['status'];
                    $userID = $_POST['userID'];

                    //Update the Values in MongoDB
                    $collection = $conn->selectCollection('orders');
                    $result = $collection->updateOne(
                        ['_id' => stringToMongoId($id)],
                        ['$set' => [
                            'foodID' => $foodID,
                            'quantity' => $qty,
                            'status' => $status,
                            'uID' => $userID
                        ]]
                    );

                    //Check whether update or not
                    //And Redirect to Manage Order with Message
                    if($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0)
                    {
                        //Updated
                        $_SESSION['update'] = "<div class='success'>Order Updated Successfully.</div>";
                        header('location:'.SITEURL.'admin/manage-order.php');
                    }
                    else
                    {
                        //No changes or Failed to Update
                        $_SESSION['update'] = "<div class='error'>No changes made to Order.</div>";
                        header('location:'.SITEURL.'admin/manage-order.php');
                    }
                } catch (Exception $e) {
                    $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-order.php');
                }
            }
        ?>


    </div>
</div>