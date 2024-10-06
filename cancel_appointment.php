<?php
session_start();
include("database.php"); // Assuming this file contains your database connection logic

// Check if all necessary parameters are set and not empty
if (
    isset($_POST['patient_email']) && !empty($_POST['patient_email']) &&
    isset($_POST['doctor_id']) && !empty($_POST['doctor_id']) &&
    isset($_POST['appointment_date']) && !empty($_POST['appointment_date']) &&
    isset($_POST['appointment_time']) && !empty($_POST['appointment_time'])
) {
    $patientEmail = $_POST['patient_email'];
    $doctorId = $_POST['doctor_id'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    // Call function to cancel appointment
    $status = cancelAppointment($patientEmail, $doctorId, $appointmentDate, $appointmentTime);

    if ($status) {
        $response = [
            'status' => 'success',
            'message' => 'Appointment cancelled successfully.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Failed to cancel appointment. Please try again.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Missing required parameters.'
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Function to cancel appointment
function cancelAppointment($patientEmail, $doctorId, $appointmentDate, $appointmentTime) {
    global $pdo;
    $query = "UPDATE appointments SET status = 'CANCELLED' WHERE patient_email = :patient_email AND doctor_id = :doctor_id AND appointment_date = :appointment_date AND appointment_time = :appointment_time";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'patient_email' => $patientEmail,
        'doctor_id' => $doctorId,
        'appointment_date' => $appointmentDate,
        'appointment_time' => $appointmentTime
    ]);
    return $stmt->rowCount(); // Return number of rows affected
}
?>
