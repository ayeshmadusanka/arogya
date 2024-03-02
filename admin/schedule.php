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
if (isset($_GET['d_id']) && !empty($_GET['d_id'])) {
    $doctor_id = $_GET['d_id'];

   
    $sql = "SELECT d_name FROM doctor WHERE d_id = $doctor_id";
    $query = mysqli_query($db, $sql);

    // Check if the query was successful
    if ($query) {
        // Fetch the data
        $row = mysqli_fetch_assoc($query);

        
        $doctor_name = $row['d_name'];
    } else {
        // Handle the case where the query fails
        echo "Error executing the query: " . mysqli_error($db);
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
    <title>Arogya | Doctor Schedule</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar">
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

                        
                        <!-- Comment -->
                        
                        <!-- End Comment -->
                      
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
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Doctors</h3> </div>
                
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        
                       
                      
                       
						
						
						     <div class="card">
                            <div class="card-body">
                            <h4 class="card-title">Doctor Schedule</h4>
                            <h6 class="card-subtitle"><?php echo $doctor_name ?></h6>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    
                                    <thead>
                                            <tr>
                                            <th>Schedule ID</th>
                    <th>Schedule Date</th>
                    <th>Available Time Start</th>
                    <th>Available Time End</th>
                    <th>Session Duration (Minutes)</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>Schedule ID</th>
                    <th>Schedule Date</th>
                    <th>Available Time Start</th>
                    <th>Available Time End</th>
                    <th>Session Duration (Minutes)</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>

                                            </tr>
                                        </tfoot>
                                        <tbody>
    <?php
    if (isset($_GET['d_id'])) {
        // Sanitize the d_id parameter to prevent SQL injection
        $d_id = mysqli_real_escape_string($db, $_GET['d_id']);
    
        // Fetch schedules for the selected doctor from the doctor_schedule table
        $sql = "SELECT * FROM doctor_schedule WHERE d_id = '$d_id'";
        $result = mysqli_query($db, $sql);

        if (!mysqli_num_rows($result) > 0) {
            echo '<tr><td colspan="7"><center>No Doctor Schedule Data Found</center></td></tr>';
        } else {
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['ds_id'] . "</td>";
                echo "<td>" . $row['schedule_date'] . "</td>";
                echo "<td>" . $row['available_time_start'] . "</td>";
                echo "<td>" . $row['available_time_end'] . "</td>";
                echo "<td>" . $row['session_duration'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>
                <a href='doctor_schedule_slots.php?ds_id=" . $row['ds_id'] . "&d_id=" . $doctor_id . "' class='btn btn-primary btn-sm'>View Slots</a>
                <button type='button' class='btn btn-info btn-sm' onclick='openStatusModal(" . $row['ds_id'] . ")'>Change Status</button>
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteSchedule(" . $row['ds_id'] . ")'>Delete</button>

              </td>";
                echo "</tr>";
            }
        }
    } else {
        echo '<tr><td colspan="8"><center>Doctor ID not provided</center></td></tr>';
    }
    ?>
</tbody>


                                    </table>
                                </div>
                            </div>
                        </div>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						 </div>
                      
                            </div>
                        </div>
                    </div>
                   <!-- Add a modal for changing status -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">Change Status</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="dsIdInput" value="">
                <label for="statusSelect">Select Status:</label>
                <select id="statusSelect" class="form-control">
                    <option value="Active">Active</option>
                    <option value="Disabled">Disabled</option>
                </select>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="reloadPage()">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateStatus()">Save changes</button>
            </div>
        </div>
    </div>
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


    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
    <script>
    function openStatusModal(dsId) {
        // Set the ds_id in the modal's hidden input field
        document.getElementById("dsIdInput").value = dsId;
        $('#changeStatusModal').modal('show'); // Show the modal
    }

    function updateStatus() {
    var status = document.getElementById("statusSelect").value;
    var dsId = document.getElementById("dsIdInput").value; // Get ds_id from the hidden input

    // AJAX request to update status
    $.ajax({
        type: 'POST',
        url: 'update_status.php', // PHP script to handle the update
        data: { ds_id: dsId, status: status },
        success: function(response) {
            // Handle success or display a message
            alert('Status updated successfully!');
            // Reload the current page after updating the status
            window.location.reload();
        },
        error: function(xhr, status, error) {
            // Handle error or display an error message
            alert('Error updating status: ' + error);
        }
    });
}
function deleteSchedule(dsId) {
    // Confirm with the user before proceeding with the deletion
    var confirmDelete = confirm('Are you sure you want to delete this schedule?');

    if (confirmDelete) {
        // AJAX request to delete the schedule
        $.ajax({
            type: 'POST',
            url: 'delete_schedule.php', // PHP script to handle the deletion
            data: { ds_id: dsId },
            dataType: 'json', // Expect JSON response
            success: function(response) {
                // Check the response status
                if (response.status === 'success') {
                    // Handle success
                    alert(response.message);
                    // Reload the current page after deleting the schedule
                    window.location.reload();
                } else {
                    // Handle error
                    alert('Error deleting schedule: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle error or display an error message
                alert('Error deleting schedule: ' + error);
            }
        });
    }
}

</script>


</body>

</html>