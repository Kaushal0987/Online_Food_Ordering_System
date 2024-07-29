<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update User</h1>

        <br><br>

        <?php 
            //1. Get the ID of Selected User
            $id=$_GET['id'];

            //2. Create SQL Query to Get the Details
            $sql="SELECT * FROM tbl_users WHERE uID=$id";

            //Execute the Query
            $res=mysqli_query($conn, $sql);

            //Check whether the query is executed or not
            if($res==true)
            {
                // Check whether the data is available or not
                $count = mysqli_num_rows($res);
                //Check whether we have user data or not
                if($count==1)
                {
                    // Get the Details
                    //echo "user Available";
                    $row=mysqli_fetch_assoc($res);

                    $username = $row['username'];
                    $email = $row['email'];
                    $address = $row['address'];
                    $password = $row['password'];
                }
                else
                {
                    //Redirect to Manage Admin PAge
                    header('location:'.SITEURL.'admin/manage-user.php');
                }
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
        //echo "Button CLicked";
        //Get all the values from form to update
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = md5($_POST['password']);

        //Create a SQL Query to Update user
        $sql = "UPDATE tbl_users SET
        username = '$username',
        email = '$email',
        address = '$address',
        password = '$password' 
        WHERE uID='$id'
        ";

        //Execute the Query
        $res = mysqli_query($conn, $sql);

        //Check whether the query executed successfully or not
        if($res==true)
        {
            //Query Executed and user Updated
            $_SESSION['update'] = "<div class='success'>User Updated Successfully.</div>";
            //Redirect to Manage user Page
            header('location:'.SITEURL.'admin/manage-user.php');
        }
        else
        {
            //Failed to Update user
            $_SESSION['update'] = "<div class='error'>Failed to Delete user.</div>";
            //Redirect to Manage user Page
            header('location:'.SITEURL.'admin/manage-user.php');
        }
    }

?>