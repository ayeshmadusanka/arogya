<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];

    // Fetch available dates and schedule IDs for the selected doctor from doctor_schedule table
    $query = "SELECT ds_id, schedule_date FROM doctor_schedule WHERE d_id = '$doctor_id' AND status = 'Active'";
    $result = mysqli_query($db, $query);

    $dates = [];
    $scheduleIDs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dates[] = $row['schedule_date'];
            $scheduleIDs[] = $row['ds_id'];
        }
    }

    // Return dates and schedule IDs as separate JSON objects
    $date_response = [
        'dates' => $dates,
        'scheduleIDs' => $scheduleIDs
    ];

    echo json_encode($date_response);
}
?>
