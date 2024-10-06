<?php
session_start();
include("database.php");

// Set the default timezone to IST (Indian Standard Time)
date_default_timezone_set('Asia/Kolkata');

$response = array();

if (!isset($_SESSION['email'])) {
    $response['status'] = 'error';
    $response['message'] = 'Session email not set.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientEmail = $_SESSION['email'];
    $sql = "SELECT appointments.doctor_id, appointments.appointment_date, appointments.appointment_time, doctors.doctor_name, appointments.status
            FROM appointments
            INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
            WHERE appointments.patient_email = :patient_email";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['patient_email' => $patientEmail]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($appointments) {
            $currentDate = date("Y-m-d");
            $currentTime = date("H:i:s");

            foreach ($appointments as &$appointment) {
                $appointmentDate = $appointment['appointment_date'];
                $appointmentTime = $appointment['appointment_time'];
                $originalStatus = $appointment['status'];

                if ($originalStatus === 'cancelled') {
                    continue;
                }

                if ($appointmentDate < $currentDate) {
                    $newStatus = 'inactive';
                } elseif ($appointmentDate == $currentDate && $appointmentTime < $currentTime) {
                    $newStatus = 'inactive';
                } else {
                    $newStatus = 'active';
                }

                $appointment['status'] = $newStatus;

                if ($newStatus != $originalStatus) {
                    $updateSql = "UPDATE appointments 
                                  SET status = :status 
                                  WHERE doctor_id = :doctor_id 
                                  AND appointment_date = :appointment_date 
                                  AND appointment_time = :appointment_time
                                  AND patient_email = :patient_email";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([
                        'status' => $newStatus,
                        'doctor_id' => $appointment['doctor_id'],
                        'appointment_date' => $appointmentDate,
                        'appointment_time' => $appointmentTime,
                        'patient_email' => $patientEmail
                    ]);

                    // Debug statement
                    echo "Updated status for Appointment ID: {$appointment['doctor_id']}, Date: $appointmentDate, Time: $appointmentTime to $newStatus<br>";
                }
            }

            $response['status'] = 'success';
            $response['appointments'] = $appointments;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No appointments found for ' . $patientEmail;
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching appointment history: ' . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
