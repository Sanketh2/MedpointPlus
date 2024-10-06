<?php
session_start();
include("database.php"); // Include database connection setup

$error_message = ''; // Initialize variable to hold error message

if (isset($_POST["registerbtn"])) {
    // Retrieve form data
    $firstname = $_POST["first_name"];
    $lastname = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $gender = $_POST["gender"];
    $confirm_password = $_POST["confirm_password"];

    // Validate password length server-side
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please enter matching passwords.";
    }

    // Proceed with registration if no validation errors
    if (empty($error_message)) {
        // Hash password
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Check if email already exists in database
            $stmt = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Email already exists, set error message
                $error_message = "Patient with email '$email' already exists. Please choose a different email.";
            } else {
                // Email does not exist, proceed with registration
                $stmt = $pdo->prepare("INSERT INTO patients (firstname, lastname, email, phone, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$firstname, $lastname, $email, $phone, $password_hashed, $gender]);

                // Set session variables
                $_SESSION["fullname"] = $firstname . " " . $lastname;
                $_SESSION["email"] = $email; // Set email to the registered email

                // Redirect to patient dashboard
                header("Location: patient_dashboard.php");
                exit();
            }
        } catch (PDOException $e) {
            // Handle database query errors
            $error_message = "Database query failed: " . $e->getMessage();
        }
    }
}
?>
