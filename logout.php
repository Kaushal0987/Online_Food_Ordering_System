<?php
include('config/constants.php');
$_SESSION['login-status'] = false;
session_destroy();
header('location:'.SITEURL.'index.php');
?>