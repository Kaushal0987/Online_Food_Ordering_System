   <?php include('particle-front/menu.php'); ?>

<!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
<!-- fOOD sEARCH Section Ends Here -->

    <?php 
        if(isset($_SESSION['order']))
        {
            echo $_SESSION['order'];
            unset($_SESSION['order']);
        }
    ?>

<!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php 
                if(isset($_SESSION['uID'])){
                    $uID = $_SESSION['uID'];
                
                    //Getting Foods from Database that are active and featured
                    //SQL Query
                    $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' LIMIT 6";

                    //Execute the Query
                    $res2 = mysqli_query($conn, $sql2);

                    //Count Rows
                    $count2 = mysqli_num_rows($res2);

                    //CHeck whether food available or not
                    if($count2>0)
                    {
                        //Food Available
                        while($row=mysqli_fetch_assoc($res2))
                        {
                            //Get all the values
                            $foodID = $row['foodID'];
                            $title = $row['title'];
                            $price = $row['price'];
                            $description = $row['description'];
                            $image_name = $row['image_name'];
                            ?>

                            <div class="food-menu-box">
                                <div class="food-menu-img">
                                    <?php 
                                        //Check whether image available or not
                                        if($image_name=="")
                                        {
                                            //Image not Available
                                            echo "<div class='error'>Image not available.</div>";
                                        }
                                        else
                                        {
                                            //Image Available
                                            ?>
                                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                                            <?php
                                        }
                                    ?>
                                    
                                </div>

                                <div class="food-menu-desc">
                                    <h4><?php echo $title; ?></h4>
                                    <p class="food-price">$<?php echo $price; ?></p>
                                    <p class="food-detail">
                                        <?php echo $description; ?>
                                    </p>
                                    <br>

                                    <a href="<?php echo SITEURL; ?>order.php?foodID=<?php echo $foodID; ?>&uID=<?php echo $uID; ?>" class="btn btn-primary">Order Now</a>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    else
                    {
                        //Food Not Available 
                        echo "<div class='error'>Food not available.</div>";
                    }
                }
                else
                {
                    //Redirect to login Page
                    header('location:'.SITEURL.'login.php');
                }
            ?>

            



            <div class="clearfix"></div>

            

        </div>

    </section>
<!-- fOOD Menu Section Ends Here -->


</body>
</html>