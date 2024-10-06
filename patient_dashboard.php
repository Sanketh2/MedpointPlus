<!-- patient_dashboard.php -->
<?php
session_start();
include("header.php"); //  header.php includes necessary files and starts session
include("database.php"); //  database.php sets up $pdo connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/main.ico">
    <title>Patient Dashboard</title>
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
    <style>

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
                            <a class="nav-link" href="#" id="bookAppointmentBtn"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="appointmentHistoryBtn"><i class="fas fa-history"></i> Appointment History</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area (90% width) -->
            <div class="col-md-10">
                <div class="content-container">
                    <h2>Welcome <?php echo strtoupper($_SESSION["fullname"]);?></h2>

                    <!-- Dashboard Content (Initially Visible) -->
                    <div id="dashboardContent">
                        <!-- Content for Dashboard -->
                        <div class="mt-5">
                            <h3>Dashboard</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="icon-container" id="bookAppointmentIcon">
                                        <a href="#" class="dashboard-link">
                                            <img src="icons/bookingicon.png" alt="Book now">
                                            <div class="icon-text">Make an Appointment Now</div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="icon-container" id="appointmentHistoryIcon">
                                        <a href="#" class="dashboard-link">
                                            <img src="icons/historyicon.png" alt="Booking History">
                                            <div class="icon-text">Appointment History</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Book Appointment Content (Initially Hidden) -->
                    <div id="bookAppointmentContent" style="display: none;">
                        <!-- Content for Book Appointment -->
                        <div class="mt-5">
                            <h3>Book an Appointment</h3>
                            <form id="appointmentForm">
                                <div class="form-group">
                                    <label for="doctor">Doctors</label>
                                    <select class="form-control" id="doctor" name="doctor">
                                        <?php
                                        // Fetch doctors from the database
                                        try {
                                            $stmt = $pdo->query("SELECT * FROM doctors"); // Assuming 'doctors' is your table name
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$row['doctor_id']}'>{$row['doctor_name']}</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "Error fetching doctors: " . $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="consultancyFees">Consultancy Fees</label>
                                    <input type="text" class="form-control" id="consultancyFees" name="consultancyFees" readonly>
                                    <!-- Fetch this dynamically from database using PHP -->
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                    <label for="time">Time</label>
                                    <input type="time" class="form-control" id="time" name="time" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="makeappointmentbtn">Make Appointment</button>
                            </form>
                            <!-- Appointment Booking Message -->
                            <div id="appointmentMessage" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Appointment History Content (Initially Hidden) -->
                    <div id="appointmentHistoryContent" style="display: none;">
                        <!-- Content for Appointment History -->
                        <div class="mt-5">
                            <h3>Appointment History</h3>
                            <div id="appointmentHistoryTable" class="mt-3">
                                <!-- Appointment history table will be populated dynamically -->
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
            // Handle click events for navigation buttons
            $("#dashboardBtn").click(function(e) {
                e.preventDefault();
                showDashboard();
            });

            $("#bookAppointmentBtn").click(function(e) {
                e.preventDefault();
                showBookAppointment();
            });

            $("#appointmentHistoryBtn").click(function(e) {
                e.preventDefault();
                showAppointmentHistory();
            });

            // Handle click events for dashboard icons
            $("#bookAppointmentIcon").click(function(e) {
                e.preventDefault();
                showBookAppointment();
            });

            $("#appointmentHistoryIcon").click(function(e) {
                e.preventDefault();
                showAppointmentHistory();
            });

            // Function to show dashboard content and activate appropriate menu item
            function showDashboard() {
                $("#dashboardContent").show();
                $("#bookAppointmentContent").hide();
                $("#appointmentHistoryContent").hide();
                $(".nav-link").removeClass("active");
                $("#dashboardBtn").addClass("active");
            }

            // Function to show book appointment content and activate appropriate menu item
            function showBookAppointment() {
                $("#dashboardContent").hide();
                $("#bookAppointmentContent").show();
                $("#appointmentHistoryContent").hide();
                $(".nav-link").removeClass("active");
                $("#bookAppointmentBtn").addClass("active");
            }

            // Function to show appointment history content and activate appropriate menu item
            function showAppointmentHistory() {
                $("#dashboardContent").hide();
                $("#bookAppointmentContent").hide();
                $("#appointmentHistoryContent").show();
                $(".nav-link").removeClass("active");
                $("#appointmentHistoryBtn").addClass("active");
                fetchAppointmentHistory(); // Fetch appointment history when tab is clicked
            }

            // Time restriction 9am-10pm
            $("#time").on("change", function() {
                var selectedTime = $(this).val();
                var startTime = "09:00";
                var endTime = "22:00";
            
                if (selectedTime < startTime || selectedTime > endTime) {
                    alert("Please select a time between 9:00 AM and 10:00 PM.");
                    $(this).val(""); // Clear the invalid time
                }
            });

            // AJAX request to fetch consultancy fees based on selected doctor
            $("#doctor").change(function() {
                var doctorId = $(this).val();
                $.ajax({
                    url: "fetch_consultancy_fees.php",
                    method: "POST",
                    data: { doctorId: doctorId }, // Ensure doctorId is sent correctly
                    success: function(data) {
                        $("#consultancyFees").val(data); // Update form field with returned data
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error); // Log any AJAX errors
                    }
                });
            });

            // AJAX request to handle appointment form submission
            $("#appointmentForm").submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    url: "process_appointment.php",
                    method: "POST",
                    data: formData,
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.status === 'success') {
                            // Display success message in green
                            $("#appointmentMessage").removeClass('text-danger').addClass('text-success').text(response.message);
                            fetchAppointmentHistory(); // Fetch updated appointment history after booking
                        } else {
                            // Display error message in red
                            $("#appointmentMessage").removeClass('text-success').addClass('text-danger').text(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        // Display error message in red
                        $("#appointmentMessage").removeClass('text-success').addClass('text-danger').text("Error: " + error);
                    }
                });
            });

            // Function to fetch appointment history from server
            function fetchAppointmentHistory() {
        $.ajax({
            url: "fetch_appointment_history.php",
            method: "POST",
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var appointments = response.appointments;
                    var tableHtml = '<table class="table table-striped"><thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Status</th></tr></thead><tbody>';
                    appointments.forEach(function(appointment) {
                        var status = appointment.status;
                        var statusColor = '';

                        // Determine status color based on status
                        switch (status) {
                            case 'active':
                                statusColor = 'bg-success text-white'; // Green background for active
                                break;
                            case 'inactive':
                                statusColor = 'bg-danger text-white'; // Red background for inactive
                                break;
                            case 'cancelled':
                                statusColor = 'bg-dark text-white'; // Dark background for cancelled
                                break;
                            default:
                                statusColor = ''; // Default color if status is unrecognized
                                break;
                        }

                        tableHtml += '<tr>';
                        tableHtml += '<td>' + appointment.appointment_date + '</td>';
                        tableHtml += '<td>' + appointment.appointment_time + '</td>';
                        tableHtml += '<td>' + appointment.doctor_name + '</td>';
                        tableHtml += '<td class="' + statusColor + '">' + status.toUpperCase() + '</td>'; // Assign statusColor as class
                        tableHtml += '</tr>';
                    });
                    tableHtml += '</tbody></table>';
                    $('#appointmentHistoryTable').html(tableHtml); // Update the appointment history table
                } else {
                    $('#appointmentHistoryTable').html('<p>No appointments found</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching appointment history:", error);
                $('#appointmentHistoryTable').html('<p>Error fetching appointment history. Please try again later.</p>');
            }
        });
    }

            // Initial fetch of appointment history when page loads
            fetchAppointmentHistory();
        });
    </script>

</body>
</html>
