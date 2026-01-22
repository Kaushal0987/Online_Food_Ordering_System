<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php 
            if(isset($_GET['id']))
            {
                $id=$_GET['id'];
            }
        ?>

        <form action="" method="POST">
        
            <table class="tbl-30">
                <tr>
                    <td>Current Password: </td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password: </td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

    </div>
</div>

<?php 

            //CHeck whether the Submit Button is Clicked or Not
            if(isset($_POST['submit']))
            {
                try {
                    //1. Get the Data from Form
                    $id = $_POST['id'];
                    $current_password = md5($_POST['current_password']);
                    $new_password = md5($_POST['new_password']);
                    $confirm_password = md5($_POST['confirm_password']);

                    //2. Check whether the user with current ID and Current Password Exists or Not
                    $collection = $conn->selectCollection('admins');
                    $admin = $collection->findOne([
                        '_id' => stringToMongoId($id),
                        'password' => $current_password
                    ]);

                    if($admin)
                    {
                        //User Exists and Password Can be Changed
                        
                        //Check whether the new password and confirm match or not
                        if($new_password == $confirm_password)
                        {
                            //Update the Password
                            $result = $collection->updateOne(
                                ['_id' => stringToMongoId($id)],
                                ['$set' => ['password' => $new_password]]
                            );

                            //Check whether the query executed or not
                            if($result->getModifiedCount() > 0)
                            {
                                //Display Success Message
                                //Redirect to Manage Admin Page with Success Message
                                $_SESSION['change-pwd'] = "<div class='success'>Password Changed Successfully. </div>";
                                //Redirect the User
                                header('location:'.SITEURL.'admin/manage-admin.php');
                            }
                            else
                            {
                                //Display Error Message
                                //Redirect to Manage Admin Page with Error Message
                                $_SESSION['change-pwd'] = "<div class='error'>Failed to Change Password. </div>";
                                //Redirect the User
                                header('location:'.SITEURL.'admin/manage-admin.php');
                            }
                        }
                        else
                        {
                            //Redirect to Manage Admin Page with Error Message
                            $_SESSION['pwd-not-match'] = "<div class='error'>Password Did not Match. </div>";
                            //Redirect the User
                            header('location:'.SITEURL.'admin/manage-admin.php');
                        }
                    }
                    else
                    {
                        //User Does not Exist Set Message and Redirect
                        $_SESSION['user-not-found'] = "<div class='error'>User Not Found or Current Password Incorrect. </div>";
                        //Redirect the User
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                } catch (Exception $e) {
                    $_SESSION['change-pwd'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }

?>