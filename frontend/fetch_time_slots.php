<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_GET['doctor_id']) && isset($_GET['selected_date'])) {
    $doctor_id = $_GET['doctor_id'];
    $selected_date = $_GET['selected_date'];

    // Fetch schedule_id and available time slots for the selected doctor and date from doctor_schedule and doctor_schedule_slots tables
    $query = "SELECT ds.ds_id, dss.time_slot 
          FROM doctor_schedule AS ds 
          INNER JOIN doctor_schedule_slots AS dss ON ds.ds_id = dss.schedule_id 
          WHERE ds.d_id = '$doctor_id' 
          AND ds.schedule_date = '$selected_date'
          AND dss.status = 'Available'
          AND ds.status = 'Active'";


    $result = mysqli_query($db, $query);

    $schedule_id = null;
    $time_slots = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $schedule_id = $row['ds_id']; // Fetching schedule_id
            $time_slots[] = $row['time_slot']; // Collecting time_slots
        }
    }

    // Create an associative array containing both schedule_id and time_slots
    $response = [
        'schedule_id' => $schedule_id,
        'time_slots' => $time_slots
    ];

    // Return schedule_id and time_slots as a single JSON object
    echo json_encode($response);
}
?>
