<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(empty($_SESSION["a_id"]))
{
	header('location:index.php');
}
else
{
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
    <title>Arogya | Admin Dashboard</title>
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
                        <!-- <span><img src="images/logo.png" alt="homepage" class="dark-logo" /></span> -->
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
                    <h3 class="text-primary">Dashboard</h3> </div>
               
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                     <div class="row">
                    
                     <div class="col-md-3">
    <a href="all_patients.php">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-child f-s-40" aria-hidden="true"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>
                        <?php
                            $sql = "select * from patient";
                            $result = mysqli_query($db, $sql);
                            $rws = mysqli_num_rows($result);
                            echo $rws;
                        ?>
                    </h2>
                    <p class="m-b-0">Patients</p>
                </div>
            </div>
        </div>
    </a>
</div>       
<div class="col-md-3">
    <a href="all_doctors.php">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-user-md f-s-40" aria-hidden="true"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>
                        <?php
                            $sql = "select * from doctor";
                            $result = mysqli_query($db, $sql);
                            $rws = mysqli_num_rows($result);
                            echo $rws;
                        ?>
                    </h2>
                    <p class="m-b-0">Doctors</p>
                </div>
            </div>
        </div>
    </a>
</div>  
<div class="col-md-3">
    <a href="all_appointments.php">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-calendar-times-o f-s-40" aria-hidden="true"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>
                        <?php
                            $sql = "select * from appointment";
                            $result = mysqli_query($db, $sql);
                            $rws = mysqli_num_rows($result);
                            echo $rws;
                        ?>
                    </h2>
                    <p class="m-b-0">Appointments</p>
                </div>
            </div>
        </div>
    </a>
</div>     
<div class="col-md-3">
    <a href="all_messages.php">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-comments-o  f-s-40" aria-hidden="true"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>
                        <?php
                            $sql = "select * from contact_form";
                            $result = mysqli_query($db, $sql);
                            $rws = mysqli_num_rows($result);
                            echo $rws;
                        ?>
                    </h2>
                    <p class="m-b-0">Messages</p>
                </div>
            </div>
        </div>
    </a>
</div>         
     </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
             
        
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
<?php
}
?>