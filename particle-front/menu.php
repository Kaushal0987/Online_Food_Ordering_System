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
                    <?php if (isset($_SESSION['login-status'])): ?>
                        <li>
                            <a href="<?php echo SITEURL; ?>my-orders.php">My Orders</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo SITEURL; ?>#menu">Menu</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>#contact">Contact</a>
                    </li>
                    <li>
                        <?php
                        if (!isset($_SESSION['login-status'])) {
                            echo '<a href="' . SITEURL . 'login.php" class="btn-login-nav">Login</a>';
                        } else {
                            echo '<a href="' . SITEURL . 'logout.php" class="btn-login-nav">Logout</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->

    <!-- Floating Cart Button -->
    <?php if (isset($_SESSION['login-status'])): ?>
        <a href="<?php echo SITEURL; ?>cart.php" class="floating-cart">
            <div class="cart-icon">🛒</div>
            <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
        </a>
    <?php endif; ?>