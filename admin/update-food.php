<?php include('partials/menu.php'); ?>

<?php 
    //CHeck whether id is set or not 
    if(isset($_GET['id']))
    {
        try {
            //Get all the details
            $id = $_GET['id'];

            //Get the Selected Food from MongoDB
            $collection = $conn->selectCollection('foods');
            $food = $collection->findOne(['_id' => stringToMongoId($id)]);

            if($food) {
                //Get the Individual Values of Selected Food
                $title = $food['title'];
                $description = $food['description'];
                $price = $food['price'];
                $current_image = $food['image_name'];
                $active = $food['active'];
            } else {
                //Food not found
                header('location:'.SITEURL.'admin/manage-food.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
            exit();
        }
    }
    else
    {
        //Redirect to Manage Food
        header('location:'.SITEURL.'admin/manage-food.php');
        exit();
    }
?>


<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
        
        <table class="tbl-30">

            <tr>
                <td>Title: </td>
                <td>
                    <input type="text" name="title" value="<?php echo $title; ?>">
                </td>
            </tr>

            <tr>
                <td>Description: </td>
                <td>
                    <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Price: </td>
                <td>
                    <input type="number" name="price" value="<?php echo $price; ?>">
                </td>
            </tr>

            <tr>
                <td>Current Image: </td>
                <td>
                    <?php 
                        if($current_image == "")
                        {
                            //Image not Available 
                            echo "<div class='error'>Image not Available.</div>";
                        }
                        else
                        {
                            //Image Available
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                            <?php
                        }
                    ?>
                </td>
            </tr>

            <tr>
                <td>Select New Image: </td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>

            <tr>
                <td>Active: </td>
                <td>
                    <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Yes 
                    <input <?php if($active=="No") {echo "checked";} ?> type="radio" name="active" value="No"> No 
                </td>
            </tr>

            <tr>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                    <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                </td>
            </tr>
        
        </table>
        
        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                try {
                    //1. Get all the details from the form
                    $id = $_POST['id'];
                    $title = trim($_POST['title']);
                    $description = trim($_POST['description']);
                    $price = (float)$_POST['price'];
                    $current_image = $_POST['current_image'];
                    $active = $_POST['active'];

                    //2. Upload the image if selected
                    //Check whether upload button is clicked or not
                    if(isset($_FILES['image']['name']))
                    {
                        //Upload Button Clicked
                        $image_name = $_FILES['image']['name']; //New Image Name

                        //Check whether the file is available or not
                        if($image_name!="")
                        {
                            //Image is Available
                            //A. Uploading New Image

                            //Rename the Image
                            $ext = end(explode('.', $image_name)); //Gets the extension of the image

                            $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; //This will be renamed image

                            //Get the Source Path and Destination Path
                            $src_path = $_FILES['image']['tmp_name']; //Source Path
                            $dest_path = "../images/food/".$image_name; //Destination Path

                            //Upload the image
                            $upload = move_uploaded_file($src_path, $dest_path);

                            //Check whether the image is uploaded or not
                            if($upload==false)
                            {
                                //Failed to Upload
                                $_SESSION['upload'] = "<div class='error'>Failed to Upload new Image.</div>";
                                //Redirect to Manage Food 
                                header('location:'.SITEURL.'admin/manage-food.php');
                                //Stop the Process
                                die();
                            }
                            //3. Remove the image if new image is uploaded and current image exists
                            //B. Remove current Image if Available
                            if($current_image!="")
                            {
                                //Current Image is Available
                                //Remove the image
                                $remove_path = "../images/food/".$current_image;

                                if(file_exists($remove_path)) {
                                    $remove = unlink($remove_path);

                                    //Check whether the image is removed or not
                                    if($remove==false)
                                    {
                                        //failed to remove current image
                                        $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current image.</div>";
                                        //redirect to manage food
                                        header('location:'.SITEURL.'admin/manage-food.php');
                                        //stop the process
                                        die();
                                    }
                                }
                            }
                        }
                        else
                        {
                            $image_name = $current_image; //Default Image when Image is Not Selected
                        }
                    }
                    else
                    {
                        $image_name = $current_image; //Default Image when Button is not Clicked
                    }

                    //4. Update the Food in MongoDB Database
                    $collection = $conn->selectCollection('foods');
                    $result = $collection->updateOne(
                        ['_id' => stringToMongoId($id)],
                        ['$set' => [
                            'title' => $title,
                            'description' => $description,
                            'price' => $price,
                            'image_name' => $image_name,
                            'active' => $active
                        ]]
                    );

                    //Check whether the query is executed or not 
                    if($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0)
                    {
                        //Query Executed and Food Updated
                        $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                    }
                    else
                    {
                        //No changes made
                        $_SESSION['update'] = "<div class='error'>No changes made to Food.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                    }
                } catch (Exception $e) {
                    $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
            }
        
        ?>

    </div>
</div>