<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>

                    <li>
                        <a href="<?php echo SITEURL; ?>">Home</a>
                    </li>

                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                    </li>

                    <li>
                        <?php 
                            if (!isset($_SESSION['login-status'])){
                                echo '<a href="http://localhost/Online_Food_Ordering_System/login.php">Login</a>';
                            }
                            else{
                                echo '<a href="http://localhost/Online_Food_Ordering_System/logout.php">Logout</a>';
                            }
                        ?>
                    </li>

                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->