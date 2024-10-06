<?php
session_start();
include("database.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // SQL query to fetch patients
    $sql = "SELECT * FROM patients";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Execute query
        $stmt->execute();

        // Fetch all patients as an associative array
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($patients) {
            $response['status'] = 'success';
            $response['patients'] = $patients;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No patients found';
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching patients: ' . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Only GET requests are allowed.';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
