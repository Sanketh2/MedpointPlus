<?php
// Start session at the beginning of the script
session_start();

include("header.php");
include("database.php");

// Function to fetch appointments based on doctor_id and date
// function fetchAppointmentsByDate($doctor_id, $date) {
//     global $pdo;
//     $query = "SELECT a.*, CONCAT(p.firstname, ' ', p.lastname) AS patient_name 
//               FROM appointments a 
//               JOIN patients p ON a.patient_email = p.email 
//               WHERE a.doctor_id = :doctor_id 
//               AND a.appointment_date = :appointment_date";
//     $stmt = $pdo->prepare($query);
//     $stmt->execute(['doctor_id' => $doctor_id, 'appointment_date' => $date]);
//     $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     return $appointments;
// }

// // Function to cancel appointment
// function cancelAppointment($patientEmail, $doctorId, $appointmentDate, $appointmentTime) {
//     global $pdo;
//     $query = "UPDATE appointments SET status = 'CANCELLED' WHERE patient_email = :patient_email AND doctor_id = :doctor_id AND appointment_date = :appointment_date AND appointment_time = :appointment_time";
//     $stmt = $pdo->prepare($query);
//     $stmt->execute([
//         'patient_email' => $patientEmail,
//         'doctor_id' => $doctorId,
//         'appointment_date' => $appointmentDate,
//         'appointment_time' => $appointmentTime
//     ]);
//     return $stmt->rowCount(); // Return number of rows affected
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/main.ico">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Datepicker CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Include Datepicker JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        /* Custom CSS for alignment */
        .content-container {
            margin-top: 20px;
        }
        .icon-container {
            text-align: center;
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            cursor: pointer; /* Add cursor pointer to indicate clickable */
        }
        .icon-container img {
            width: 100px; /* Adjust size of icons as needed */
            height: 100px;
        }
        .icon-text {
            text-align: center;
            margin-top: 10px;
        }
        .datepicker-container {
            margin-top: 20px;
        }
        .form-control-inline {
            display: inline-block;
            width: auto;
        }
        .table-cancel-button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar (10% width) -->
            <div class="col-md-2 bg-light">
                <div class="mt-4 ml-3">
                    <h5>Menu</h5>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" id="dashboardBtn"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="appointmentScheduleBtn"><i class="fas fa-calendar"></i> Show Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="bulkCancelBtn"><i class="fas fa-calendar-times"></i> Bulk Cancel Appointments</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area (90% width) -->
            <div class="col-md-10">
                <div class="content-container">
                    <h2>Welcome <?php echo strtoupper($_SESSION["fullname"]); ?></h2>

                    <!-- Dashboard Content (Initially Visible) -->
                    <div id="dashboardContent">
                        <!-- Content for Dashboard -->
                        <div class="mt-5">
                            <h3>Dashboard</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="icon-container" id="appointmentScheduleIcon">
                                        <a href="#" class="dashboard-link">
                                            <img src="icons/ShowAppointments.png" alt="Appointment Schedule">
                                            <div class="icon-text">Show Appointments</div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="icon-container" id="bulkCancelIcon">
                                        <a href="#" class="dashboard-link">
                                            <img src="icons/bulkcancel.png" alt="Bulk Cancel Appointments">
                                            <div class="icon-text">Bulk Cancel Appointments</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Show Appointments Content (Initially Hidden) -->
                    <div id="showAppointmentsContent" style="display: none;">
                        <!-- Content for Show Appointments -->
                        <div class="mt-5">
                            <h3>Show Appointments</h3>
                            <div class="datepicker-container">
                                <label for="datepicker">Find Appointments On:</label>
                                <input type="text" id="datepicker" class="form-control form-control-inline">
                                <button id="findAppointmentsBtn" class="btn btn-primary ml-2">Find Appointments</button>
                            </div>
                            <div id="appointmentsTable" class="mt-3">
                                <!-- Appointments table will be populated dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Cancel Appointments Content (Initially Hidden) -->
                    <div id="bulkCancelContent" style="display: none;">
                        <!-- Content for Bulk Cancel Appointments -->
                        <div class="mt-5">
                            <h3>Bulk Cancel Appointments</h3>
                            <div class="datepicker-container">
                                <label for="startDateTimePicker">Cancel appointments from:</label>
                                <input type="text" id="startDateTimePicker" class="form-control form-control-inline">
                                <label for="endDateTimePicker" class="ml-3">Cancel appointments till:</label>
                                <input type="text" id="endDateTimePicker" class="form-control form-control-inline ml-2">
                                <button id="bulkCancelAppointmentsBtn" class="btn btn-danger ml-2">Cancel Appointments</button>
                            </div>
                            <div id="bulkCancelMessage" class="mt-3">
                                <!-- Message about bulk cancellation results -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Handle Click Events and Dynamic Content -->
    <script>
        $(document).ready(function() {
            // Datepicker initialization
            $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#startDateTimePicker").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#endDateTimePicker").datepicker({ dateFormat: 'yy-mm-dd' });

            // Handle click events for navigation buttons
            $("#dashboardBtn").click(function(e) {
                e.preventDefault();
                showDashboard();
            });

            $("#appointmentScheduleBtn").click(function(e) {
                e.preventDefault();
                showAppointments();
            });

            $("#bulkCancelBtn").click(function(e) {
                e.preventDefault();
                showBulkCancel();
            });

            // Handle click events for dashboard icons
            $("#appointmentScheduleIcon").click(function(e) {
                e.preventDefault();
                showAppointments();
            });

            $("#bulkCancelIcon").click(function(e) {
                e.preventDefault();
                showBulkCancel();
            });

            // Function to show dashboard content and activate appropriate menu item
            function showDashboard() {
                $("#dashboardContent").show();
                $("#showAppointmentsContent").hide();
                $("#bulkCancelContent").hide();
                $(".nav-link").removeClass("active");
                $("#dashboardBtn").addClass("active");
            }

            // Function to show appointments content and activate appropriate menu item
            function showAppointments() {
                $("#dashboardContent").hide();
                $("#showAppointmentsContent").show();
                $("#bulkCancelContent").hide();
                $(".nav-link").removeClass("active");
                $("#appointmentScheduleBtn").addClass("active");
            }

            // Function to show bulk cancel content and activate appropriate menu item
            function showBulkCancel() {
                $("#dashboardContent").hide();
                $("#showAppointmentsContent").hide();
                $("#bulkCancelContent").show();
                $(".nav-link").removeClass("active");
                $("#bulkCancelBtn").addClass("active");
            }

            // Handle find appointments button click
            $("#findAppointmentsBtn").click(function(e) {
                e.preventDefault();
                var selectedDate = $("#datepicker").val();
                fetchAppointments(selectedDate);
            });

            // Function to fetch appointments based on date
            function fetchAppointments(date) {
                $.ajax({
                    url: "fetch_doctor_appointments.php",
                    method: "POST",
                    data: { date: date },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            var appointments = response.appointments;
                            var tableHtml = '<table class="table table-striped"><thead><tr><th>Patient Name</th><th>Appointment Date</th><th>Appointment Time</th><th>Action</th></tr></thead><tbody>';
                            appointments.forEach(function(appointment) {
                                tableHtml += '<tr>';
                                tableHtml += '<td>' + appointment.firstname.toUpperCase() + " " + appointment.lastname.toUpperCase() + '</td>';
                                tableHtml += '<td>' + appointment.appointment_date + '</td>';
                                tableHtml += '<td>' + appointment.appointment_time + '</td>';
                                tableHtml += '<td><button class="btn btn-danger btn-sm cancel-appointment" data-patient-email="' + appointment.patient_email + '" data-doctor-id="' + appointment.doctor_id + '" data-appointment-date="' + appointment.appointment_date + '" data-appointment-time="' + appointment.appointment_time + '">Cancel</button></td>';
                                tableHtml += '</tr>';
                            });
                            tableHtml += '</tbody></table>';
                            $('#appointmentsTable').html(tableHtml);
                        } else {
                            $('#appointmentsTable').html('<p>No appointments found for selected date</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching appointments:", error);
                        $('#appointmentsTable').html('<p>Error fetching appointments. Please try again later.</p>');
                    }
                });
            }

            // Handle cancel appointment button click
            $(document).on('click', '.cancel-appointment', function(e) {
                e.preventDefault();
                var patientEmail = $(this).data('patient-email');
                var doctorId = $(this).data('doctor-id');
                var appointmentDate = $(this).data('appointment-date');
                var appointmentTime = $(this).data('appointment-time');
                cancelAppointmentAjax(patientEmail, doctorId, appointmentDate, appointmentTime);
            });

            // Function to cancel appointment via AJAX
            function cancelAppointmentAjax(patientEmail, doctorId, appointmentDate, appointmentTime) {
                $.ajax({
                    url: "cancel_appointment.php",
                    method: "POST",
                    data: {
                        patient_email: patientEmail,
                        doctor_id: doctorId,
                        appointment_date: appointmentDate,
                        appointment_time: appointmentTime
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            fetchAppointments($("#datepicker").val()); // Refresh appointments table
                            alert("Appointment cancelled successfully.");
                        } else {
                            alert("Failed to cancel appointment: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error cancelling appointment:", error);
                        alert("Error cancelling appointment. Please try again later.");
                    }
                });
            }

            // Initial fetch of appointment schedule when page loads
            showDashboard(); // Show dashboard by default
        });
        

        $("#bulkCancelAppointmentsBtn").click(function(e) {
        e.preventDefault();
        var startDate = $("#startDateTimePicker").val();
        var endDate = $("#endDateTimePicker").val();
        bulkCancelAppointments(startDate, endDate);
    });

    // Function to handle bulk cancel appointments
    function bulkCancelAppointments(startDate, endDate) {
        $.ajax({
            url: "bulk_cancel_appointments.php",
            method: "POST",
            data: {
                start_date: startDate,
                end_date: endDate
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    $("#bulkCancelMessage").html('<p>' + response.message + '</p>');
                    fetchAppointments($("#datepicker").val()); // Refresh appointments table if needed
                } else {
                    $("#bulkCancelMessage").html('<p>' + response.message + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error cancelling appointments:", error);
                $("#bulkCancelMessage").html('<p>Error cancelling appointments. Please try again later.</p>');
            }
        });
    }
    </script>

</body>
</html>
