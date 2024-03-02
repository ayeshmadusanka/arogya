<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_contact = filter_var($_POST['p_contact'], FILTER_SANITIZE_NUMBER_INT);
    $p_address = filter_var($_POST['p_address'], FILTER_SANITIZE_STRING);
    $p_gender = filter_var($_POST['p_gender'], FILTER_SANITIZE_STRING);
    $p_dob = filter_var($_POST['p_dob'], FILTER_SANITIZE_STRING);
    $p_pw = $_POST['p_pw'];
    $confirm_pw = $_POST['confirm_pw'];

    // Validate input data
    if (empty($p_name) || empty($p_contact) || empty($p_address) || empty($p_gender) || empty($p_dob) || empty($p_pw) || empty($confirm_pw)) {
        $status = "Please fill in all the fields";
    } elseif (!preg_match('/^[A-Za-z. ]+$/', $p_name)) {
        $status = "Name should contain only letters and full stop";
    } elseif (!preg_match('/^\d{10}$/', $p_contact)) {
        $status = "Contact Number should be 10 digits";
    } elseif (!preg_match('/^[a-zA-Z0-9\s,]+$/', $p_address)) {
        $status = "Address format is invalid";
    } elseif ($p_pw !== $confirm_pw) {
        $status = "Passwords do not match";
    } 
    else {
        // Check if the contact number already exists in the database
        $check_query = $db->prepare("SELECT * FROM patient WHERE p_contact=?");
        $check_query->bind_param("s", $p_contact);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $status = "Patient with the same contact number already exists";
        } else {
            // Validate Date of Birth
            if (empty($p_dob)) {
                $status = "Please enter your date of birth";
            } else {
                $dobDateTime = DateTime::createFromFormat('Y-m-d', $p_dob);
                $errors = DateTime::getLastErrors();
                if ($dobDateTime && $errors['warning_count'] === 0 && $errors['error_count'] === 0) {
                    // Valid date format
                    $p_dob = $dobDateTime->format('Y-m-d');

                    // Hash the password securely
                    $p_pw = password_hash($p_pw, PASSWORD_DEFAULT);

                    // Prepare and execute SQL statement
                    $creation_date = date('Y-m-d H:i:s'); // Get current date and time
                    $sql = $db->prepare("INSERT INTO patient (p_name, p_contact, p_address, p_gender, p_dob, p_pw, p_creation_dt) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $sql->bind_param("sssssss", $p_name, $p_contact, $p_address, $p_gender, $p_dob, $p_pw, $creation_date);

                    if ($sql->execute()) {
                        $status = "Patient registered successfully";
                        $message = "Hi $p_name, your registration at Arogya was successful.";

                        $user = "TEXTIT.BIZ USERNAME";
                        $password = "TEXTIT.BIZ Pw";
                        $text = urlencode($message);
                        $to = $p_contact;

                        $baseurl = "http://www.textit.biz/sendmsg";
                        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                        $ret = file($url);
                    } else {
                        $status = "Error: " . $sql->error;
                    }
                } else {
                    $status = "Please enter a valid date in the format YYYY-MM-DD";
                }
            }
        }
        $check_query->close();
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
  <link href="css/registration.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="col-md-9" onsubmit="return validatePassword();">
    <div class="AppForm shadow-lg">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="AppFormLeft">
                    <h1>Registration</h1>
                    <br>
                    <!-- Add the common classes to form elements -->
                    <div class="form-group position-relative mb-4">
                        <input type="text" name="p_name" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="username"
                            placeholder="Patient Name" pattern="[A-Za-z. ]+" title="Name should contain only letters and full stop" required>
                        <i class="fa fa-user-o"></i>
                    </div>
                    <div class="form-group position-relative mb-4">
                        <input type="text" name="p_contact" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="email"
                            placeholder="Mobile Number" pattern="\d{10}" title="Contact Number should be 10 digits" required>
                        <i class="fa fa-mobile"></i>
                    </div>
                    <div class="form-group position-relative mb-4">
                        <input type="text" name="p_address" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="email"
                            placeholder="Address" required>
                        <i class="fa fa-home"></i>
                    </div>
                    <div class="form-group position-relative mb-4">
    <select name="p_gender" id="p_gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
    <i class="fa fa-user"></i>
</div>
<div class="form-group position-relative mb-4">
<input type="text" name="p_dob" id="p_dob" class="form-control" placeholder="Date of Birth" required>
<i class="fa fa-calendar"></i>
</div>
                    <!-- Password field -->
    <div class="form-group position-relative mb-4">
      <input type="password" name="p_pw" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="password" placeholder="Password" required>
      <i id="password-key-icon" class="fa fa-key"></i>
      <i id="password-tick-icon" class="fa fa-check" style="display: none; color: green;"></i>
      <i id="password-cross-icon" class="fa fa-times" style="display: none; color: red;"></i>
    </div>


    <!-- Confirm Password field -->
<div class="form-group position-relative mb-4">
  <input type="password" name="confirm_pw" class="form-control border-top-0 border-right-0 border-left-0 rounded-0 shadow-none" id="confirm_password" placeholder="Confirm Password" required>
  <i id="password-key-icon" class="fa fa-key"></i>
  <i id="confirm-tick-icon" class="fa fa-check" style="display: none; color: green;"></i>
  <i id="confirm-cross-icon" class="fa fa-times" style="display: none; color: red;"></i>
</div>
<div>
  <span>Create a strong password with a mix of </span><br>
  <span>Letters,Numbers and Symbols</span><br>
</div>
   <br>
                   <button type="submit" class="btn btn-success btn-block shadow border-0 py-2 text-uppercase" name="register">
                        Register
                    </button>
                    <br>
                    <p class="text-center mt-5">
                        Already have an account?
                        <span>
                            <a href="login.php">Login here</a>
                        </span>
                    </p>
                </div>
            </div>
            <!-- AppFormRight content remains the same for registration -->
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
  <script type="text/javascript" src="js/registration.js"></script>
 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <script>
    // Function to validate password on input event
    document.getElementById("password").addEventListener("input", validatePassword);
    document.getElementById("confirm_password").addEventListener("input", validatePassword);

    function validatePassword() {
        // Get the password values
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        // Regular expression patterns for password validation
        var uppercaseRegex = /[A-Z]/;
        var lowercaseRegex = /[a-z]/;
        var digitRegex = /[0-9]/;
        var specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

        // Get the icon elements for password
        var passwordKeyIcon = document.getElementById("password-key-icon");
        var passwordTickIcon = document.getElementById("password-tick-icon");
        var passwordCrossIcon = document.getElementById("password-cross-icon");

        // Get the icon elements for password confirmation
        var confirmTickIcon = document.getElementById("confirm-tick-icon");
        var confirmCrossIcon = document.getElementById("confirm-cross-icon");

        // Check if the password matches all criteria
        if (password.length < 8 || !uppercaseRegex.test(password) || !lowercaseRegex.test(password) || !digitRegex.test(password) || !specialCharRegex.test(password)) {
            // If password does not meet all criteria, display red X icon for password
            passwordKeyIcon.style.display = "inline";
            passwordTickIcon.style.display = "none";
            passwordCrossIcon.style.display = "inline";

            // Hide green tick icon for confirm password
            confirmTickIcon.style.display = "none";
        } else {
            // If password meets all criteria, display green tick icon for password
            passwordKeyIcon.style.display = "inline";
            passwordTickIcon.style.display = "inline";
            passwordCrossIcon.style.display = "none";

            // Check if password and confirm password match
            if (password !== confirmPassword) {
                // If passwords do not match, display red X icon for confirm password
                confirmTickIcon.style.display = "none";
                confirmCrossIcon.style.display = "inline";
            } else {
                // If passwords match, display green tick icon for confirm password
                confirmTickIcon.style.display = "inline";
                confirmCrossIcon.style.display = "none";
            }
        }
    }
     // Initialize jQuery UI Datepicker
  $(function() {
    $("#p_dob").datepicker({
      dateFormat: "yy-mm-dd", // Set date format as needed
      maxDate: "0", // Restrict future dates
      changeYear: true, // Show year dropdown
      yearRange: "-100:+0" // Allow selection of 100 years back from current year to current year
      // Add any additional configuration options as needed
    });
  });
</script>


</body>

</html>