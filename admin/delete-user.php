
<?php 

    //Include constants.php file here
    include('../config/constants.php');

    // 1. get the ID of user to be deleted
    $id = $_GET['id'];

    //2. Create SQL Query to Delete user
    $sql = "DELETE FROM tbl_users WHERE uID=$id";

    //Execute the Query
    $res = mysqli_query($conn, $sql);

    // Check whether the query executed successfully or not
    if($res==true)
    {
        //Query Executed Successully and user Deleted
        //echo "user Deleted";
        //Create SEssion Variable to Display Message
        $_SESSION['delete'] = "<div class='success'>User Deleted Successfully.</div>";
        //Redirect to Manage user Page
        header('location:'.SITEURL.'admin/manage-user.php');
    }
    else
    {
        //Failed to Delete user
        //echo "Failed to Delete user";

        $_SESSION['delete'] = "<div class='error'>Failed to Delete user. Try Again Later.</div>";
        header('location:'.SITEURL.'admin/manage-user.php');
    }

    //3. Redirect to Manage user page with message (success/error)

?>
