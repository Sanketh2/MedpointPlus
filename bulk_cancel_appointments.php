<?php
session_start();
include("database.php"); // Adjust the path as per your file structure

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate session variable for doctor_id
    if (!isset($_SESSION['doctor_id']) || empty($_SESSION['doctor_id'])) {
        $response = [
            'status' => 'error',
            'message' => 'Doctor ID not found in session. Please log in again.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Stop further execution
    }

    // Sanitize and validate input parameters
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $doctor_id = $_SESSION['doctor_id'];

    if (!$start_date || !$end_date) {
        $response = [
            'status' => 'error',
            'message' => 'Invalid start or end date.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Stop further execution
    }

    try {
        // Prepare and execute the SQL query
        $query = "UPDATE appointments SET status = 'CANCELLED' WHERE doctor_id = :doctor_id AND appointment_date BETWEEN :start_date AND :end_date";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'doctor_id' => $doctor_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        // Check if any rows were affected
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            $response = [
                'status' => 'success',
                'message' => "Successfully cancelled $rowCount appointments."
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No appointments found within the specified date range.'
            ];
        }
    } catch (PDOException $e) {
        // Database error
        $response = [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }

} else {
    // Invalid request method
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method. Expected POST.'
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
