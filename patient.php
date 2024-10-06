<?php

$errors = array(); // Initialize an empty array to store errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("patient_registration_processing.php"); // Include processing script if form is submitted

    // Check if there are any errors from processing script
    if (!empty($error_message)) {
        $errors[] = $error_message; // Store error message in the errors array
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/main.ico">
    <title>Patient Registration - MedPoint Plus</title>
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
    
    <div class="content-container">
        <h2>Patient Registration</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="patient.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" minlength="10" maxlength="10">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" maxlength="20">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Gender</label>
                    <div>
                        <input type="radio" id="male" name="gender" value="male" required>
                        <label for="male">Male</label>
                    </div>
                    <div>
                        <input type="radio" id="female" name="gender" value="female" required>
                        <label for="female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <p>Already have an account? <a href="Patientlogin.php">Log in</a></p>
            </div>
            <div class="form-row">
                <button type="submit" class="button-41" role="button" name="registerbtn"> Register </button>
            </div>
        </form>
    </div>
</body>
</html>
