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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d_name = mysqli_real_escape_string($db, $_POST['d_name']);
    $d_contact = mysqli_real_escape_string($db, $_POST['d_contact']);
    $d_address = mysqli_real_escape_string($db, $_POST['d_address']);
    $d_gender = $_POST['d_gender'];
    $d_dob = $_POST['d_dob'];
    $d_type = $_POST['d_type'];
    $d_fees = $_POST['d_fees'];
    $d_pw = $_POST['d_pw'];

    // Server-side validation
    if (empty($d_name) || empty($d_contact) || empty($d_address) || empty($d_gender) || empty($d_dob) || empty($d_type) || empty($d_fees) || empty($d_pw)) {
        $error = "All fields are required!";
    } elseif (!preg_match("/^[A-Za-z. ]+$/", $d_name)) {
        $error = "Name should contain only letters and a full stop.";
    } elseif (!preg_match("/^\d{10}$/", $d_contact)) {
        $error = "Contact number should be 10 digits.";
    } elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $d_fees)) {
        $error = "Consultancy Fees should be a valid number.";
    } elseif (strlen($d_pw) < 8) {
        $error = "Password should have at least 8 characters!";
    } elseif (!preg_match("/[A-Z]/", $d_pw)) {
        $error = "Password should contain at least one uppercase letter!";
    } elseif (!preg_match("/[a-z]/", $d_pw)) {
        $error = "Password should contain at least one lowercase letter!";
    } elseif (!preg_match("/\d/", $d_pw)) {
        $error = "Password should contain at least one digit!";
    } elseif (!preg_match("/[^A-Za-z\d]/", $d_pw)) {
        $error = "Password should contain at least one symbol!";
    } else {
        // Check if the contact number already exists in the database using prepared statements
        $check_query = "SELECT * FROM doctor WHERE d_contact=?";
        $stmt = mysqli_prepare($db, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $d_contact);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Doctor with the same contact number already exists!
        </div>';
        } else {
            // Insert data into the database using prepared statements
            
            $insert_query = "INSERT INTO doctor (d_name, d_contact, d_address, d_gender, d_dob, d_type, d_fees, d_pw, d_creation_dt, d_updated_dt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $creation_date = date('Y-m-d H:i:s');
            $updated_date = date('Y-m-d H:i:s');
            $stmt = mysqli_prepare($db, $insert_query);
            $hashed_pw = password_hash($d_pw, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssssdsss", $d_name, $d_contact, $d_address, $d_gender, $d_dob, $d_type, $d_fees, $hashed_pw, $creation_date, $updated_date);

            if (mysqli_stmt_execute($stmt)) {
                $success = '<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Doctor added successfully.
            </div>';

            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Error adding doctor: ' . mysqli_error($db) . '
            </div>';
            }
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
    <title>Arogya | Add Doctor </title>
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
                <h4 class="m-b-0 text-white">Add Doctor</h4>
            </div>
            <div class="card-body">
                <br>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
    <label for="d_name">Doctor Name</label>
    <input type="text" class="form-control" id="d_name" name="d_name" pattern="[A-Za-z. ]+" title="Name should contain only letters and full stop" required>
  </div>

  <div class="form-group">
    <label for="d_contact">Contact Number</label>
    <input type="text" class="form-control" id="d_contact" name="d_contact" pattern="\d{10}" title="Contact number should be 10 digits" required>
  </div>

      <div class="form-group">
        <label for="d_address">Address</label>
        <input type="text" class="form-control" id="d_address" name="d_address" required>
      </div>

      <div class="form-group">
    <label for="d_gender">Gender</label>
    <select class="form-control" id="d_gender" name="d_gender" required>
        <option value="" disabled selected>Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
</div>

      <div class="form-group">
        <label for="d_dob">Date of Birth</label>
        <input type="date" class="form-control" id="d_dob" name="d_dob" required>
      </div>

      <div class="form-group">
    <label for="d_type">Doctor Type</label>
    <select class="form-control" id="d_type" name="d_type" required>
        <option value="" disabled selected>Select Doctor Type</option>
        <?php
        $query = "SELECT dt FROM doctor_types";
        $result = mysqli_query($db, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['dt']."'>".$row['dt']."</option>";
            }
        } else {
            echo "<option value=''>Error fetching data</option>";
        }
        ?>
    </select>
</div>


      <div class="form-group">
  <label for="d_fees">Consultancy Fees (LKR)</label>
  <input type="text" class="form-control" id="d_fees" name="d_fees" pattern="^\d+(\.\d{1,2})?$" title="Consultancy Fees should be a valid number" required>
</div>

      <div class="form-group">
        <label for="d_pw">Password</label>
        <input type="password" class="form-control" id="d_pw" name="d_pw" required>
      </div>

      <button type="submit" class="btn btn-primary">Add Doctor</button>
      <?php echo $error; ?>
    <?php echo $success; ?>
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