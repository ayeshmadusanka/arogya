<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}
$success = false;
$message = '';
// Fetch doctors to populate the dropdown/select options
$sql_doctors = "SELECT d_id, d_name FROM doctor";
$result_doctors = $db->query($sql_doctors);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST["doctor"];
    $schedule_date = $_POST["schedule_date"];
    $available_time_start = $_POST["available_time_start"];
    $available_time_end = $_POST["available_time_end"];
    $session_duration = $_POST["session_duration"];

    // Check if a conflicting schedule already exists for the selected doctor, date, and available time
    $sql_check_conflict = "SELECT ds_id FROM doctor_schedule 
    WHERE d_id = '$doctor_id' 
    AND schedule_date = '$schedule_date' 
    AND status = 'Active'
    AND ((available_time_start <= '$available_time_start' AND available_time_end > '$available_time_start') 
    OR (available_time_start < '$available_time_end' AND available_time_end >= '$available_time_end'))";

    $result_check_conflict = $db->query($sql_check_conflict);

    if ($result_check_conflict->num_rows > 0) {
        // Conflicting schedule exists
        $success = false;
        $message = "Conflicting schedule already exists for this doctor, date, and time.";
    } else {
      
       
// Calculate time slots based on available time period and session duration
// Calculate time slots based on available time period and session duration
$start_time = strtotime($available_time_start);
$end_time = strtotime($available_time_end);
$session_duration_seconds = $session_duration * 60; // Convert session duration to seconds
$time_slots = [];

while ($start_time < $end_time) {
    $time_slots[] = date("H:i:s", $start_time);
    $start_time += $session_duration_seconds;

    // Handle the case where the calculated time exceeds the end time
    if ($start_time > $end_time) {
        break;
    }
}

// If the last time slot is not equal to the end time, add the end time
if (end($time_slots) != date("H:i:s", $end_time)) {
    $time_slots[] = date("H:i:s", $end_time);
}


        // Insert doctor schedule into the database
         $sql_insert = "INSERT INTO doctor_schedule (d_id, schedule_date, available_time_start, available_time_end, session_duration, status) 
                   VALUES ('$doctor_id', '$schedule_date', '$available_time_start', '$available_time_end', '$session_duration', 'Active')";

        if ($db->query($sql_insert) === TRUE) {
            $schedule_id = $db->insert_id;

            // Insert generated time slots for the schedule
            foreach ($time_slots as $time_slot) {
                $sql_insert_slots = "INSERT INTO doctor_schedule_slots (schedule_id, time_slot, status) VALUES ('$schedule_id', '$time_slot', 'Available')";
                $db->query($sql_insert_slots);
            }

            $success = true;
            $message = "Schedule added successfully";
        } else {
            $success = false;
            $message = "Error: " . $sql_insert . "<br>" . $db->error;
        }
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Arogya | Add Doctor Schedule </title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
         <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <!-- Logo icon -->
                        <b><img src="images/favicon.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <!-- <span><img src="images/logo-text.png" alt="homepage" class="dark-logo" /></span> -->
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                     
                       
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        
                      
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/5.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">                                
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Patients</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_patients.php">All Patients</a></li>
        
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-user-md"></i><span class="hide-menu">Doctors</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_doctors.php">All Doctors</a></li>
                                <li><a href="add_doctor.php">Add Doctor</a></li>
                                <li><a href="add_schedule.php">Add Doctor Schedule</a></li>
                                <li><a href="all_doctor_types.php">All Doctor Types</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-calendar-times-o"></i><span class="hide-menu">Appointments</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_appointments.php">All Appointments</a></li>
        
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">Messages</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_messages.php">All Messages</a></li>
        
                            </ul>
                        </li>
                    
                       
                         
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper" style="height:1200px;">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Doctors</h3> </div>
                
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                  
									
			

    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-header">
                <h4 class="m-b-0 text-white">Add Doctor Schedule</h4>
            </div>
            <div class="card-body">
                <br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <label for="doctor">Select Doctor:</label>
                        <select name="doctor" id="doctor" class="form-control">
                            <?php
                            if ($result_doctors->num_rows > 0) {
                                while ($row = $result_doctors->fetch_assoc()) {
                                    echo "<option value='" . $row["d_id"] . "'>" . $row["d_name"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <br>
                        <label for="schedule_date">Schedule Date:</label>
                        <input type="date" name="schedule_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        <br>
                        <label for="available_time_start">Available Time Start:</label>
                        <input type="time" name="available_time_start" class="form-control" required >
                        <br>
                        <label for="available_time_end">Available Time End:</label>
                        <input type="time" name="available_time_end" class="form-control" required >
                        <br>
                        <label for="session_duration">Session Duration (in minutes):</label>
                        <input type="number" name="session_duration" class="form-control" required min="1">
                        <br>
                        <input type="submit" value="Add Schedule" class="btn btn-primary">
                        <br>
                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                        <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    </form>
            </div>
        </div>
    </div>
					
					
					
					
					
					
					
					
					
					
					
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
            <!-- footer -->
        
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>

</body>

</html>