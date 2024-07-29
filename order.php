
<?php include('particle-front/menu.php'); ?>

    <?php 
        //CHeck whether food id is set or not
        if(isset($_GET['foodID']) && isset($_GET['uID']))
        {
            //Get the Food id and details of the selected food
            $foodID = $_GET['foodID'];

            //Get the DEtails of the SElected Food
            $sql = "SELECT * FROM tbl_food WHERE foodID=$foodID";
            //Execute the Query
            $res = mysqli_query($conn, $sql);
            //Count the rows
            $count = mysqli_num_rows($res);
            //CHeck whether the data is available or not
            if($count==1)
            {
                //WE Have DAta
                //GEt the Data from Database
                $row = mysqli_fetch_assoc($res);

                $title = $row['title'];
                $price = $row['price'];
                $image_name = $row['image_name'];
            }
            else
            {
                //Food not Availabe
                //REdirect to Home Page
                header('location:'.SITEURL);
            }

            //Get the User id and details of the selected User
            $uID = $_GET['uID'];

            //Get the DEtails of the SElected User
            $sql1 = "SELECT * FROM tbl_users WHERE uID=$uID";
            //Execute the Query
            $res1 = mysqli_query($conn, $sql1);
            //Count the rows
            $count1 = mysqli_num_rows($res1);
            //CHeck whether the data is available or not
            if($count1==1)
            {
                //WE Have DAta
                //GEt the Data from Database
                $row1 = mysqli_fetch_assoc($res1);

                $username = $row1['username'];
                $email = $row1['email'];
                $address = $row1['address'];
            }
            else
            {
                //User not Availabe
                //REdirect to Home Page
                header('location:'.SITEURL);
            }
        }
        else
        {
            //Redirect to homepage
            header('location:'.SITEURL);
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

                        // Get all the details from the form
                        $quantity = $_POST['quantity'];

                        $price = $_POST['price'];
                        $total = $price * $quantity; // total = price x quantity 

                        $order_date = date("Y-m-d h:i:sa"); //Order DAte

                        $status = "Ordered";  // Ordered, On Delivery, Delivered, Cancelled


                        //Save the Order in Databaase
                        //Create SQL to save the data
                        $sql2 = "INSERT INTO tbl_order SET 
                            foodID = '$foodID',
                            quantity = $quantity,
                            total = $total,
                            order_date = '$order_date',
                            status = '$status',
                            uID = $uID
                        ";

                        //echo $sql2; die();

                        //Execute the Query
                        $res2 = mysqli_query($conn, $sql2);

                        //Check whether query executed successfully or not
                        if($res2==true)
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
                    }
                }
            
            ?>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->