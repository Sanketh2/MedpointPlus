<?php 
session_start(); 
$errors = isset($_SESSION["error_message"]) ? [$_SESSION["error_message"]] : []; // Initialize errors array

// Clear error message from session
unset($_SESSION["error_message"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("patient_login_processing.php"); // Include processing script if form is submitted

    // Re-check for errors from session after processing script
    if (!empty($_SESSION["error_message"])) {
        $errors[] = $_SESSION["error_message"]; // Store error message in the errors array
        unset($_SESSION["error_message"]); // Clear the session error message after displaying it
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/main.ico">
    <title>Patient Login - MedPoint Plus</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include Google Fonts or other necessary links -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        .error-message {
            color: red; /* Set error message color */
            margin-top: 5px; /* Optional: Add some space above error messages */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include header to maintain consistent styling -->
    
    <div class="content-container smaller-container">
        <h2>Patient Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="patient_login_processing.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <div class="form-row">
                <button type="submit" class="button-41" role="button" name="loginbtn">Log in</button>
            </div>
        </form>
    </div>
</body>
</html>
