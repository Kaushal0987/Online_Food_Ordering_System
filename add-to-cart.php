<?php
include('config/constants.php');

if (isset($_GET['id'])) {
    $foodID = $_GET['id'];
    $qty = 1;

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in cart and update quantity
    if (isset($_SESSION['cart'][$foodID])) {
        $_SESSION['cart'][$foodID] += $qty;
    } else {
        $_SESSION['cart'][$foodID] = $qty;
    }

    // Redirect back to the previous page
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('location:' . SITEURL);
    }
} else {
    header('location:' . SITEURL);
}
?>