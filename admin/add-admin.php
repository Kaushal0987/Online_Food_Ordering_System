<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['add'])) //Checking whether the Session is Set or Not
            {
                echo $_SESSION['add']; //Display the Session Message if SET
                unset($_SESSION['add']); //Remove Session Message
            }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username" required>
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password" required>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>


    </div>
</div>


<?php 
    //Process the Value from Form and Save it in Database

    //Check whether the submit button is clicked or not

    if(isset($_POST['submit']))
    {
        try {
            // Button Clicked

            //1. Get the Data from form
            $username = trim($_POST['username']);
            $password = md5($_POST['password']); //Password Encryption with MD5

            //2. Check if username already exists
            $collection = $conn->selectCollection('admins');
            $existingAdmin = $collection->findOne(['username' => $username]);
            
            if($existingAdmin) {
                $_SESSION['add'] = "<div class='error'>Username already exists.</div>";
                header("location:".SITEURL.'admin/add-admin.php');
                exit();
            }

            //3. Insert Into MongoDB Database
            $result = $collection->insertOne([
                'username' => $username,
                'password' => $password
            ]);

            //4. Check whether the data is inserted or not and display appropriate message
            if($result->getInsertedCount() > 0)
            {
                //Data Inserted
                //Create a Session Variable to Display Message
                $_SESSION['add'] = "<div class='success'>Admin Added Successfully.</div>";
                //Redirect Page to Manage Admin
                header("location:".SITEURL.'admin/manage-admin.php');
            }
            else
            {
                //Failed to Insert Data
                //Create a Session Variable to Display Message
                $_SESSION['add'] = "<div class='error'>Failed to Add Admin.</div>";
                //Redirect Page to Add Admin
                header("location:".SITEURL.'admin/add-admin.php');
            }
        } catch (Exception $e) {
            $_SESSION['add'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
            header("location:".SITEURL.'admin/add-admin.php');
        }
    }
    
?>