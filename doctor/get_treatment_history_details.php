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
if (!isset($_POST['appointmentId'])) {
    $response = ['success' => false, 'message' => 'Appointment ID not provided'];
    echo json_encode($response);
    exit;
}

$appointmentId = intval($_POST['appointmentId']); // Convert to integer

// Fetch existing treatment history details using SQL query
$query = "SELECT treatment_details, remarks FROM treatment_history WHERE a_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $appointmentId);
$stmt->execute();
$stmt->bind_result($treatmentDetails, $remarks);
$stmt->fetch();
$stmt->close();

// Respond with JSON
echo json_encode(['success' => true, 'data' => ['treatmentDetails' => $treatmentDetails, 'remarks' => $remarks]]);
?>