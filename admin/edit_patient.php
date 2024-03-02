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
$error = '';
$success = '';

// Initialize variables for patient details
$patient_id = $patient_name = $patient_contact = $patient_address = $patient_gender = $patient_dob = '';

// Check if the 'p_id' is set in the URL
if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
    $patient_id = $_GET['p_id'];

    // Fetch patient details based on $patient_id from the database
    $sql = "SELECT * FROM patient WHERE p_id = $patient_id";
    $query = mysqli_query($db, $sql);
    $patient_data = mysqli_fetch_assoc($query);

    // Check if the patient details are fetched
    if ($patient_data) {
        // Assign fetched data to variables
        $patient_name = $patient_data['p_name'];
        $patient_contact = $patient_data['p_contact'];
        $patient_address = $patient_data['p_address'];
        $patient_gender = $patient_data['p_gender'];
        $patient_dob = $patient_data['p_dob'];

        // Check if the form is submitted for update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_patient'])) {
            $new_name = $_POST['patient_name'];
            $new_contact = $_POST['patient_contact'];
            $new_address = $_POST['patient_address'];
            $new_gender = $_POST['patient_gender'];
            $new_dob = $_POST['patient_dob'];

            // Update the patient details in the database
            $update_sql = "UPDATE patient SET p_name = '$new_name', p_contact = '$new_contact', p_address = '$new_address', p_gender = '$new_gender', p_dob = '$new_dob' WHERE p_id = $patient_id";
            $update_query = mysqli_query($db, $update_sql);

            if ($update_query) {
                $success = '<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Patient details updated successfully.
            </div>'; 
                  
    // Fetch updated patient details from the database
    $sql = "SELECT * FROM patient WHERE p_id = $patient_id";
    $query = mysqli_query($db, $sql);
    $patient_data = mysqli_fetch_assoc($query);

    if ($patient_data) {
        // Assign updated data to variables
        $patient_name = $patient_data['p_name'];
        $patient_contact = $patient_data['p_contact'];
        $patient_address = $patient_data['p_address'];
        $patient_gender = $patient_data['p_gender'];
        $patient_dob = $patient_data['p_dob'];
    }
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Failed to update patient details.
            </div>';
            }
        }
    } else {
        // Handle if the patient details are not found
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Patient details not found!
            </div>'; 
    }
} else {
    // Handle if the 'p_id' is not provided in the URL
    header("Location: dashboard.php");
    exit();
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
    <title>Arogya | Edit Patient </title>
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
                    <h3 class="text-primary">Patients</h3> </div>
                
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                  
									
			

    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-header">
                <h4 class="m-b-0 text-white">Edit Patient</h4>
            </div>
            <div class="card-body">
                <br>
                <form action="" method="POST">
                                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">

                                <div class="form-group">
                                    <label for="patient_name">Patient Name:</label>
                                    <input type="text" id="patient_name" name="patient_name" value="<?php echo $patient_name; ?>" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="patient_contact">Contact Number:</label>
                                    <input type="text" id="patient_contact" name="patient_contact" value="<?php echo $patient_contact; ?>" class="form-control" pattern="[0-9]{10}" title="Please enter a 10 digit phone number">
                                </div>

                                <div class="form-group">
                                    <label for="patient_address">Address:</label>
                                    <input type="text" id="patient_address" name="patient_address" class="form-control" value="<?php echo $patient_address; ?>">
                                </div>

                                <div class="form-group">
    <label for="patient_gender">Gender:</label>
    <select id="patient_gender" name="patient_gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="Male" <?php if($patient_gender === 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if($patient_gender === 'Female') echo 'selected'; ?>>Female</option>
    </select>
</div>


                                <div class="form-group">
                                    <label for="patient_dob">Date of Birth:</label>
                                    <input type="date" id="patient_dob" name="patient_dob" value="<?php echo $patient_dob; ?>" class="form-control">
                                </div>

                                <button type="submit" name="update_patient" class="btn btn-primary">Update Patient</button>
                                <div class="form-group">
                                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                </div>
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