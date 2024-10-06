<?php
session_start();
include("database.php"); // Include database connection setup

if(isset($_POST["loginbtn"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Fetch doctor data from database based on username
    try {
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE username = ?");
        $stmt->execute([$username]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            // Verify password
            if (password_verify($password, $doctor['password'])) {
                // Password correct, set session variables
                $_SESSION["fullname"] = $doctor['doctor_name'];
                $_SESSION["username"] = $doctor['username']; // Optionally, store username in session
                $_SESSION["doctor_id"] = $doctor['doctor_id'];

                // Redirect to doctor dashboard
                header("Location: doctor_dashboard.php");
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

// Redirect back to doctor login page after processing
header("Location: doctor.php");
exit();
?>
