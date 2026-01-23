<?php include('../config/constants.php'); 

// Check if remember me cookie exists and pre-fill username
$remembered_username = '';
$remember_checked = '';
if(isset($_COOKIE['admin_username'])) {
    $remembered_username = $_COOKIE['admin_username'];
    $remember_checked = 'checked';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Food Order System</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="../CSS/admin_style.css">
    </head>

    <body>
        
        <div class="login-container">
            <h1 class="form-title">Admin Login</h1>

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

            <!-- Login Form Starts Here -->
            <form action="" method="POST">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="username" placeholder="Email ID" value="<?php echo htmlspecialchars($remembered_username); ?>" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="remember-me">
                    <label>
                        <input type="checkbox" name="remember" <?php echo $remember_checked; ?>> Remember me
                    </label>
                </div>

                <input type="submit" name="submit" value="LOGIN" class="btn-login">
            </form>
            <!-- Login Form Ends Here -->
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

                //5. Handle Remember Me functionality
                if(isset($_POST['remember']) && $_POST['remember'] == 'on') {
                    // Set cookie for 30 days
                    setcookie('admin_username', $username, time() + (30 * 24 * 60 * 60), '/');
                } else {
                    // Remove cookie if remember me is not checked
                    if(isset($_COOKIE['admin_username'])) {
                        setcookie('admin_username', '', time() - 3600, '/');
                    }
                }

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