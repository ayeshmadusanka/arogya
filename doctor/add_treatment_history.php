<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['d_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Check if the required POST data is set
if (!isset($_POST['appointmentId']) || !isset($_POST['treatmentDetails'])) {
    $response = ['success' => false, 'message' => 'Incomplete data received'];
    echo json_encode($response);
    exit;
}

// Sanitize input data
$appointmentId = intval($_POST['appointmentId']);  // Convert to integer
$treatmentDetails = filter_var($_POST['treatmentDetails'], FILTER_SANITIZE_STRING);
$remarks = isset($_POST['remarks']) ? filter_var($_POST['remarks'], FILTER_SANITIZE_STRING) : ''; // Handle empty remarks

// Check if treatment history already exists for the given appointment ID
$queryCheckHistory = "SELECT * FROM treatment_history WHERE a_id = ?";
$stmtCheckHistory = $db->prepare($queryCheckHistory);
$stmtCheckHistory->bind_param("i", $appointmentId);
$stmtCheckHistory->execute();
$stmtCheckHistory->store_result();

// Check if treatment history already exists
if ($stmtCheckHistory->num_rows > 0) {
    $response = ['success' => false, 'message' => 'Treatment history already exists for the given appointment ID'];
    echo json_encode($response);
    exit;
}

// Close the statement for checking treatment history
$stmtCheckHistory->close();

// Check if the appointment has already been marked as treated
$queryCheckTreated = "SELECT a_doctor_status FROM appointment WHERE a_id = ?";
$stmtCheckTreated = $db->prepare($queryCheckTreated);
$stmtCheckTreated->bind_param("i", $appointmentId);
$stmtCheckTreated->execute();
$stmtCheckTreated->bind_result($doctorStatus);
$stmtCheckTreated->fetch();

// Check if appointment is already treated
if ($doctorStatus === 'Treated') {
    $response = ['success' => false, 'message' => 'Appointment has already been treated'];
    echo json_encode($response);
    exit;
}

// Close the statement for checking treated status
$stmtCheckTreated->close();

// Prepare and execute the SQL query to insert treatment history
$queryInsert = "INSERT INTO treatment_history (a_id, treatment_details, remarks) VALUES (?, ?, ?)";
$stmtInsert = $db->prepare($queryInsert);
$stmtInsert->bind_param("iss", $appointmentId, $treatmentDetails, $remarks);

// Check for successful insertion
if (!$stmtInsert->execute()) {
    $response = ['success' => false, 'message' => 'Error adding treatment history'];
    echo json_encode($response);
    exit;
}

// Close the prepared statement for insertion

$stmtInsert->close();

// Update a_doctor_status to "Treated" in the appointment table
$queryUpdate = "UPDATE appointment SET a_doctor_status = 'Treated' WHERE a_id = ?";
$stmtUpdate = $db->prepare($queryUpdate);
$stmtUpdate->bind_param("i", $appointmentId);

// Check for successful update
if ($stmtUpdate->execute()) {
    $response = ['success' => true, 'message' => 'Treatment history added successfully and appointment status updated'];
} else {
    $response = ['success' => false, 'message' => 'Error updating appointment status'];
}

// Close the prepared statement for update and database connection
$stmtUpdate->close();
$db->close();

// Respond with JSON
echo json_encode($response);
?>
