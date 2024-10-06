<?php
session_start();
include("database.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $doctorIdRemove = $_POST['doctorIdRemove'];
    $usernameRemove = $_POST['usernameRemove'];
    $adminPassword = $_POST['adminPassword'];

    // Fetch admin details from session or database
    if (isset($_SESSION['username'])) {
        $adminUsername = $_SESSION['username'];

        // Validate admin password against database
        $sqlAdmin = "SELECT * FROM admin WHERE username = :adminUsername";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->bindParam(':adminUsername', $adminUsername, PDO::PARAM_STR);
        $stmtAdmin->execute();
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Verify admin password
            if (password_verify($adminPassword, $admin['password'])) {
                // Admin password matches, proceed to remove doctor
                $sql = "DELETE FROM doctors WHERE doctor_id = :doctorIdRemove AND username = :usernameRemove";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':doctorIdRemove', $doctorIdRemove, PDO::PARAM_STR);
                    $stmt->bindParam(':usernameRemove', $usernameRemove, PDO::PARAM_STR);
                    
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        // Doctor removed successfully
                        $response['status'] = 'success';
                        $response['message'] = 'Doctor ' . $doctorIdRemove . ' removed successfully';
                    } else {
                        // No rows affected, doctor not found
                        $response['status'] = 'error';
                        $response['message'] = 'Doctor ' . $doctorIdRemove . ' not found or removal failed';
                    }
                } catch (PDOException $e) {
                    // Database error
                    $response['status'] = 'error';
                    $response['message'] = 'Error removing doctor: ' . $e->getMessage();
                }
            } else {
                // Admin password does not match
                $response['status'] = 'error';
                $response['message'] = 'Admin validation failed. Please enter correct admin password.';
            }
        } else {
            // Admin not found in database
            $response['status'] = 'error';
            $response['message'] = 'Admin not found. Please log in again.';
        }
    } else {
        // Session username not set
        $response['status'] = 'error';
        $response['message'] = 'Admin session expired. Please log in again.';
    }
} else {
    // Invalid request method
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
