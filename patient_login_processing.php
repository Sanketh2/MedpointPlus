<?php
session_start();
include("database.php"); // Include database connection setup

if(isset($_POST["loginbtn"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Fetch user data from database based on email
    try {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password correct, set session variables
                $_SESSION["fullname"] = $user['firstname'] . " " . $user['lastname'];
                $_SESSION["email"] = $user['email']; // Optionally, store email in session

                // Redirect to patient dashboard
                header("Location: patient_dashboard.php");
                exit();
            } else {
                $_SESSION["error_message"] = "Incorrect password.";
            }
        } else {
            $_SESSION["error_message"] = "Email not found. Please register first.";
        }
    } catch (PDOException $e) {
        // Handle database query errors
        $_SESSION["error_message"] = "Database query failed: " . $e->getMessage();
    }
}

// Redirect back to patient login page after processing
header("Location: patientlogin.php");
exit();
?>
