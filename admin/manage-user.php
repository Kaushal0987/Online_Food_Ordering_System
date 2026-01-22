<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage User</h1>

        <br /><br />

                <!-- Button to Add User -->
                <a href="<?php echo SITEURL; ?>admin/add-user.php" class="btn-primary">Add User</a>

                <br /><br /><br />

                <?php 
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }

                    if(isset($_SESSION['unauthorize']))
                    {
                        echo $_SESSION['unauthorize'];
                        unset($_SESSION['unauthorize']);
                    }

                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                
                ?>

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Username</th>
                        <th>E-mail</th>
                        <th>Address</th>
                        <th>password</th>
                        <th>Actions</th>
                    </tr>

                    <?php 
                        try {
                            //Get all users from MongoDB
                            $collection = $conn->selectCollection('users');
                            
                            //Find all users
                            $cursor = $collection->find();
                            
                            //Get users as array
                            $users = iterator_to_array($cursor);
                            $count = count($users);

                            //Create Serial Number Variable and Set Default Value as 1
                            $sn = 1;

                            if($count > 0)
                            {
                                //We have users in Database
                                //Get the users from Database and Display
                                foreach($users as $user)
                                {
                                    //get the values from individual columns
                                    $id = mongoIdToString($user['_id']);
                                    $username = $user['username'];
                                    $email = $user['email'];
                                    $address = $user['address'];
                                    $password = md5($user['password']); // Double MD5 for display only
                                    ?>

                                    <tr>
                                        <td><?php echo $sn++; ?>. </td>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $address; ?></td>
                                        <td><?php echo $password; ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-user.php?id=<?php echo $id; ?>" class="btn-secondary">Update User</a>
                                            <a href="<?php echo SITEURL; ?>admin/delete-user.php?id=<?php echo $id; ?>" class="btn-danger">Delete User</a>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            else
                            {
                                //user not Added in Database
                                echo "<tr> <td colspan='6' class='error'> No Users Added Yet. </td> </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr> <td colspan='6' class='error'> Error: " . $e->getMessage() . " </td> </tr>";
                        }
                    ?>

                    
                </table>
    </div>
    
</div>