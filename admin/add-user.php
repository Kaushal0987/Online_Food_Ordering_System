<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add User</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
        
            <table class="tbl-30">
                
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="username">
                    </td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td>
                        <input type="text" name="email" placeholder="email">
                    </td>
                </tr>

                <tr>
                    <td>Address: </td>
                    <td>
                        <input type="text" name="address" placeholder="address">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="text" name="password" placeholder="password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add User" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        
        <?php 

            //CHeck whether the button is clicked or not
            if(isset($_POST['submit']))
            {
                //Add the user in Database
                //echo "Clicked";
                
                //1. Get the data from Form
                $username = $_POST['username'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $password = md5($_POST['password']);

                //3. Insert Into Database

                //Create a SQL Query to Save or Add user
                // For Numerical we do not need to pass value inside quotes '' But for string value it is compulsory to add quotes ''
                $sql2 = "INSERT INTO tbl_users(username,email, address, password)
                       VALUES ('$username','$email', '$address', '$password')";

                //Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                //CHeck whether data inserted or not
                //4. Redirect with MEssage to Manage Food page
                if($res2 == true)
                {
                    //Data inserted Successfullly
                    $_SESSION['add'] = "<div class='success'>user Added Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-user.php');
                }
                else
                {
                    //FAiled to Insert Data
                    $_SESSION['add'] = "<div class='error'>Failed to Add user.</div>";
                    header('location:'.SITEURL.'admin/manage-user.php');
                }

                
            }

        ?>


    </div>
</div>