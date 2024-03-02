<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['p_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointmentId'])) {
    $appointmentId = $_POST['appointmentId'];
    $doctorId = $_POST['doctorId'];
    $appointmentDate = $_POST['appointmentDate'];
    $appointmentTime = $_POST['appointmentTime'];

    // Update the appointment status to 'Canceled' in the database
    $updateQuery = "UPDATE appointment SET a_user_status = 'Canceled' WHERE a_id = '$appointmentId'";
    $updateResult = $db->query($updateQuery);

    if ($updateResult) {
        // Update doctor's availability
        $doctorScheduleQuery = "SELECT ds_id FROM doctor_schedule WHERE d_id = '$doctorId' AND schedule_date = '$appointmentDate'";
        $doctorScheduleResult = $db->query($doctorScheduleQuery);

        if ($doctorScheduleResult->num_rows > 0) {
            $doctorScheduleRow = $doctorScheduleResult->fetch_assoc();
            $doctorScheduleId = $doctorScheduleRow['ds_id'];

            // Update doctor's schedule slots
            $doctorScheduleSlotsQuery = "UPDATE doctor_schedule_slots SET status = 'Available' WHERE schedule_id = '$doctorScheduleId' AND time_slot = '$appointmentTime'";
            $doctorScheduleSlotsResult = $db->query($doctorScheduleSlotsQuery);

            if ($doctorScheduleSlotsResult) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
