<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Admin</h1>

        <br><br>

        <?php 
            try {
                //1. Get the ID of Selected Admin
                $id = $_GET['id'];

                //2. Get admin details from MongoDB
                $collection = $conn->selectCollection('admins');
                $admin = $collection->findOne(['_id' => stringToMongoId($id)]);

                //Check whether the admin is available or not
                if($admin)
                {
                    // Get the Details
                    $username = $admin['username'];
                }
                else
                {
                    //Redirect to Manage Admin Page
                    header('location:'.SITEURL.'admin/manage-admin.php');
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                header('location:'.SITEURL.'admin/manage-admin.php');
                exit();
            }
        
        ?>


        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>" required>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
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

            //Update Admin in MongoDB
            $collection = $conn->selectCollection('admins');
            $result = $collection->updateOne(
                ['_id' => stringToMongoId($id)],
                ['$set' => [
                    'username' => $username
                ]]
            );

            //Check whether the query executed successfully or not
            if($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0)
            {
                //Query Executed and Admin Updated
                $_SESSION['update'] = "<div class='success'>Admin Updated Successfully.</div>";
                //Redirect to Manage Admin Page
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
            else
            {
                //No changes made
                $_SESSION['update'] = "<div class='error'>No changes made to Admin.</div>";
                //Redirect to Manage Admin Page
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        } catch (Exception $e) {
            $_SESSION['update'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }

?>
