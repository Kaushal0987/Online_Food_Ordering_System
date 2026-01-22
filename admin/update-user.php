<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update User</h1>

        <br><br>

        <?php 
            try {
                //1. Get the ID of Selected User
                $id = $_GET['id'];

                //2. Get user details from MongoDB
                $collection = $conn->selectCollection('users');
                $user = $collection->findOne(['_id' => stringToMongoId($id)]);

                //Check whether the user is available or not
                if($user)
                {
                    // Get the Details
                    $username = $user['username'];
                    $email = $user['email'];
                    $address = $user['address'];
                    $password = $user['password'];
                }
                else
                {
                    //Redirect to Manage User Page
                    header('location:'.SITEURL.'admin/manage-user.php');
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                header('location:'.SITEURL.'admin/manage-user.php');
                exit();
            }
        
        ?>


        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>">
                    </td>
                </tr>

                <tr>
                    <td>email: </td>
                    <td>
                        <input type="email" name="email" value="<?php echo $email; ?>">
                    </td>
                </tr>

                <tr>
                    <td>address: </td>
                    <td>
                        <input type="text" name="address" value="<?php echo $address; ?>">
                    </td>
                </tr>

                <tr>
                    <td>password: </td>
                    <td>
                        <input type="password" name="password" value="<?php echo $password; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update user" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>
    </div>
</div>

<?php 

    //Check whether the Submit Button is Clicked or not
    if(isset($_POST['submit']))
    {
        try {
            //Get all the values from form to update
            $id = $_POST['id'];
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $address = trim($_POST['address']);
            $password = md5($_POST['password']);

            //Update user in MongoDB
            $collection = $conn->selectCollection('users');
            $result = $collection->updateOne(
                ['_id' => stringToMongoId($id)],
                ['$set' => [
                    'username' => $username,
                    'email' => $email,
                    'address' => $address,
                    'password' => $password
                ]]
            );

            //Check whether the query executed successfully or not
            if($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0)
            {
                //Query Executed and user Updated
                $_SESSION['update'] = "<div class='success'>User Updated Successfully.</div>";
                //Redirect to Manage user Page
                header('location:'.SITEURL.'admin/manage-user.php');
            }
            else
            {
                //No changes made
                $_SESSION['update'] = "<div class='error'>No changes made to User.</div>";
                //Redirect to Manage user Page
                header('location:'.SITEURL.'admin/manage-user.php');
            }
        } catch (Exception $e) {
            $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
            header('location:'.SITEURL.'admin/manage-user.php');
        }
    }

?>