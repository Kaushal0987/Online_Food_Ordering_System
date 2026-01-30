<?php 
    // Prevent multiple inclusions
    if (defined('CONSTANTS_LOADED')) {
        return;
    }
    define('CONSTANTS_LOADED', true);

    //Start Session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Create Constants to Store Non Repeating Values
    if (!defined('SITEURL')) {
        define('SITEURL', getenv('SITEURL') ?: 'http://localhost/Online_Food_Ordering_System/');
    }
    
    // MongoDB Configuration
    $mongodb_uri = getenv('MONGODB_URI');
    $mongodb_host = getenv('MONGODB_HOST') ?: 'localhost';
    $mongodb_port = getenv('MONGODB_PORT') ?: '27017';
    $mongodb_db = getenv('MONGODB_DATABASE') ?: 'online_food_ordering_system';

    if (!defined('MONGODB_DATABASE')) {
        define('MONGODB_DATABASE', $mongodb_db);
    }
    
    // MongoDB Connection
    try {
        // Require MongoDB library (make sure to run: composer install)
        $vendorPath = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($vendorPath)) {
            require_once $vendorPath;
        } else {
            die("MongoDB library not found. Please run 'composer install' in the project root directory.");
        }
        
        // Create MongoDB client
        if ($mongodb_uri) {
            $mongoClient = new MongoDB\Client($mongodb_uri);
        } else {
            $mongoClient = new MongoDB\Client("mongodb://" . $mongodb_host . ":" . $mongodb_port);
        }
        
        // Select database
        $database = $mongoClient->selectDatabase(MONGODB_DATABASE);
        
        // Connection successful - store database connection
        $conn = $database;
        
    } catch (Exception $e) {
        die("MongoDB Connection Error: " . $e->getMessage() . "<br>Make sure MongoDB is running and accessible. If on Vercel, check MONGODB_URI environment variable.");
    }
    
    // Helper function to convert MongoDB ObjectId to string
    if (!function_exists('mongoIdToString')) {
        function mongoIdToString($id) {
            if ($id instanceof MongoDB\BSON\ObjectId) {
                return (string) $id;
            }
            return $id;
        }
    }
    
    // Helper function to convert string to MongoDB ObjectId
    if (!function_exists('stringToMongoId')) {
        function stringToMongoId($id) {
            try {
                if ($id instanceof MongoDB\BSON\ObjectId) {
                    return $id;
                }
                if (empty($id)) {
                    return null;
                }
                return new MongoDB\BSON\ObjectId($id);
            } catch (Exception $e) {
                return null;
            }
        }
    }
    
    // Helper function to check if a string is a valid MongoDB ObjectId
    if (!function_exists('isValidMongoId')) {
        function isValidMongoId($id) {
            if ($id instanceof MongoDB\BSON\ObjectId) {
                return true;
            }
            return preg_match('/^[a-f\d]{24}$/i', $id) === 1;
        }
    }
?>
