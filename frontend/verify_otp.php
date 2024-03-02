<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Function to validate OTP
function validateOTP($entered_otp)
{
    // Validate entered OTP
    if (!empty($entered_otp) && is_numeric($entered_otp) && strlen($entered_otp) == 4) {
        // Check if OTP has expired
        if (isset($_SESSION['reset_otp']) && isset($_SESSION['otp_expiration_time'])) {
            $current_time = time();
            $expiration_time = $_SESSION['otp_expiration_time'];

            if ($current_time <= $expiration_time) {
                // Check if the entered OTP is correct
                return ($entered_otp == $_SESSION['reset_otp']) ? "success" : "Incorrect OTP. Please try again.";
            } else {
                return "OTP has expired. Please request a new one.";
            }
        } else {
            return "OTP validation failed. Please request a new one.";
        }
    } else {
        return "Invalid OTP format. Please enter a 4-digit OTP.";
    }
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is set
    if (isset($_POST['entered_otp'])) {
        // Get the entered OTP
        $entered_otp = $_POST['entered_otp'];

        // Validate OTP
        $validation_result = validateOTP($entered_otp);

        echo $validation_result;
    } else {
        echo "Please Enter the OTP"; // Missing entered OTP
    }
} else {
    echo "Error! Invalid Request"; // Invalid request method
}
?>
