<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Function to generate OTP
function generateOTP()
{
    return sprintf('%04d', rand(1000, 9999));
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is set
    if (isset($_POST['p_contact'])) {
        $p_contact = $_POST['p_contact'];

        // Validate input data (10-digit number)
        if (is_numeric($p_contact) && strlen($p_contact) == 10) {
            // Check if the user exists in the database
            $check_query = "SELECT * FROM patient WHERE p_contact = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bind_param('s', $p_contact);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                // User exists, generate OTP
                $otp = generateOTP();
                $message = "Your OTP for Arogya password reset: $otp";
                $user = "TEXTIT.BIZ USERNAME";
                $password = "TEXTIT.BIZ Pw";
                $text = urlencode($message);
                $to = $p_contact;

                $baseurl = "http://www.textit.biz/sendmsg";
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                $ret = file($url);

                // Check if SMS sending was successful
                if ($ret !== false) {
                    // Store the OTP and its expiration time in the session
                    $_SESSION['reset_otp'] = $otp;
                    $_SESSION['reset_contact'] = $p_contact;
                    $_SESSION['otp_expiration_time'] = time() + 300; // Set a timeout of 5 minutes (adjust as needed)

                    echo "success";
                } else {
                    echo "Failed to Send the OTP"; // Failed to send SMS
                }
            } else {
                echo "User does not exist"; // User does not exist in the database
            }
        } else {
            echo "Invalid Contact Number. Please enter a 10-digit number."; // Invalid contact number
        }
    } else {
        echo "Please Enter a Valid Contact Number"; // Missing contact number
    }
} else {
    echo "Error! Invalid Request"; // Invalid request method
}
?>
