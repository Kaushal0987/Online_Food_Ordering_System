<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class="main-content">
            <div class="wrapper">
                <h1>Dashboard</h1>
                <br><br>
                <?php 
                    if(isset($_SESSION['login']))
                    {
                        echo $_SESSION['login'];
                        unset($_SESSION['login']);
                    }
                ?>
                <br><br>

                <div class="col-4 text-center">

                    <?php 
                        try {
                            //Get users collection count
                            $collection = $conn->selectCollection('users');
                            $count1 = $collection->countDocuments();
                        } catch (Exception $e) {
                            $count1 = 0;
                        }
                    ?>

                    <h1><?php echo $count1; ?></h1>
                    <br />
                    Users
                </div>

                <div class="col-4 text-center">

                    <?php 
                        try {
                            //Get foods collection count
                            $collection = $conn->selectCollection('foods');
                            $count2 = $collection->countDocuments();
                        } catch (Exception $e) {
                            $count2 = 0;
                        }
                    ?>

                    <h1><?php echo $count2; ?></h1>
                    <br />
                    Foods
                </div>

                <div class="col-4 text-center">
                    
                    <?php 
                        try {
                            //Get orders collection count
                            $collection = $conn->selectCollection('orders');
                            $count3 = $collection->countDocuments();
                        } catch (Exception $e) {
                            $count3 = 0;
                        }
                    ?>

                    <h1><?php echo $count3; ?></h1>
                    <br />
                    Total Orders
                </div>

                <div class="col-4 text-center">
                    
                    <?php 
                        try {
                            //Calculate Total Revenue from Delivered Orders
                            $collection = $conn->selectCollection('orders');
                            
                            $pipeline = [
                                ['$match' => ['status' => 'Delivered']],
                                ['$group' => ['_id' => null, 'Total' => ['$sum' => '$total']]]
                            ];
                            
                            $result = $collection->aggregate($pipeline)->toArray();
                            $total_revenue = isset($result[0]['Total']) ? $result[0]['Total'] : 0;
                        } catch (Exception $e) {
                            $total_revenue = 0;
                        }
                    ?>

                    <h1>$<?php echo $total_revenue; ?></h1>
                    <br />
                    Revenue Generated
                </div>

                <div class="clearfix"></div>

            </div>
        </div>
        <!-- Main Content Setion Ends -->