<?php session_start(); ?>

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
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include header to maintain consistent styling -->
    
    <div class="content-container smaller-container">
        <h2>Doctor Login</h2>
        <form action="doctor_login_processing.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
            </div>
            <div class="form-row">
                <button type="submit" class="button-41" role="button" name="loginbtn">Log in</button>
            </div>
        </form>
    </div>
</body>
</html>
