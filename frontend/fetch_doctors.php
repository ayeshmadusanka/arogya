<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['doctor_type'])) {
    $selectedDoctorType = $_GET['doctor_type'];

    // Fetch doctors based on the selected doctor type
    $query = "SELECT d_id, d_name FROM doctor WHERE d_type = '$selectedDoctorType'";
    $result = mysqli_query($db, $query);

    $doctors = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $doctors[] = $row;
        }
        echo json_encode($doctors);
    } else {
        echo json_encode(array('error' => 'Error fetching doctors'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
