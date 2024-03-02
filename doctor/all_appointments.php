<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
// Check if the user is not logged in
if (!isset($_SESSION['d_id'])) {
  header("Location: index.php"); // Redirect to login page
  exit;
  }

  $message = "";

  // Fetch the patient's name if logged in
  if (isset($_SESSION['d_id'])) {
    $d_id = $_SESSION['d_id'];

    // Fetch d_name based on the logged-in d_id
    $query = "SELECT d_name FROM doctor WHERE d_id='$d_id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $d_name = $row['d_name'];
      $message = "Welcome, Dr. $d_name";
    }
  }
?>
<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> Arogya </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <!-- DataTables Responsive CSS -->

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">
  <div class="hero_area">

    <div class="hero_bg_box">
      <img src="images/hero-bg.png" alt="">
    </div>

    <!-- header section -->
  <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php">
          <span>
            Arogya
          </span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""> </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav">
          <?php
  // Check if the user is logged in
  if (isset($_SESSION['d_id'])) {
    // If logged in, display Patient Area and Logout with icons
    echo '
      <li class="nav-item active">
        <a class="nav-link" href="../frontend/index.php"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="#department_section"><i class="fa fa-building"></i> Departments</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>
    </li>
      <li class="nav-item">
        <a class="nav-link" href="doctor_area.php"><i class="fa fa-user"></i> Doctor Area</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
      </li>
    ';
  } else {
    // If not logged in, display Patient Login with an icon
    echo '
      <li class="nav-item active">
        <a class="nav-link" href="../frontend/index.php"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="#department_section"><i class="fa fa-building"></i> Departments</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>
    </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php"><i class="fa fa-sign-in"></i> Doctor Login</a>
      </li>
    ';
  }
  ?>
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <!-- end header section -->

  <!-- contact section -->
  <section class="doctor_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
         All Appointments
        </h2>
        <p class="col-md-10 mx-auto px-0">
      <?php echo $message; ?> 
      </p>
      </div>
      <br>
      <div class="row justify-content-center">
        <div class="col-md-10">
        <div class="full-width-div">
  <?php if (!empty($message)): ?>
    <div class="<?php echo strpos($message, 'successful') !== false ? 'text-success' : 'text-danger'; ?>">
      
    </div>
  <?php endif; ?>
  <br>
  
  <table id="appointmentsTable" class="display responsive">
  <?php
// Fetch appointments data from the database
$query = "SELECT a.a_id, p.p_name, d.d_id, d.d_name, a.a_date, a.a_time_slot, a.a_placedt, a.a_user_status , a.a_doctor_status
FROM appointment a
INNER JOIN patient p ON a.p_id = p.p_id
INNER JOIN doctor d ON a.d_id = d.d_id
WHERE d.d_id = '$d_id'";
$result = $db->query($query);
?>
           <thead>
    <tr>
        <th>Appointment ID</th>
        <th>Patient Name</th>
        <th>Appointment Date</th>
        <th>Appointment Time Slot</th>
        <th>Placed Date</th>
        <th>User Status</th>
        <th>Doctor Status</th>
        <th>Action</th>

    </tr>
</thead>
<tbody>
    <?php
    // Reset the pointer after fetching the header info
    $result->data_seek(0);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['a_id'] . "</td>";
            echo "<td>" . $row['p_name'] . "</td>";
            echo "<td>" . $row['a_date'] . "</td>";
            echo "<td>" . $row['a_time_slot'] . "</td>";
            echo "<td>" . $row['a_placedt'] . "</td>";
            echo "<td>" . $row['a_user_status'] . "</td>";
            echo "<td>" . $row['a_doctor_status'] . "</td>";
           
            echo "<td>";
            echo "<button class='btn btn-info' data-toggle='modal' data-target='#treatmentModal' data-id='" . $row['a_id'] . "'>Add Treatment History</button>";
            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No appointments found for the current doctor.</td></tr>";
    }
    ?>
</tbody>
        </table>

        </div>

        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->
  <!-- Add this modal at the end of your HTML body -->
  <div class="modal fade" id="treatmentModal" tabindex="-1" role="dialog" aria-labelledby="treatmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="treatmentModalLabel">Add Treatment History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="treatmentForm">
                    <div class="form-group">
                        <label for="treatmentDetails">Treatment Details:</label>
                        <textarea class="form-control" id="treatmentDetails" name="treatmentDetails" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" ></textarea>
                    </div>
                    <input type="hidden" id="appointmentId" name="appointmentId" value="" />

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-success custom-alert d-none" id="successAlert" role="alert">
    Treatment history added successfully!
  </div>
  <div class="alert alert-danger custom-alert d-none" id="errorAlert" role="alert">
    Error adding treatment history. Please try again.
  </div>
  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_contact">
            <h4>
              Reach at..
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                 No. 456 Galle Road, Colombo 05, Sri Lanka
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                0112580500
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                info@arogyahospital.lk
                </span>
              </a>
            </div>
          </div>
        </div>
      <div class="col-md-6 col-lg-2 mx-auto footer_col">
        <div class="footer_link_box">
          <h4>Links</h4>
          <div class="footer_links">
          <?php
  if (isset($_SESSION['d_id'])) {
    // If logged in, display appropriate links with icons
    echo '<a class="active" href="../frontend/index.php"><i class="fa fa-home"></i> Home</a>';
    echo '<a href="#department_section"><i class="fa fa-building"></i> Departments</a>';
    echo '<a href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>';
    echo '<a href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>';
    echo '<a href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>';
    echo '<a class="active" href="doctor_area.php"><i class="fa fa-user"></i> Doctor Area</a>';
    echo '<a class="active" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>';
  } else {
    // If not logged in, display different links with icons
    echo '<a class="active" href="../frontend/index.php"><i class="fa fa-home"></i>  Home</a>';
    echo '<a href="#department_section"><i class="fa fa-building"></i> Departments</a>';
    echo '<a href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>';
    echo '<a href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>';
    echo '<a href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>';
    echo '<a class="active" href="index.php"><i class="fa fa-sign-in"></i>  Doctor Login</a>';
  }
  ?>
</div>

        </div>
      </div>
    </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> Arogya All Rights Reserved 
        </p>
       
      </div>  
    </div>
  </footer>

  <!-- footer section -->
  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
  $(document).ready(function () {
        // Initialize DataTable
        $('#appointmentsTable').DataTable({
      responsive: true // Enable responsive feature
    });

    // Set the appointmentId value when the modal is about to be shown
    $('#treatmentModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var appointmentId = button.data('id');
  console.log('Appointment ID:', appointmentId);
  $('#appointmentId').val(appointmentId);
});


    // Handle form submission
    $('#treatmentForm').submit(function (e) {
      e.preventDefault();

      // Use AJAX to submit the form data
      $.ajax({
        url: 'add_treatment_history.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json', // Specify the expected data type
        success: function (response) {
          // Check the response from the server
          if (response.success) {
            // Handle success
            $('#treatmentModal').modal('hide');
            $('#successAlert').removeClass('d-none');
            $('#errorAlert').addClass('d-none');

            // Optionally, reload the page after 0.5 seconds
            setTimeout(function () {
              location.reload(); // Reload the current page
            }, 500); // 500 milliseconds (0.5 seconds)
          } else {
            // Handle server-side error
            $('#errorAlert').removeClass('d-none');
            $('#successAlert').addClass('d-none');
          }
        },
        error: function () {
          // Handle other error scenarios
          $('#errorAlert').removeClass('d-none');
          $('#successAlert').addClass('d-none');
        }
      });
    });
  });
</script>
</body>

</html>