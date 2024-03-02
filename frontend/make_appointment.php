<!DOCTYPE html>
<html lang="en">
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
    $query = "SELECT p_name FROM patient WHERE p_id='$p_id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $p_name = $row['p_name'];
      $message = "Welcome, $p_name";
    }
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['doctor_type']) &&
        isset($_POST['selected_doctor_id']) &&
        isset($_POST['selected_date']) &&
        isset($_POST['selected_time_slot'])
    ) {
        $doctor_type = $_POST['doctor_type'];
        $selected_doctor_id = $_POST['selected_doctor_id'];
        $appointment_date = $_POST['selected_date'];
        $appointment_time_slot = $_POST['selected_time_slot'];

        // Check for duplicates
        $check_duplicate_query = "SELECT * FROM appointment WHERE p_id = '$p_id' AND d_id = '$selected_doctor_id' AND a_date = '$appointment_date' AND a_time_slot = '$appointment_time_slot'";
        $duplicate_result = $db->query($check_duplicate_query);

        if ($duplicate_result->num_rows > 0) {
            $status = "Appointment already exists for the selected doctor, date, and time slot.";
        } else {
            // Insert appointment details into the database
            $current_date_time = date("Y-m-d H:i:s");
            $insert_query = "INSERT INTO appointment (p_id, d_id, a_date, a_time_slot, a_placedt, a_user_status, a_doctor_status) VALUES ('$p_id', '$selected_doctor_id', '$appointment_date', '$appointment_time_slot', '$current_date_time', 'Active', 'Not Treated')";

            if ($db->query($insert_query) === TRUE) {
                $status = "Appointment successfully added!";
                $schedule_id = $_POST['schedule_id'];

                $update_slot_status_query = "UPDATE doctor_schedule_slots 
                SET status = 'Reserved' 
                WHERE schedule_id = '$schedule_id' 
                AND time_slot = '$appointment_time_slot'";
                $db->query($update_slot_status_query);
// Query to fetch the doctor's name based on selected doctor ID
$d_name_query = "SELECT d_name FROM doctor WHERE d_id ='$selected_doctor_id'";
$d_name_result = $db->query($d_name_query);

// Query to fetch the patient's contact based on patient ID
$p_contact_query = "SELECT p_contact FROM patient WHERE p_id='$p_id'";
$p_contact_result = $db->query($p_contact_query);

// Check if queries were successful and fetch results
if ($d_name_result && $d_name_result->num_rows > 0 && $p_contact_result && $p_contact_result->num_rows > 0) {
    $d_name_row = $d_name_result->fetch_assoc();
    $d_name = $d_name_row['d_name']; // Doctor's name

    $p_contact_row = $p_contact_result->fetch_assoc();
    $p_contact = $p_contact_row['p_contact']; // Patient's contact

    
    $sms = "Hi $p_name, your appointment with $d_name on $appointment_date at $appointment_time_slot is successfully placed";
   
    $user = "TEXTIT.BIZ USERNAME";
    $password = "TEXTIT.BIZ PW";
    $text = urlencode($sms);
    $to = $p_contact;

   $baseurl ="http://www.textit.biz/sendmsg";
   $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
   $ret = file($url);

} else {
  
}

            } else {
                $status = "Error adding appointment: " . $db->error;
            }
        }
    } else {
        $status = "Incomplete form data";
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
        
  <?php if (!empty($message)): ?>
    <div class="<?php echo strpos($message, 'successful') !== false ? 'text-success' : 'text-danger'; ?>">
      
    </div>
  <?php endif; ?>
  <br>
     
      <div class="row justify-content-center">
        <div class="col-md-6">
        <div class="appointment_form">
  <?php
  $query = "SELECT dt FROM doctor_types";
    $result = mysqli_query($db, $query);
    ?>

<form action="" method="POST">
    <div class="form-group">
        <label for="doctor_type">Select Doctor Type</label>
        <select class="form-control" id="doctor_type" name="doctor_type" required>
            <option value="" selected disabled>Select Doctor Type</option> <!-- Default prompt option -->
            <?php
            // Loop through doctor types retrieved from the database
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='".$row['dt']."'>".$row['dt']."</option>";
                }
            } else {
                echo "Error fetching data from the database";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="doctor">Select Doctor</label>
        <select class="form-control" id="doctor" name="selected_doctor_id" required>
            <!-- Options for doctors will be populated dynamically based on doctor type selection -->
        </select>
    </div>

    <div class="form-group">
        <label for="dates">Available Dates</label>
        <select class="form-control" id="dates" name="selected_date" required>
            <!-- Options for available dates will be populated dynamically based on selected doctor -->
        </select>
    </div>

    <div class="form-group">
        <label for="time_slots">Available Time Slots</label>
        <select class="form-control" id="time_slots" name="selected_time_slot" required>
            <!-- Options for available time slots will be populated dynamically based on selected date -->
        </select>
    </div>
    <input type="hidden" id="schedule_id" name="schedule_id" value="">

    <button type="submit" class="btn btn-primary">Make Appointment</button>
</form>

</div>


        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->
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
  <script type="text/javascript" src="js/custom.js">
  </script>
  <script>
// Function to fetch doctors based on selected doctor type and populate the doctors dropdown
document.getElementById('doctor_type').addEventListener('change', function() {
  var selectedDoctorType = this.value;
  var doctorDropdown = document.getElementById('doctor');

  // Clear existing options
  doctorDropdown.innerHTML = '<option value="" selected disabled>Select Doctor</option>';

  if (selectedDoctorType) {
    fetchDoctors(selectedDoctorType)
      .then(doctors => {
        if (doctors.length === 0) {
          doctorDropdown.innerHTML = '<option value="" selected disabled>No doctors available</option>';
        } else {
          doctors.forEach(doctor => {
            var option = document.createElement('option');
            option.value = doctor.d_id;
            option.textContent = doctor.d_name;
            doctorDropdown.appendChild(option);
          });
        }
      })
      .catch(error => console.error('Error fetching doctors', error));
  }
});

// Function to fetch doctors via AJAX based on selected doctor type
function fetchDoctors(doctorType) {
  // Simulate AJAX call to fetch doctors based on the selected type (replace with actual URL)
  return fetch('fetch_doctors.php?doctor_type=' + doctorType)
    .then(response => response.json())
    .catch(error => {
      console.error('Error fetching doctors', error);
      return [];
    });
}

// Event listener for doctor selection to fetch available dates
document.getElementById('doctor').addEventListener('change', function() {
  var doctorId = this.value;

  fetchAvailableDates(doctorId)
    .then(response => {
      // Process the response to populate dates dropdown
      populateDatesDropdown(response.dates);
    })
    .catch(error => {
      console.error('Error fetching dates', error);
      populateDatesDropdown([]);
    });
});
// Function to fetch available dates for the selected doctor
function fetchAvailableDates(doctorId) {
  return fetch('fetch_dates.php?doctor_id=' + doctorId)
    .then(response => response.json())
    .catch(error => {
      console.error('Error fetching dates', error);
      return [];
    });
}

// Function to populate dates dropdown
function populateDatesDropdown(dates) {
  var datesDropdown = document.getElementById('dates');
  datesDropdown.innerHTML = '<option value="" selected disabled>Select Date</option>';

  if (dates.length === 0) {
    datesDropdown.innerHTML = '<option value="" selected disabled>No dates available for the selected doctor.</option>';
  } else {
    dates.forEach(date => {
      var option = document.createElement('option');
      option.value = date;
      option.textContent = date;
      datesDropdown.appendChild(option);
    });
  }
}

function fetchScheduleAndTimeSlots(selectedDoctorId, selectedDate) {
  return fetch('fetch_time_slots.php?doctor_id=' + selectedDoctorId + '&selected_date=' + selectedDate)
    .then(response => response.json())
    .catch(error => {
      console.error('Error fetching schedule and time slots:', error);
      return { schedule_id: null, time_slots: [] };
    });
}

// Event listener for date selection to fetch available time slots
document.getElementById('dates').addEventListener('change', function() {
  var selectedDate = this.value;
  var selectedDoctorId = document.getElementById('doctor').value;

  if (selectedDate && selectedDoctorId) {
    fetchScheduleAndTimeSlots(selectedDoctorId, selectedDate)
      .then(response => {
        // Process the response to populate time slots dropdown
        populateTimeSlotsDropdown(response);
      })
      .catch(error => {
        console.error('Error fetching schedule and time slots', error);
        populateTimeSlotsDropdown([]);
      });
  }
});

// Function to populate time slots dropdown
function populateTimeSlotsDropdown(response) {
  var timeSlotsDropdown = document.getElementById('time_slots');
  timeSlotsDropdown.innerHTML = '<option value="" selected disabled>Select Time Slot</option>';

  // Extract schedule_id and time_slots from the response
  var { schedule_id, time_slots } = response;

  if (time_slots.length === 0) {
    timeSlotsDropdown.innerHTML = '<option value="" selected disabled>No time slots available for the selected date.</option>';
  } else {
    time_slots.forEach(slot => {
      var option = document.createElement('option');
      option.value = slot;
      option.textContent = slot;
      timeSlotsDropdown.appendChild(option);
    });
  }

  document.getElementById('schedule_id').value = schedule_id;
}
</script>

 

</body>

</html>