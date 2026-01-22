<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Food</h1>

        <br /><br />

                <!-- Button to Add Admin -->
                <a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add Food</a>

                <br /><br /><br />

                <?php 
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }

                    if(isset($_SESSION['upload']))
                    {
                        echo $_SESSION['upload'];
                        unset($_SESSION['upload']);
                    }

                    if(isset($_SESSION['unauthorize']))
                    {
                        echo $_SESSION['unauthorize'];
                        unset($_SESSION['unauthorize']);
                    }

                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                
                ?>

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Title</th>
                        <th>description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>

                    <?php 
                        try {
                            //Get all foods from MongoDB
                            $collection = $conn->selectCollection('foods');
                            
                            //Find all foods
                            $cursor = $collection->find();
                            
                            //Get foods as array
                            $foods = iterator_to_array($cursor);
                            $count = count($foods);

                            //Create Serial Number Variable and Set Default Value as 1
                            $sn = 1;

                            if($count > 0)
                            {
                                //We have food in Database
                                //Get the Foods from Database and Display
                                foreach($foods as $food)
                                {
                                    //get the values from individual columns
                                    $id = mongoIdToString($food['_id']);
                                    $title = $food['title'];
                                    $description = $food['description'];
                                    $price = $food['price'];
                                    $image_name = $food['image_name'];
                                    $active = $food['active'];
                                    ?>

                                    <tr>
                                        <td><?php echo $sn++; ?>. </td>
                                        <td><?php echo $title; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td>$<?php echo $price; ?></td>
                                        <td>
                                            <?php  
                                                //Check whether we have image or not
                                                if($image_name=="")
                                                {
                                                    //WE do not have image, Display Error Message
                                                    echo "<div class='error'>Image not Added.</div>";
                                                }
                                                else
                                                {
                                                    //WE Have Image, Display Image
                                                    ?>
                                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" width="100px">
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $active; ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update Food</a>
                                            <a href="<?php echo SITEURL; ?>admin/delete-food.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-danger">Delete Food</a>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            else
                            {
                                //Food not Added in Database
                                echo "<tr> <td colspan='7' class='error'> Food not Added Yet. </td> </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr> <td colspan='7' class='error'> Error: " . $e->getMessage() . " </td> </tr>";
                        }
                    ?>

                    
                </table>
    </div>
    
</div>