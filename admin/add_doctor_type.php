<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctorTypeName'])) {
    $doctorTypeName = $_POST['doctorTypeName'];

    // Sanitize the input
    $sanitizedDoctorTypeName = mysqli_real_escape_string($db, $doctorTypeName);

    $insertQuery = "INSERT INTO doctor_types (dt) VALUES (?)";
    $stmt = mysqli_prepare($db, $insertQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $sanitizedDoctorTypeName);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Doctor type added successfully";
        } else {
            echo "Error adding doctor type: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Prepared statement error: " . mysqli_error($db);
    }
} else {
    echo "Invalid request";
}
?>
