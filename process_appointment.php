<?php

session_start();
include("database.php");

date_default_timezone_set('Asia/Kolkata'); // Adjust timezone as per your requirements

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate appointment date (today or later)
    $currentDate = date("Y-m-d");
    $appointmentDate = $_POST['date'];

    if ($appointmentDate < $currentDate) {
        $response['status'] = 'error';
        $response['message'] = 'Appointment date must be today or later.';
    } else {
        // Validate appointment time (at least 1 hour from current time)
        $currentDateTime = new DateTime();
        $currentDateTime->modify('+1 hour'); // Adjust current time by adding 1 hour

        $appointmentTime = $_POST['time'];
        $appointmentDateTime = new DateTime($appointmentDate . ' ' . $appointmentTime);

        if ($appointmentDateTime < $currentDateTime) {
            $response['status'] = 'error';
            $response['message'] = 'Appointment time must be at least 1 hour from current time.';
        } else {
            // Check if the appointment already exists for the given doctor, date, and time, with a 3-minute gap
            $doctorId = $_POST['doctor'];

            // Modified query to check if any appointment exists within a 3-minute gap on the same date
            $stmt_check = $pdo->prepare("
                SELECT COUNT(*) AS count 
                FROM appointments 
                WHERE doctor_id = ? 
                    AND appointment_date = ? 
                    AND (
                        (appointment_time >= ADDTIME(?, '-00:03:00')) 
                        AND 
                        (appointment_time <= ADDTIME(?, '00:03:00'))
                    )
            ");
            
            $stmt_check->execute([$doctorId, $appointmentDate, $appointmentTime, $appointmentTime]);
            $row = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                // Error if an appointment is found within the 3-minute gap
                $response['status'] = 'error';
                $response['message'] = 'Appointment slot is already booked or within a 3-minute gap. Please choose a different time.';
            } else {
                // Proceed to insert appointment into the database
                $patientEmail = $_SESSION['email']; // Assuming session contains patient's email
                $bookingTime = date("Y-m-d H:i:s"); // Current timestamp for booking time

                try {
                    $stmt = $pdo->prepare("INSERT INTO appointments (patient_email, doctor_id, appointment_date, appointment_time, booking_time, status) VALUES (?, ?, ?, ?, ?, 'ACTIVE')");
                    $stmt->execute([$patientEmail, $doctorId, $appointmentDate, $appointmentTime, $bookingTime]);

                    $response['status'] = 'success';
                    $response['message'] = 'Appointment booked successfully!';
                } catch (PDOException $e) {
                    $response['status'] = 'error';
                    $response['message'] = 'Error: ' . $e->getMessage();
                }
            }
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
