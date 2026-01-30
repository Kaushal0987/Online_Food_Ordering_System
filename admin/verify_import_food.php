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

// Path to food images directory
$imageDir = '../images/food/';

// Check if directory exists
if (!is_dir($imageDir)) {
    die("Images directory not found: $imageDir");
}

// Scan directory for image files
$files = scandir($imageDir);
$imageFiles = array_filter($files, function ($file) use ($imageDir) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) && is_file($imageDir . $file);
});

// Get all existing image names from database
$existingImages = [];
$existingFoods = $foodsCollection->find([], ['projection' => ['image_name' => 1]]);
foreach ($existingFoods as $food) {
    if (isset($food['image_name']) && !empty($food['image_name'])) {
        $existingImages[] = $food['image_name'];
    }
}

// Separate new images from already imported ones
$newImages = [];
$alreadyImported = [];

foreach ($imageFiles as $imageFile) {
    if (in_array($imageFile, $existingImages)) {
        $alreadyImported[] = $imageFile;
    } else {
        $newImages[] = $imageFile;
    }
}

// Sample food names and descriptions for pre-filling
$foodNames = [
    'Delicious Pizza',
    'Juicy Burger',
    'Fresh Momo',
    'Crispy Chicken',
    'Pasta Delight',
    'Spicy Noodles',
    'Grilled Sandwich',
    'Tasty Biryani',
    'Cheese Pizza',
    'Veggie Burger',
    'Chicken Momo',
    'Fish Fry',
    'Fried Rice',
    'Spring Rolls',
    'Garlic Bread',
    'Caesar Salad'
];

$descriptions = [
    'Fresh and delicious, made with premium ingredients',
    'Perfectly cooked with authentic spices and flavors',
    'A mouth-watering dish that will satisfy your cravings',
    'Prepared with love and served hot',
    'Traditional recipe with a modern twist',
    'Crispy on the outside, tender on the inside',
    'Loaded with fresh vegetables and premium toppings',
    'A perfect blend of taste and quality',
    'Chef\'s special with secret ingredients',
    'Healthy and delicious, perfect for any meal',
    'Served with special sauce and garnishing',
    'A customer favorite, highly recommended',
    'Rich in flavor and absolutely delicious',
    'Made fresh daily with quality ingredients',
    'Perfect portion size for a satisfying meal',
    'A delightful treat for your taste buds'
];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Verify Food Import</title>
    <link rel="stylesheet" href="../CSS/admin_style.css">
    <style>
        .verify-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-box.new {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .stat-box.existing {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
        }

        .stat-box.total {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
        }

        .stat-box h2 {
            font-size: 3em;
            margin: 0;
        }

        .stat-box p {
            margin: 10px 0 0 0;
            font-size: 1.1em;
        }

        .section-header {
            background: #f5f5f5;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 30px 0 20px 0;
            border-left: 5px solid #4CAF50;
        }

        .section-header.already-imported {
            border-left-color: #ff9800;
        }

        .section-header h2 {
            margin: 0;
            color: #333;
        }

        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .food-preview-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .food-preview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .food-preview-card.already-imported {
            opacity: 0.7;
            border: 3px solid #ff9800;
        }

        .preview-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-filename {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px;
            font-size: 0.85em;
            font-family: monospace;
        }

        .already-imported-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff9800;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .preview-form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 60px;
        }

        .price-active-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 5px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
            cursor: pointer;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 20px;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 10px;
            margin-top: 30px;
        }

        .btn-import {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-import:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .btn-import:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #f44336 0%, #da190b 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .no-images {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .no-images h2 {
            color: #667eea;
            margin-bottom: 20px;
        }

        .info-message {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .info-message h3 {
            color: #856404;
            margin: 0 0 10px 0;
        }

        .info-message p {
            color: #856404;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include('partials/menu.php'); ?>

    <div class="main-content">
        <div class="verify-container">
            <div class="page-header">
                <h1>🍕 Verify Food Import</h1>
                <p>Review and edit food details before importing to database</p>
            </div>

            <div class="stats-grid">
                <div class="stat-box total">
                    <h2><?php echo count($imageFiles); ?></h2>
                    <p>📁 Total Images Found</p>
                </div>
                <div class="stat-box new">
                    <h2><?php echo count($newImages); ?></h2>
                    <p>✨ New Images (Ready to Import)</p>
                </div>
                <div class="stat-box existing">
                    <h2><?php echo count($alreadyImported); ?></h2>
                    <p>⚠️ Already Imported (Skipped)</p>
                </div>
            </div>

            <?php if (count($newImages) > 0): ?>
                <div class="section-header">
                    <h2>✨ New Images - Ready to Import (<?php echo count($newImages); ?>)</h2>
                </div>

                <form action="import_food_data.php" method="POST">
                    <div class="food-grid">
                        <?php
                        $index = 0;
                        foreach ($newImages as $imageFile):
                            // Use image filename as food title (remove extension and format)
                            $fileNameWithoutExt = pathinfo($imageFile, PATHINFO_FILENAME);
                            // Replace underscores and hyphens with spaces, capitalize words
                            $foodTitle = ucwords(str_replace(['_', '-'], ' ', $fileNameWithoutExt));

                            $randomDesc = $descriptions[array_rand($descriptions)];
                            $randomPrice = rand(5, 25) + (rand(0, 99) / 100);
                            ?>
                            <div class="food-preview-card">
                                <div class="preview-image-container">
                                    <img src="../images/food/<?php echo htmlspecialchars($imageFile); ?>"
                                        alt="<?php echo htmlspecialchars($imageFile); ?>" class="preview-image">
                                    <div class="image-filename">📁 <?php echo htmlspecialchars($imageFile); ?></div>
                                </div>

                                <div class="preview-form">
                                    <input type="hidden" name="image_name[]"
                                        value="<?php echo htmlspecialchars($imageFile); ?>">

                                    <div class="form-group">
                                        <label>Food Title:</label>
                                        <input type="text" name="title[]" value="<?php echo htmlspecialchars($foodTitle); ?>"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label>Description:</label>
                                        <textarea name="description[]"
                                            required><?php echo htmlspecialchars($randomDesc); ?></textarea>
                                    </div>

                                    <div class="price-active-row">
                                        <div class="form-group">
                                            <label>Price ($):</label>
                                            <input type="number" name="price[]"
                                                value="<?php echo number_format($randomPrice, 2); ?>" step="0.01" min="0"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Active:</label>
                                            <div class="radio-group">
                                                <label>
                                                    <input type="radio" name="active[<?php echo $index; ?>]" value="Yes"
                                                        checked> Yes
                                                </label>
                                                <label>
                                                    <input type="radio" name="active[<?php echo $index; ?>]" value="No"> No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $index++;
                        endforeach;
                        ?>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" name="confirm_import" class="btn-import">
                            ✅ Import New Foods (<?php echo count($newImages); ?>)
                        </button>
                        <a href="manage-food.php" class="btn-cancel">
                            ❌ Cancel
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="info-message">
                    <h3>✅ All Images Already Imported!</h3>
                    <p>There are no new images to import. All images in the folder have already been added to the database.
                    </p>
                </div>
            <?php endif; ?>

            <?php if (count($alreadyImported) > 0): ?>
                <div class="section-header already-imported">
                    <h2>⚠️ Already Imported - Will Be Skipped (<?php echo count($alreadyImported); ?>)</h2>
                </div>

                <div class="food-grid">
                    <?php foreach ($alreadyImported as $imageFile): ?>
                        <div class="food-preview-card already-imported">
                            <div class="preview-image-container">
                                <img src="../images/food/<?php echo htmlspecialchars($imageFile); ?>"
                                    alt="<?php echo htmlspecialchars($imageFile); ?>" class="preview-image">
                                <div class="already-imported-badge">✓ IMPORTED</div>
                                <div class="image-filename">📁 <?php echo htmlspecialchars($imageFile); ?></div>
                            </div>
                            <div class="preview-form">
                                <p style="text-align: center; color: #ff9800; font-weight: bold; margin: 10px 0;">
                                    This image is already in the database
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($imageFiles) == 0): ?>
                <div class="no-images">
                    <h2>📂 No Images Found</h2>
                    <p>There are no images in the <code>images/food/</code> directory to import.</p>
                    <br>
                    <a href="manage-food.php" class="btn-cancel">Back to Manage Food</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>