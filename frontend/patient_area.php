<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['p_id'])) {
  header("Location: login.php"); // Redirect to login page
  exit;
}

$message = "";

// Fetch the patient's name if logged in
if (isset($_SESSION['p_id'])) {
  $p_id = $_SESSION['p_id'];

  // Fetch p_name based on the logged-in p_id
  $query = "SELECT p_name FROM patient WHERE p_id = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param('s', $p_id);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($p_name);
    $stmt->fetch();
    $message = "Welcome, $p_name";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

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

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/patient_area.css" rel="stylesheet" />
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
              <img src="images/favicon.png" width="34" height="34">
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
              if (isset($_SESSION['p_id'])) {
                // If logged in, display Patient Area and Logout with icons
                echo '
                  <li class="nav-item active">
                    <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
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
                    <a class="nav-link" href="patient_area.php"><i class="fa fa-user"></i> Patient Area</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                  </li>
                ';
              } else {
                // If not logged in, display Patient Login with an icon
                echo '
                  <li class="nav-item active">
                    <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
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
                    <a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Patient Login</a>
                  </li>
                ';
              }
              ?>
            </ul>
          </div>
        </nav>
      </div>
    </header>

    <section class="doctor_section layout_padding">
      <div class="container">
        <div class="heading_container heading_center">
          <h2>
            Patient Area
          </h2>
          <p class="col-md-10 mx-auto px-0">
            <?php echo $message; ?> <a href="edit_details.php" class="edit-icon" style="color: white;"><i class="fa fa-cog"></i></a>
          </p>
        </div>
        <div class="row">
          <div class="col-sm-6 col-lg-4 mx-auto">
            <a href="make_appointment.php" class="box-link">
              <div class="box">
                <div class="img-box">
                  <img src="images/app1.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Make Appointment
                  </h5>
                  <h6 class="">
                    Schedule a new appointment
                  </h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-lg-4 mx-auto">
            <a href="all_appointments.php" class="box-link">
              <div class="box">
                <div class="img-box">
                  <img src="images/app2.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    All Appointments
                  </h5>
                  <h6 class="">
                    View all scheduled appointments
                  </h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-lg-4 mx-auto">
            <a href="treatment_history.php" class="box-link">
              <div class="box">
                <div class="img-box">
                  <img src="images/app3.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Treatment History
                  </h5>
                  <h6 class="">
                    Check my treatment history
                  </h6>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>

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
                if (isset($_SESSION['p_id'])) {
                  // If logged in, display appropriate links with icons
                  echo '<a class="active" href="index.php"><i class="fa fa-home"></i> Home</a>';
                  echo '<a href="#department_section"><i class="fa fa-building"></i> Departments</a>';
                  echo '<a href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>';
                  echo '<a href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>';
                  echo '<a href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>';
                  echo '<a class="active" href="patient_area.php"><i class="fa fa-user"></i> Patient Area</a>';
                  echo '<a class="active" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>';
                } else {
                  // If not logged in, display different links with icons
                  echo '<a class="active" href="index.php"><i class="fa fa-home"></i>  Home</a>';
                  echo '<a href="#department_section"><i class="fa fa-building"></i> Departments</a>';
                  echo '<a href="#about_section"><i class="fa fa-info-circle"></i> About Us</a>';
                  echo '<a href="#doctor_section"><i class="fa fa-user-md"></i> Our Doctors</a>';
                  echo '<a href="#contact_section"><i class="fa fa-envelope"></i> Contact</a>';
                  echo '<a class="active" href="login.php"><i class="fa fa-sign-in"></i>  Patient Login</a>';
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
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
