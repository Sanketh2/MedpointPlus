<?php
session_start();
include("database.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $doctorId = $_POST['doctorId'];
    $doctorName = $_POST['doctorName'];
    $fees = $_POST['fees'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Note: Securely hash this password before saving to database

    // Example of password hashing (using PHP password_hash function)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if doctor_id already exists
    $checkDoctorIdSql = "SELECT COUNT(*) AS count FROM doctors WHERE doctor_id = :doctorId";
    $checkDoctorIdStmt = $pdo->prepare($checkDoctorIdSql);
    $checkDoctorIdStmt->bindParam(':doctorId', $doctorId, PDO::PARAM_STR);
    $checkDoctorIdStmt->execute();
    $doctorIdRow = $checkDoctorIdStmt->fetch(PDO::FETCH_ASSOC);

    // Check if username already exists
    $checkUsernameSql = "SELECT COUNT(*) AS count FROM doctors WHERE username = :username";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
    $checkUsernameStmt->bindParam(':username', $username, PDO::PARAM_STR);
    $checkUsernameStmt->execute();
    $usernameRow = $checkUsernameStmt->fetch(PDO::FETCH_ASSOC);

    if ($doctorIdRow['count'] > 0) {
        // Doctor_id already exists
        $response['status'] = 'error';
        $response['message'] = 'Doctor ID already exists';
    } elseif ($usernameRow['count'] > 0) {
        // Username already exists
        $response['status'] = 'error';
        $response['message'] = 'Username already exists';
    } else {
        // Insert new doctor into database
        $insertSql = "INSERT INTO doctors (doctor_id, doctor_name, fees, username, password) 
                      VALUES (:doctorId, :doctorName, :fees, :username, :password)";

        try {
            $stmt = $pdo->prepare($insertSql);
            $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_STR);
            $stmt->bindParam(':doctorName', $doctorName, PDO::PARAM_STR);
            $stmt->bindParam(':fees', $fees, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            
            $stmt->execute();

            $response['status'] = 'success';
            $response['message'] = 'Doctor ' . $doctorName . ' added successfully';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error adding doctor: ' . $e->getMessage();
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
