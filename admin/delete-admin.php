
<?php 

    //Include constants.php file here
    include('../config/constants.php');

    if(isset($_GET['id'])) {
        try {
            // 1. get the ID of Admin to be deleted
            $id = $_GET['id'];

            //2. Delete Admin from MongoDB
            $collection = $conn->selectCollection('admins');
            $result = $collection->deleteOne(['_id' => stringToMongoId($id)]);

            // Check whether the query executed successfully or not
            if($result->getDeletedCount() > 0)
            {
                //Query Executed Successfully and Admin Deleted
                //Create Session Variable to Display Message
                $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully.</div>";
                //Redirect to Manage Admin Page
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
            else
            {
                //Failed to Delete Admin
                $_SESSION['delete'] = "<div class='error'>Failed to Delete Admin. Try Again Later.</div>";
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        } catch (Exception $e) {
            $_SESSION['delete'] = "<div class='error'>Error: " . $e->getMessage() . "</div>";
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    } else {
        $_SESSION['delete'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:'.SITEURL.'admin/manage-admin.php');
    }

?>
