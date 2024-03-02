<!DOCTYPE html>
<html lang="en">
<?php
  include("../connection/connect.php");
  error_reporting(0);
  session_start();
  $status = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate new password
        if (validatePassword($new_password, $confirm_password)) {
            // Password validation passed, proceed with updating the password in the database
            $contact = $_SESSION['reset_contact'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_query = "UPDATE patient SET p_pw = ? WHERE p_contact = ?";
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bind_param('ss', $hashed_password, $contact);
            $update_stmt->execute();

            unset($_SESSION['reset_otp']);
            unset($_SESSION['reset_contact']);

            $status = "Password reset successfully!";
        } else {
            // Password validation failed
            $status = "Password validation failed. Please make sure the password has at least 8 characters including one uppercase letter,one lowercase letter,one digit and one symbol.";
        }
    }
}

function validatePassword($password, $confirmPassword) {
    // Password criteria: at least 1 uppercase, 1 lowercase, 1 symbol, 1 digit, and minimum 8 characters
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $symbol = preg_match('@[\W]@', $password);

    // Ensure passwords match
    $match = ($password == $confirmPassword);

    // Return true if all criteria are met
    return ($uppercase && $lowercase && $number && $symbol && $match && strlen($password) >= 8);
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
          <div class="col-md-6 col-lg-6">
            <h2>Patient Account Password Reset</h2>

            <!-- Form to send OTP -->
            <form method="post" id="sendOTPForm">
              <div class="form-group">
                <label for="p_contact">Enter Contact Number:</label>
                <input type="text" name="p_contact" class="form-control" pattern="\d{10}" title="Please enter a 10-digit number" required>
              </div>
              <button type="button" name="send_otp" class="btn btn-primary" onclick="sendOTP()">Send OTP</button>
            </form>

            <!-- Form to verify OTP -->
            <form method="post" id="verifyOTPForm" style="display: none;">
              <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="password" name="otp" class="form-control" pattern="\d{4}" title="Please enter a 4-digit OTP" required>
              </div>
              <button type="button" name="verify_otp" class="btn btn-primary" onclick="verifyOTP()">Verify OTP</button>
            </form>

            <!-- Form to reset password -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="resetPasswordForm" style="display: none;">
    <div class="form-group">
        <label for="new_password">Enter New Password:</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
</form>

<!-- Place these elements where you want to display the responses -->
<div id="otpResponse"></div>
<div id="otpVerificationResponse"></div>

            <!-- Display status messages -->
            <?php if (!empty($status)): ?>
              <div class="alert alert-info" role="alert">
                <?php echo $status; ?>
              </div>
            <?php endif; ?>
          </div>
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
            <h4>Reach at..</h4>
            <div class="contact_link_box">
              <a href="#">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>No. 456 Galle Road, Colombo 05, Sri Lanka</span>
              </a>
              <a href="#">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>0112580500</span>
              </a>
              <a href="#">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>info@arogyahospital.lk</span>
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

  
  <!-- JavaScript -->
  <script>
  function showOTPForm() {
    document.getElementById("sendOTPForm").style.display = "none";
    document.getElementById("verifyOTPForm").style.display = "block";
    document.getElementById("resetPasswordForm").style.display = "none";
}

function showResetPasswordForm() {
    document.getElementById("sendOTPForm").style.display = "none";
    document.getElementById("verifyOTPForm").style.display = "none";
    document.getElementById("resetPasswordForm").style.display = "block";
}

function showAlert(elementId, message, alertClass) {
    var alertDiv = document.createElement("div");
    alertDiv.className = "alert " + alertClass;
    alertDiv.setAttribute("role", "alert");
    alertDiv.innerHTML = message;
    document.getElementById(elementId).appendChild(alertDiv);

    setTimeout(function () {
        $("#" + elementId + " .alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 3000); // Adjust the duration as needed (3 seconds in this example)
}

function sendOTP() {
    var p_contact = document.getElementById("sendOTPForm").elements["p_contact"].value;

    // Perform AJAX request to send OTP
    $.ajax({
        type: "POST",
        url: "send_otp.php",
        data: { p_contact: p_contact },
        success: function (response) {
            // If the response is "success," show the verify OTP form
            if (response.trim() === "success") {
                showAlert("otpResponse", "OTP is sent to your mobile number", "alert-success");
                showOTPForm();
            } else {
                // Show an error alert
                showAlert("otpResponse", response, "alert-danger");
            }
        }
    });
}

function verifyOTP() {
    var entered_otp = document.getElementById("verifyOTPForm").elements["otp"].value;

    // Perform AJAX request to verify OTP
    $.ajax({
        type: "POST",
        url: "verify_otp.php",
        data: { entered_otp: entered_otp },
        success: function (response) {
            // If the response is "success," show the reset password form
            if (response.trim() === "success") {
                showAlert("otpVerificationResponse", "OTP successfully validated", "alert-success");
                showResetPasswordForm();
            } else {
                // Show an error alert
                showAlert("otpVerificationResponse", response, "alert-danger");
            }
        }
    });
}


</script>

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