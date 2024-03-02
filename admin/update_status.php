<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ds_id']) && isset($_POST['status'])) {
        $dsId = mysqli_real_escape_string($db, $_POST['ds_id']);
        $status = mysqli_real_escape_string($db, $_POST['status']);

        $sql = "UPDATE doctor_schedule SET status = '$status' WHERE ds_id = '$dsId'";
        $result = mysqli_query($db, $sql);

        if ($result) {
            echo "Success"; // Send a success response
        } else {
            echo "Error: " . mysqli_error($db); // Send an error response
        }
    } else {
        echo "Incomplete data"; // Send an error response for missing data
    }
} else {
    echo "Invalid request"; // Send an error response for invalid request method
}
?>
