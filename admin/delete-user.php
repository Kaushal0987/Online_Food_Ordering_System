<?php

//Include constants.php file here
include('../config/constants.php');

//Check if admin is logged in
include('partials/login-check.php');

if (isset($_GET['id'])) {
    try {
        // 1. get the ID of user to be deleted
        $id = $_GET['id'];

        //2. Delete user from MongoDB
        $collection = $conn->selectCollection('users');
        $result = $collection->deleteOne(['_id' => stringToMongoId($id)]);

        // Check whether the query executed successfully or not
        if ($result->getDeletedCount() > 0) {
            //Query Executed Successfully and user Deleted
            //Create Session Variable to Display Message
            $_SESSION['delete'] = "<div class='success'>User Deleted Successfully.</div>";
            //Redirect to Manage user Page
            header('location:' . SITEURL . 'admin/manage-user.php');
        } else {
            //Failed to Delete user
            $_SESSION['delete'] = "<div class='error'>Failed to Delete User. Try Again Later.</div>";
            header('location:' . SITEURL . 'admin/manage-user.php');
        }
    } catch (Exception $e) {
        $_SESSION['delete'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
        header('location:' . SITEURL . 'admin/manage-user.php');
    }
} else {
    $_SESSION['delete'] = "<div class='error'>Unauthorized Access.</div>";
    header('location:' . SITEURL . 'admin/manage-user.php');
}

?>