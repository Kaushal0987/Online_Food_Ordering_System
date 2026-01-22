
<?php include('particle-front/menu.php'); ?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <?php 

            //Get the Search Keyword
            $search = trim($_POST['search']);
        
        ?>


        <h2>Foods on Your Search <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>

    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->



<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 

            try {
                //MongoDB Query to Get foods based on search keyword using regex
                $foodCollection = $conn->selectCollection('foods');
                
                // Create regex pattern for search
                $regex = new MongoDB\BSON\Regex($search, 'i'); // 'i' for case-insensitive
                
                // Find foods where title or description matches search keyword
                $cursor = $foodCollection->find([
                    '$or' => [
                        ['title' => $regex],
                        ['description' => $regex]
                    ]
                ]);

                //Count results
                $foods = iterator_to_array($cursor);
                $count = count($foods);

                //Check whether food available or not
                if($count > 0)
                {
                    //Food Available
                    foreach($foods as $food)
                    {
                        //Get the details
                        $id = mongoIdToString($food['_id']);
                        $title = $food['title'];
                        $price = $food['price'];
                        $description = $food['description'];
                        $image_name = $food['image_name'];
                        ?>

                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php 
                                    // Check whether image name is available or not
                                    if($image_name=="")
                                    {
                                        //Image not Available
                                        echo "<div class='error'>Image not Available.</div>";
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

                                <a href="#" class="btn btn-primary">Order Now</a>
                            </div>
                        </div>

                        <?php
                    }
                }
                else
                {
                    //Food Not Available
                    echo "<div class='error'>Food not found.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>Search Error: " . $e->getMessage() . "</div>";
            }
        
        ?>

        

        <div class="clearfix"></div>

        

    </div>

</section>
<!-- fOOD Menu Section Ends Here -->