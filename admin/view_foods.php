<?php
// Include constants for database connection
include('../config/constants.php');

// Check if admin is logged in
include('partials/login-check.php');

// Check if MongoDB connection exists
if (!isset($conn)) {
    die("Database connection not established. Please check your MongoDB setup.");
}

// Get the foods collection
$foodsCollection = $conn->selectCollection('foods');

// Fetch all foods
$foods = $foodsCollection->find([], ['sort' => ['title' => 1]]);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View All Foods</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .stats h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .food-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .food-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .food-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid #667eea;
        }

        .food-details {
            padding: 20px;
        }

        .food-title {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .food-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .food-price {
            font-size: 1.5em;
            color: #ff6b6b;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .food-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .food-id {
            font-size: 0.8em;
            color: #999;
            margin-top: 10px;
            font-family: monospace;
        }

        .no-foods {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .no-foods h2 {
            color: #667eea;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #ff5252;
        }

        .btn-primary {
            background-color: #667eea;
        }

        .btn-primary:hover {
            background-color: #5568d3;
        }

        .btn-success {
            background-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #45a049;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🍕 All Food Items</h1>

        <?php
        $totalFoods = $foodsCollection->countDocuments();
        $activeFoods = $foodsCollection->countDocuments(['active' => 'Yes']);
        ?>

        <div class="stats">
            <h3>📊 Database Statistics</h3>
            <p><strong>Total Foods:</strong> <?php echo $totalFoods; ?> | <strong>Active:</strong>
                <?php echo $activeFoods; ?></p>
        </div>

        <?php if ($totalFoods > 0): ?>
            <div class="food-grid">
                <?php foreach ($foods as $food): ?>
                    <div class="food-card">
                        <?php
                        $imagePath = '../images/food/' . $food['image_name'];
                        // Check if image exists, otherwise use placeholder
                        if (!file_exists($imagePath)) {
                            $imagePath = '../images/pizza.jpg'; // Fallback image
                        }
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($food['title']); ?>"
                            class="food-image">

                        <div class="food-details">
                            <div class="food-title"><?php echo htmlspecialchars($food['title']); ?></div>
                            <div class="food-description"><?php echo htmlspecialchars($food['description']); ?></div>
                            <div class="food-price">$<?php echo number_format($food['price'], 2); ?></div>
                            <span
                                class="food-status <?php echo $food['active'] == 'Yes' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $food['active'] == 'Yes' ? '✅ Active' : '❌ Inactive'; ?>
                            </span>
                            <div class="food-id">ID: <?php echo mongoIdToString($food['_id']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-foods">
                <h2>No Food Items Found</h2>
                <p>There are no food items in the database yet.</p>
                <a href="import_food_data.php" class="btn btn-success">Import Food Data</a>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="import_food_data.php" class="btn btn-success">Import More Foods</a>
            <a href="manage-food.php" class="btn btn-primary">Manage Foods</a>
            <a href="index.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>