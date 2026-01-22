<?php include('../config/constants.php'); ?>

<html>
    <head>
        <title>Login - Food Order System</title>
        <link rel="stylesheet" href="../css/admin_style.css">
    </head>

    <body>
        
        <div class="login">
            <h1 class="text-center">Login</h1>
            <br><br>

            <?php 
                if(isset($_SESSION['login']))
                {
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }

                if(isset($_SESSION['no-login-message']))
                {
                    echo $_SESSION['no-login-message'];
                    unset($_SESSION['no-login-message']);
                }
            ?>
            <br><br>

            <!-- Login Form Starts HEre -->
            <form action="" method="POST" class="text-center">
            Username: <br>
            <input type="text" name="username" placeholder="Enter Username"><br><br>

            Password: <br>
            <input type="password" name="password" placeholder="Enter Password"><br><br>

            <input type="submit" name="submit" value="Login" class="btn-primary">
            <br><br>
            </form>
            <!-- Login Form Ends HEre -->
        </div>

    </body>
</html>

<?php 

    //CHeck whether the Submit Button is Clicked or NOt
    if(isset($_POST['submit']))
    {
        //Process for Login
        //1. Get the Data from Login form
        $username = trim($_POST['username']);
        $password = md5($_POST['password']);

        //2. Query MongoDB to check whether the user with username and password exists or not
        try {
            $collection = $conn->selectCollection('admins');
            
            //3. Find admin with matching username and password
            $admin = $collection->findOne([
                'username' => $username,
                'password' => $password
            ]);

            //4. Check whether the admin exists or not
            if($admin)
            {
                //User Available and Login Success
                $_SESSION['login'] = "<div class='success'>Login Successful.</div>";
                $_SESSION['user'] = $username; //TO check whether the user is logged in or not and logout will unset it
                $_SESSION['adminID'] = mongoIdToString($admin['_id']); //Store admin ID for reference

                //REdirect to HOme Page/Dashboard
                header('location:'.SITEURL.'admin/');
            }
            else
            {
                //User not Available and Login Fail
                $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
                //REdirect to login page
                header('location:'.SITEURL.'admin/login.php');
            }
        } catch (Exception $e) {
            //Error during login
            $_SESSION['login'] = "<div class='error text-center'>Login Error: " . $e->getMessage() . "</div>";
            header('location:'.SITEURL.'admin/login.php');
        }
    }

?>