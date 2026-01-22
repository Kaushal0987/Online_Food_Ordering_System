<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
        
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Title of the Food">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Food."></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes 
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        
        <?php 

            //CHeck whether the button is clicked or not
            if(isset($_POST['submit']))
            {
                //Add the Food in Database
                
                try {
                    //1. Get the Data from Form
                    $title = trim($_POST['title']);
                    $description = trim($_POST['description']);
                    $price = (float)$_POST['price'];

                    if(isset($_POST['active']))
                    {
                        $active = $_POST['active'];
                    }
                    else
                    {
                        $active = "No"; //Setting Default Value
                    }

                    //2. Upload the Image if selected
                    //Check whether the select image is clicked or not and upload the image only if the image is selected
                    if(isset($_FILES['image']['name']))
                    {
                        //Get the details of the selected image
                        $image_name = $_FILES['image']['name'];

                        //Check Whether the Image is Selected or not and upload image only if selected
                        if($image_name!="")
                        {
                            // Image is Selected
                            //A. Rename the Image
                            //Get the extension of selected image (jpg, png, gif, etc.)
                            $ext = end(explode('.', $image_name));

                            // Create New Name for Image
                            $image_name = "Food-Name-".rand(0000,9999).".".$ext; //New Image Name May Be "Food-Name-657.jpg"

                            //B. Upload the Image
                            //Get the Src Path and Destination path

                            // Source path is the current location of the image
                            $src = $_FILES['image']['tmp_name'];

                            //Destination Path for the image to be uploaded
                            $dst = "../images/food/".$image_name;

                            //Finally Upload the food image
                            $upload = move_uploaded_file($src, $dst);

                            //check whether image uploaded or not
                            if($upload==false)
                            {
                                //Failed to Upload the image
                                //Redirect to Add Food Page with Error Message
                                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                                header('location:'.SITEURL.'admin/add-food.php');
                                //Stop the process
                                die();
                            }
                        }
                    }
                    else
                    {
                        $image_name = ""; //Setting Default Value as blank
                    }

                    //3. Insert Into MongoDB Database
                    $collection = $conn->selectCollection('foods');
                    
                    $result = $collection->insertOne([
                        'title' => $title,
                        'description' => $description,
                        'price' => $price,
                        'image_name' => $image_name,
                        'active' => $active
                    ]);

                    //Check whether data inserted or not
                    //4. Redirect with Message to Manage Food page
                    if($result->getInsertedCount() > 0)
                    {
                        //Data inserted Successfully
                        $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                    }
                    else
                    {
                        //Failed to Insert Data
                        $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                    }
                } catch (Exception $e) {
                    $_SESSION['add'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
            }

        ?>


    </div>
</div>