<?php 
    include('../config/constants.php'); 
    include('login-check.php');
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Food Order Website - Admin Panel</title>

        <link rel="stylesheet" href="../CSS/admin_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <!-- Sidebar Section Starts -->
        <div class="menu">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" onerror="this.style.display='none';">
                <span>FoodAdmin</span>
            </div>
            <ul>
                <li><a href="index.php"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
                <li><a href="manage-admin.php"><i class="fas fa-user-shield"></i> <span>Admin</span></a></li>
                <li><a href="manage-user.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li><a href="manage-food.php"><i class="fas fa-utensils"></i> <span>Foods</span></a></li>
                <li><a href="manage-order.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        <!-- Sidebar Section Ends -->