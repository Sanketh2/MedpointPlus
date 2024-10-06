<?php
    include("header.php");
    include("database.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="icons/main.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        /* Adjust sidebar width and text wrapping */
        @media (max-width: 768px) {
            .list-group-item {
                white-space: normal !important; /* Ensure text wraps */
            }
        }
        /* Style for icons in sidebar */
        #sidebar .fas {
            margin-right: 10px;
        }
        #sidebar .menu-text {
            display: inline-block;
            vertical-align: middle;
            line-height: 1.5;
        }
        /* Additional styling */
        .content-container {
            padding: 20px;
        }
        .icon-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .icon-container img {
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group" id="sidebar">
                <a href="#" class="list-group-item list-group-item-action active" id="sidebar_dashboardBtn">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action" id="sidebar_viewAppointmentsBtn">
                    <i class="far fa-calendar-alt"></i>
                    <span class="menu-text">View Appointments</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action" id="sidebar_addRemoveDoctorsBtn">
                    <i class="fas fa-user-md"></i>
                    <span class="menu-text">Add/Remove Doctors</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action" id="sidebar_bulkCancelBtn">
                    <i class="fas fa-ban"></i>
                    <span class="menu-text">Bulk Cancel Appointments</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action" id="sidebar_viewPatientsBtn">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">View Patients</span>
                </a>
            </div>
        </div>
        <div class="col-md-10">
            <h2>Welcome <?php echo strtoupper($_SESSION["admin_name"]); ?></h2>
            <div id="dashboardContent">
                <h3>Dashboard</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="icon-container">
                            <a href="#" id="main_viewAppointmentsBtn">
                                <img src="icons/ShowAppointments.png" alt="Appointment Schedule">
                                <div class="icon-text">Show Appointments</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="icon-container">
                            <a href="#" id="main_addRemoveDoctorsBtn">
                                <img src="icons/addremovedoc.png" alt="Add Doctor">
                                <div class="icon-text">Add/Remove Doctors</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="icon-container">
                            <a href="#" id="main_bulkCancelBtn">
                                <img src="icons/bulkcancel.png" alt="Bulk Cancel Appointments">
                                <div class="icon-text">Bulk Cancel Appointments</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="icon-container">
                            <a href="#" id="main_viewPatientsBtn">
                                <img src="icons/viewpatients.png" alt="View Patients">
                                <div class="icon-text">View Patients</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="viewAppointmentsContent" style="display: none;">
                <h3>View Appointments</h3>
                <div class="form-group">
                    <label for="datepicker">Select Date:</label>
                    <input type="date" class="form-control" id="datepicker">
                </div>
                <button type="button" class="btn btn-primary" id="fetchAppointmentsBtn">Fetch Appointments</button>
                <div class="table-responsive mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Patient Email</th>
                                <th>Doctor Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTableBody">
                            <!-- Appointments will be loaded dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="addRemoveDoctorsContent" style="display: none;">
                <h3>Add/Remove Doctors</h3>
                <div class="row">
                    <div class="col-md-6">
                        <form id="addDoctorForm">
                            <div class="form-group">
                                <label for="doctorId">Doctor ID</label>
                                <input type="text" class="form-control" id="doctorId" name="doctorId" required>
                            </div>
                            <div class="form-group">
                                <label for="doctorName">Doctor Name</label>
                                <input type="text" class="form-control" id="doctorName" name="doctorName" required>
                            </div>
                            <div class="form-group">
                                <label for="fees">Consultation Fees</label>
                                <input type="number" class="form-control" id="fees" name="fees" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Doctor</button>
                            <div id="addDoctorMessage" class="mt-3"></div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form id="removeDoctorForm">
                            <div class="form-group">
                                <label for="doctorIdRemove">Doctor ID</label>
                                <input type="text" class="form-control" id="doctorIdRemove" name="doctorIdRemove" required>
                            </div>
                            <div class="form-group">
                                <label for="usernameRemove">Username</label>
                                <input type="text" class="form-control" id="usernameRemove" name="usernameRemove" required>
                            </div>
                            <div class="form-group">
                                <label for="adminPassword">Admin Password</label>
                                <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Remove Doctor</button>
                            <div id="removeDoctorMessage" class="mt-3"></div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="bulkCancelContent" style="display: none;">
                <h3>Bulk Cancel Appointments</h3>
                <form id="bulkCancelForm">
                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Bulk Cancel Appointments</button>
                    <div id="bulkCancelMessage" class="mt-3"></div>
                    
                </form>
            </div>

            <div id="viewPatientsContent" style="display: none;">
                <h3>View Patients</h3>
                <div class="table-responsive mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody id="patientsTableBody">
                            <!-- Patients will be loaded dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Function to load appointments via AJAX based on selected date
    function loadAppointmentsByDate(date) {
        $.ajax({
            type: "POST",
            url: "admin_get_appointments.php",
            data: { date: date },
            dataType: "json",
            success: function(response) {
                // Clear previous table rows
                $("#appointmentsTableBody").empty();

                // Populate table with appointments
                if (response && response.status === 'success') {
                    $.each(response.appointments, function(index, appointment) {
                        var row = `<tr>
                            <td>${appointment.firstname.toUpperCase() + ' ' + appointment.lastname.toUpperCase()}</td>
                            <td>${appointment.patient_email}</td>
                            <td>${appointment.doctor_name}</td>
                            <td>${appointment.appointment_date}</td>
                            <td>${appointment.appointment_time}</td>
                            <td>${appointment.status.toUpperCase()}</td>
                        </tr>`;
                        $("#appointmentsTableBody").append(row);
                    });
                } else {
                    var message = response.message || 'No appointments found for selected date';
                    var row = `<tr><td colspan="6" class="text-center">${message}</td></tr>`;
                    $("#appointmentsTableBody").append(row);
                }

                // Show appointments content
                $("#viewAppointmentsContent").show();
            },
            error: function(xhr, status, error) {
                console.error("Error loading appointments:", error);
                var errorMessage = "Error loading appointments. Please try again later.";
                $("#appointmentsTableBody").html('<tr><td colspan="6" class="text-center">' + errorMessage + '</td></tr>');
            }
        });
    }

    // Event handler for Fetch Appointments button click
    $("#fetchAppointmentsBtn").click(function() {
        var selectedDate = $("#datepicker").val();
        loadAppointmentsByDate(selectedDate);
    });

    // Event handlers for sidebar links
    $("#sidebar_dashboardBtn").click(function(e) {
        e.preventDefault();
        showDashboard();
    });

    $("#sidebar_viewAppointmentsBtn").click(function(e) {
        e.preventDefault();
        showViewAppointments();
    });

    $("#sidebar_addRemoveDoctorsBtn").click(function(e) {
        e.preventDefault();
        showAddRemoveDoctors();
    });

    $("#sidebar_bulkCancelBtn").click(function(e) {
        e.preventDefault();
        showBulkCancel();
    });

    $("#sidebar_viewPatientsBtn").click(function(e) {
        e.preventDefault();
        showViewPatients();
        fetchPatients(); // Call fetchPatients when the link is clicked
    });

    // Event handlers for main content links
    $("#main_viewAppointmentsBtn").click(function(e) {
        e.preventDefault();
        showViewAppointments();
    });

    $("#main_addRemoveDoctorsBtn").click(function(e) {
        e.preventDefault();
        showAddRemoveDoctors();
    });

    $("#main_bulkCancelBtn").click(function(e) {
        e.preventDefault();
        showBulkCancel();
    });

    $("#main_viewPatientsBtn").click(function(e) {
        e.preventDefault();
        showViewPatients();
        fetchPatients(); // Call fetchPatients when the link is clicked
    });

    // Initial function call to show default content
    showDashboard();

    // Functions to show respective content
    function showDashboard() {
        hideAllContent();
        $("#dashboardContent").show();
    }

    function showViewAppointments() {
        hideAllContent();
        $("#viewAppointmentsContent").show();
    }

    function showAddRemoveDoctors() {
        hideAllContent();
        $("#addRemoveDoctorsContent").show();
    }

    function showBulkCancel() {
        hideAllContent();
        $("#bulkCancelContent").show();
    }

    function showViewPatients() {
        hideAllContent();
        $("#viewPatientsContent").show();
        fetchPatients(); // Call fetchPatients when showing the view patients content
    }

    function hideAllContent() {
        $("#dashboardContent").hide();
        $("#viewAppointmentsContent").hide();
        $("#addRemoveDoctorsContent").hide();
        $("#bulkCancelContent").hide();
        $("#viewPatientsContent").hide();
    }

    // Form submissions (example for add doctor form)
    $("#addDoctorForm").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "admin_add_doctor.php", // Adjust path as per your file structure
            data: formData,
            success: function(response) {
                console.log("Server Response:", response);
                
                // Display response message on the webpage
                if (response.status === 'success') {
                    // Show success message
                    $("#addDoctorMessage").removeClass('text-danger').addClass('text-success').text(response.message);
                    // Optionally, update UI or reload data
                } else {
                    // Show error message
                    $("#addDoctorMessage").removeClass('text-success').addClass('text-danger').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error adding doctor:", error);
            }
        });
    });

    // Example for remove doctor form
    $("#removeDoctorForm").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "admin_remove_doctor.php",
            data: formData,
            success: function(response) {
                console.log("Server Response:", response);
                if (response.status === 'success') {
                    $("#removeDoctorMessage").removeClass('text-danger').addClass('text-success').text(response.message);
                } else {
                    $("#removeDoctorMessage").removeClass('text-success').addClass('text-danger').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error removing doctor:", error);
                $("#removeDoctorMessage").removeClass('text-success').addClass('text-danger').text("Error removing doctor. Please try again later.");
            }
        });
    });

    // Example for bulk cancel form
    $("#bulkCancelForm").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "admin_bulk_cancel.php", // Adjust path as per your file structure
            data: formData,
            success: function(response) {
                console.log("Server Response:", response);
                if (response.status === 'success') {
                    $("#bulkCancelMessage").removeClass('text-danger').addClass('text-success').text(response.message);
                } else {
                    $("#bulkCancelMessage").removeClass('text-success').addClass('text-danger').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error bulk canceling appointments:", error);
            }
        });
    });

    // Function to fetch patients
    function fetchPatients() {
        $.ajax({
            type: "GET",
            url: "admin_view_patients.php", // Adjust path as per your file structure
            dataType: "json",
            success: function(response) {
                // Clear previous table rows
                $("#patientsTableBody").empty();

                // Populate table with patients
                if (response && response.status === 'success') {
                    $.each(response.patients, function(index, patient) {
                        var row = `<tr>
                            <td>${patient.firstname}</td>
                            <td>${patient.lastname}</td>
                            <td>${patient.email}</td>
                            <td>${patient.phone}</td>
                        </tr>`;
                        $("#patientsTableBody").append(row);
                    });
                } else {
                    var message = response.message || 'No patients found';
                    var row = `<tr><td colspan="4" class="text-center">${message}</td></tr>`;
                    $("#patientsTableBody").append(row);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading patients:", error);
                var errorMessage = "Error loading patients. Please try again later.";
                $("#patientsTableBody").html('<tr><td colspan="4" class="text-center">' + errorMessage + '</td></tr>');
            }
        });
    }

});
</script>

</body>
</html>
