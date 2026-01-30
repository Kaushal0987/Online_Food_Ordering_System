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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Import Food Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            border-bottom: 3px solid #ff6b6b;
            padding-bottom: 10px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }

        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }

        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #ff5252;
        }

        .stats {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .stat-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .stat-item strong {
            display: block;
            font-size: 1.5em;
            color: #667eea;
        }
    </style>
</head>

<body>
    <div class='container'>
        <h1>🍕 Food Data Import</h1>

        <?php
        // Check if form was submitted from verify page
        if (isset($_POST['confirm_import'])) {
            // Get arrays from POST
            $image_names = $_POST['image_name'];
            $titles = $_POST['title'];
            $descriptions = $_POST['description'];
            $prices = $_POST['price'];
            $actives = $_POST['active'];

            $imported = 0;
            $skipped = 0;
            $errors = 0;
            $totalItems = count($image_names);

            echo "<div class='info'>📦 Processing $totalItems food items...</div>";

            // Import each food item
            for ($i = 0; $i < $totalItems; $i++) {
                try {
                    // Check if image already exists in database (double-check for safety)
                    $existingFood = $foodsCollection->findOne(['image_name' => $image_names[$i]]);

                    if ($existingFood) {
                        // Image already exists, skip it
                        $skipped++;
                        echo "<div class='warning'>⚠️ Skipped: <strong>" . htmlspecialchars($titles[$i]) . "</strong> - Image already exists in database (Image: " . htmlspecialchars($image_names[$i]) . ")</div>";
                        continue;
                    }

                    // Image doesn't exist, proceed with import
                    $foodDocument = [
                        'title' => trim($titles[$i]),
                        'description' => trim($descriptions[$i]),
                        'price' => (float) $prices[$i],
                        'image_name' => $image_names[$i],
                        'active' => $actives[$i]
                    ];

                    // Insert into MongoDB
                    $result = $foodsCollection->insertOne($foodDocument);

                    if ($result->getInsertedCount() > 0) {
                        $imported++;
                        echo "<div class='success'>✅ Imported: <strong>" . htmlspecialchars($titles[$i]) . "</strong> (Image: " . htmlspecialchars($image_names[$i]) . ", Price: $" . number_format($prices[$i], 2) . ")</div>";
                    } else {
                        $errors++;
                        echo "<div class='error'>❌ Failed to import: " . htmlspecialchars($image_names[$i]) . "</div>";
                    }

                } catch (Exception $e) {
                    $errors++;
                    echo "<div class='error'>❌ Error importing " . htmlspecialchars($image_names[$i]) . ": " . $e->getMessage() . "</div>";
                }
            }

            // Display summary
            echo "<div class='stats'>
                    <h3>📊 Import Summary</h3>
                    <div class='stats-grid'>
                        <div class='stat-item'>
                            <strong>$totalItems</strong>
                            <span>Total Processed</span>
                        </div>
                        <div class='stat-item'>
                            <strong style='color: #4CAF50;'>$imported</strong>
                            <span>Successfully Imported</span>
                        </div>
                        <div class='stat-item'>
                            <strong style='color: #ff9800;'>$skipped</strong>
                            <span>Skipped (Duplicates)</span>
                        </div>
                        <div class='stat-item'>
                            <strong style='color: #f44336;'>$errors</strong>
                            <span>Errors</span>
                        </div>
                    </div>
                    <p style='margin-top: 15px; text-align: center;'><strong>Total Foods in Database:</strong> " . $foodsCollection->countDocuments() . "</p>
                  </div>";

            if ($skipped > 0) {
                echo "<div class='info'>ℹ️ <strong>Note:</strong> $skipped item(s) were skipped because they were already in the database. This prevents duplicate entries.</div>";
            }

        } else {
            // No form submission - redirect to verify page
            echo "<div class='info'>⚠️ Please use the verification page to import foods.</div>";
            echo "<p>You will be redirected to the verification page...</p>";
            echo "<script>setTimeout(function(){ window.location.href = 'verify_import_food.php'; }, 2000);</script>";
        }
        ?>

        <a href='view_foods.php' class='btn'>View All Foods</a>
        <a href='manage-food.php' class='btn' style='background-color: #4CAF50;'>Manage Foods</a>
        <a href='verify_import_food.php' class='btn' style='background-color: #2196F3;'>Import More</a>
    </div>
</body>

</html>