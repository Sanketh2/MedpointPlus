<?php
// Database connection setup (database.php)
$host = "localhost"; // Database host (usually 'localhost')
$dbname = "Hospital"; // Database name
$username = "root"; // Database username
$password = ""; // Database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set character set to UTF-8 (useful for international characters)
    $pdo->exec("SET NAMES utf8mb4");

} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Exit script if connection fails
}
?>
