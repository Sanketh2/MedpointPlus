<?php
session_start();
include("database.php");

$response = array();

// Check if user is logged in
if (!isset($_SESSION["admin_name"])) {
    $response['status'] = 'error';
    $response['message'] = 'Unauthorized access';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $startDateInput = $_POST['startDate'];
    $endDateInput = $_POST['endDate'];

    // Convert input dates from dd-mm-yyyy to yyyy-mm-dd format for database
    $startDate = date('Y-m-d', strtotime($startDateInput));
    $endDate = date('Y-m-d', strtotime($endDateInput));

    // Validate start date is before end date
    if ($startDate > $endDate) {
        $response['status'] = 'error';
        $response['message'] = 'Start date must be before end date.';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Perform bulk cancellation
    $query = "UPDATE appointments 
              SET status = 'cancelled'
              WHERE appointment_date BETWEEN :startDate AND :endDate";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();

        // Check if any rows were affected
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            $response['status'] = 'success';
            $response['message'] = $rowCount . ' appointments cancelled successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No appointments found within the specified date range.';
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

/**
 * Validate date format as dd-mm-yyyy
 */
function validateDate($date)
{
    $d = DateTime::createFromFormat('d-m-Y', $date);
    return $d && $d->format('d-m-Y') === $date;
}
?>
