<?php
session_start();
include("database.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch appointments based on date
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    // Convert the input date format if needed (from dd-mm-yyyy to yyyy-mm-dd)
    if (!empty($date)) {
        $formattedDate = date('Y-m-d', strtotime($date));
    } else {
        $formattedDate = ''; // Handle empty date case if necessary
    }
    
    // Modify SQL query as per your database structure
    $sql = "SELECT patients.firstname,patients.lastname, appointments.patient_email, doctors.doctor_name, appointments.appointment_date, appointments.appointment_time, appointments.status
            FROM appointments
            INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
            INNER JOIN patients ON appointments.patient_email = patients.email";
    
    // Append WHERE clause if date is provided
    if (!empty($formattedDate)) {
        $sql .= " WHERE appointments.appointment_date = :appointment_date";
    }

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters if date is provided
        if (!empty($formattedDate)) {
            $stmt->bindParam(':appointment_date', $formattedDate, PDO::PARAM_STR);
        }

        $stmt->execute();
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($appointments) {
            $response['status'] = 'success';
            $response['appointments'] = $appointments;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No appointments found for selected date';
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching appointments: ' . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
