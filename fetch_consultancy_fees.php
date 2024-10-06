<?php
// fetch_consultancy_fees.php
// fetch_consultancy_fees.php
session_start();
include("database.php");

if(isset($_POST['doctorId'])) {
    $doctorId = $_POST['doctorId'];

    try {
        $stmt = $pdo->prepare("SELECT fees FROM doctors WHERE doctor_id = ?");
        $stmt->execute([$doctorId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            echo $row['fees'];
        } else {
            echo "Fees not found for selected doctor.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Doctor ID not specified."; // This message should not appear if doctorId is correctly sent
}

?>
