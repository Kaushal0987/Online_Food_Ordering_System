
<?php include('particle-front/menu.php'); ?>

    <?php 
        //CHeck whether food id is set or not
        if(isset($_GET['foodID']) && isset($_GET['uID']))
        {
            try {
                //Get the Food id and details of the selected food
                $foodID = $_GET['foodID'];
                
                //Get the Details of the Selected Food from MongoDB
                $foodCollection = $conn->selectCollection('foods');
                $food = $foodCollection->findOne(['_id' => stringToMongoId($foodID)]);
                
                //Check whether the data is available or not
                if($food)
                {
                    //WE Have Data
                    $title = $food['title'];
                    $price = $food['price'];
                    $image_name = $food['image_name'];
                }
                else
                {
                    //Food not Available
                    //Redirect to Home Page
                    header('location:'.SITEURL);
                    exit();
                }

                //Get the User id and details of the selected User
                $uID = $_GET['uID'];
                
                //Get the Details of the Selected User from MongoDB
                $userCollection = $conn->selectCollection('users');
                $user = $userCollection->findOne(['_id' => stringToMongoId($uID)]);
                
                //Check whether the data is available or not
                if($user)
                {
                    //WE Have Data
                    $username = $user['username'];
                    $email = $user['email'];
                    $address = $user['address'];
                }
                else
                {
                    //User not Available
                    //Redirect to Home Page
                    header('location:'.SITEURL);
                    exit();
                }
            } catch (Exception $e) {
                //Error occurred
                $_SESSION['order'] = "<div class='error text-center'>Error: " . $e->getMessage() . "</div>";
                header('location:'.SITEURL);
                exit();
            }
        }
        else
        {
            //Redirect to homepage
            header('location:'.SITEURL);
            exit();
        }
    ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Confirm your order.</h2>

            <form action="" method="POST" class="order">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <?php 
                        
                            //CHeck whether the image is available or not
                            if($image_name=="")
                            {
                                //Image not Availabe
                                echo "<div class='error'>Image not Available.</div>";
                            }
                            else
                            {
                                //Image is Available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                                <?php
                            }
                        
                        ?>
                        
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $title; ?></h3>
                        <input type="hidden" name="food" value="<?php echo $title; ?>">

                        <p class="food-price">$<?php echo $price; ?></p>
                        <input type="hidden" name="price" value="<?php echo $price; ?>">

                        <div class="order-label">Quantity</div>
                        <input type="number" name="quantity" class="input-responsive" value="1" min="1" required>
                        
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend>Order Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name"  value="<?php echo $username; ?>"class="input-responsive" required>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" value="<?php echo $email; ?>" class="input-responsive" required>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="5" class="input-responsive" required><?php echo $address; ?></textarea>

                    <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
                </fieldset>

            </form>

            <?php 

                //CHeck whether submit button is clicked or not
                if(isset($_POST['submit']))
                {
                    if(isset($_GET['foodID']) && isset($_GET['uID']))
                    {
                        $foodID = $_GET['foodID'];
                        $uID = $_GET['uID'];

                        try {
                            // Get all the details from the form
                            $quantity = (int)$_POST['quantity'];
                            $price = (float)$_POST['price'];
                            $total = $price * $quantity; // total = price x quantity 

                            $order_date = date("Y-m-d h:i:sa"); //Order Date

                            $status = "Ordered";  // Ordered, On Delivery, Delivered, Cancelled

                            //Save the Order in Database
                            $orderCollection = $conn->selectCollection('orders');
                            
                            $result = $orderCollection->insertOne([
                                'foodID' => $foodID,
                                'quantity' => $quantity,
                                'total' => $total,
                                'order_date' => $order_date,
                                'status' => $status,
                                'uID' => $uID
                            ]);

                            //Check whether order was inserted successfully
                            if($result->getInsertedCount() > 0)
                            {
                                //Query Executed and Order Saved
                                $_SESSION['order'] = "<div class='success text-center'>Food Ordered Successfully.</div>";
                                header('location:'.SITEURL);
                            }
                            else
                            {
                                //Failed to Save Order
                                $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food.</div>";
                                header('location:'.SITEURL);
                            }
                        } catch (Exception $e) {
                            //Error occurred
                            $_SESSION['order'] = "<div class='error text-center'>Order Error: " . $e->getMessage() . "</div>";
                            header('location:'.SITEURL);
                        }
                    }
                }
            
            ?>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->