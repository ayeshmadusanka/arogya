<!DOCTYPE html>
<html lang="en">
<?php
  include("../connection/connect.php");
  error_reporting(E_ALL);
  session_start();
  $status = "";
  if (isset($_SESSION['p_id'])) {
    header("Location: patient_area.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  $p_contact = $_POST['p_contact'];
  $p_pw = $_POST['p_pw']; // Password from login form

  // Validate input data
  if (empty($p_contact) || empty($p_pw)) {
    $status = "Please fill in all the fields";
  } else {
    // Prepare the SQL statement using prepared statements
    $query = "SELECT * FROM patient WHERE p_contact = ? LIMIT 1";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bind_param('s', $p_contact);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Fetch the user's data
      $row = $result->fetch_assoc();
      $hashed_pw = $row['p_pw']; // Get the hashed password from the database

      // Verify the password using password_verify()
      if (password_verify($p_pw, $hashed_pw)) {
        // Login successful
        $_SESSION['p_id'] = $row['p_id'];
        $status = "Login successful";
        // Redirect to patient_area.php
        header("Location: patient_area.php");
        exit();
      } else {
        $status = "Invalid credentials. Please try again.";
      }
    } else {
      $status = "Invalid credentials. Please try again.";
    }

    // Close statement and database connection
    $stmt->close();
    $db->close();
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

  <!-- Custom styles for this template -->
  <link href="css/login.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>
<body class="sub_page">

  <div class="hero_area">

    <div class="hero_bg_box">
      <img src="images/hero-bg.png" alt="">
    </div>

    <!-- header section strats -->
   <!-- header section -->
   <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php">
          <span>
          <img src = "images/favicon.png"  width="34" height="34">
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
    <!-- end header section -->
  </div>

  <!-- department section -->

  <section class="department_section layout_padding">
    <div class="department_container">
    <div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="col-md-9">
            <div class="AppForm shadow-lg">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div class="AppFormLeft">
                            <h1>Patient Login</h1>
                            <div class="form-group position-relative mb-4">
                                <input type="text" name="p_contact" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="username"
                                    placeholder="Contact Number" pattern="\d{10}" title="Contact Number should be 10 digits" required>
                                <i class="fa fa-user-o"></i>
                            </div>
                            <div class="form-group position-relative mb-4">
                                <input type="password" name="p_pw" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="password"
                                    placeholder="Password" required>
                                <i class="fa fa-key"></i>
                            </div>
                            <div class="row  mt-4 mb-4">
                                <div class="col-md-6 text-right">
                                    <a href="reset_password.php">Forgot Password?</a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block shadow border-0 py-2 text-uppercase " name="login">
                                Login
                            </button>
                            <br>
                            
                            <p class="text-center mt-5">
                                Don't have an account?
                                <span>
                                <a href="registration.php" style="color: #0000FF;">Create your account</a>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="AppFormRight position-relative d-flex justify-content-center flex-column align-items-center text-center p-5 text-white">
                            <h2 class="position-relative px-4 pb-3 mb-4">Welcome</h2>
                            <p>Precision in Healing Excellence in Surgery</p>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <?php if (!empty($status)): ?>
  <div class="container mt-3">
    <?php if (strpos($status, 'successfully') !== false): ?>
      <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
        <?php echo $status; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php else: ?>
      <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
        <?php echo $status; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
    </div>
</div>
      </div>
    </div>
  </section>

  <!-- end department section -->

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
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>

  <script type="text/javascript" src="js/login.js"></script>
  
 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>