<?php
// Include database connection or require_once("database.php");
session_start();
include("database.php"); // Adjust as per your file structure and database connection method

// Check if date parameter is set
if (isset($_POST['date'])) {
    $date = $_POST['date'];
    $doctorId = $_SESSION['doctor_id']; // Fetch doctor_id from session
    
    // Example SQL query to fetch appointments for a specific doctor and date
    $query = "SELECT *
              FROM appointments a 
              JOIN patients p ON a.patient_email = p.email 
              WHERE a.doctor_id = :doctor_id 
              AND a.appointment_date = :date
              AND a.status = 'ACTIVE'";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['doctor_id' => $doctorId, 'date' => $date]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($appointments) {
        // Prepare response as JSON
        $response = array('status' => 'success', 'appointments' => $appointments);
        echo json_encode($response);
    } else {
        // No appointments found for the selected doctor and date
        $response = array('status' => 'error', 'message' => 'No appointments found for selected date');
        echo json_encode($response);
    }
} else {
    // Date parameter not provided
    $response = array('status' => 'error', 'message' => 'Date parameter is missing');
    echo json_encode($response);
}
?>
