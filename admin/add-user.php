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
                try {
                    //Add the user in Database
                    
                    //1. Get the data from Form
                    $username = trim($_POST['username']);
                    $email = trim($_POST['email']);
                    $address = trim($_POST['address']);
                    $password = md5($_POST['password']);

                    //2. Check if email already exists
                    $collection = $conn->selectCollection('users');
                    $existingUser = $collection->findOne(['email' => $email]);
                    
                    if($existingUser) {
                        $_SESSION['add'] = "<div class='error'>Email already exists.</div>";
                        header('location:'.SITEURL.'admin/add-user.php');
                        exit();
                    }

                    //3. Insert Into MongoDB Database
                    $result = $collection->insertOne([
                        'username' => $username,
                        'email' => $email,
                        'address' => $address,
                        'password' => $password
                    ]);

                    //CHeck whether data inserted or not
                    //4. Redirect with Message to Manage User page
                    if($result->getInsertedCount() > 0)
                    {
                        //Data inserted Successfully
                        $_SESSION['add'] = "<div class='success'>User Added Successfully.</div>";
                        header('location:'.SITEURL.'admin/manage-user.php');
                    }
                    else
                    {
                        //Failed to Insert Data
                        $_SESSION['add'] = "<div class='error'>Failed to Add User.</div>";
                        header('location:'.SITEURL.'admin/manage-user.php');
                    }
                } catch (Exception $e) {
                    $_SESSION['add'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
                    header('location:'.SITEURL.'admin/manage-user.php');
                }
            }

        ?>


    </div>
</div>