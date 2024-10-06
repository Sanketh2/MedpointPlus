<?php
session_start();
include("database.php"); // Include database connection setup

if(isset($_POST["username"]) && isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    try {
        // Prepare SQL statement to fetch admin data based on username
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Password correct, set session variables
                $_SESSION["admin_name"] = $admin['admin_name'];
                $_SESSION["username"] = $admin['username'];

                // Redirect to admin dashboard or relevant page
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $_SESSION["error_message"] = "Incorrect password.";
            }
        } else {
            $_SESSION["error_message"] = "Username not found.";
        }
    } catch (PDOException $e) {
        // Handle database query errors
        $_SESSION["error_message"] = "Database query failed: " . $e->getMessage();
    }
}

// Redirect back to admin login page after processing
header("Location: admin.php");
exit();
?>
