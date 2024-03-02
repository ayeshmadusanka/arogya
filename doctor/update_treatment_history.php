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
if (!isset($_POST['appointmentId']) || !isset($_POST['treatmentDetails']) || !isset($_POST['remarks'])) {
    $response = ['success' => false, 'message' => 'Incomplete data received'];
    echo json_encode($response);
    exit;
}

// Sanitize input data
$appointmentId = intval($_POST['appointmentId']);  // Convert to integer
$treatmentDetails = filter_var($_POST['treatmentDetails'], FILTER_SANITIZE_STRING);
$remarks = $_POST['remarks']; // No need to sanitize remarks, it can be empty

// Check if treatment history already exists for the given appointment ID
$queryCheckHistory = "SELECT * FROM treatment_history WHERE a_id = ?";
$stmtCheckHistory = $db->prepare($queryCheckHistory);
$stmtCheckHistory->bind_param("i", $appointmentId);
$stmtCheckHistory->execute();
$stmtCheckHistory->store_result();

// Prepare and execute the SQL query to update treatment history
if ($stmtCheckHistory->num_rows > 0) {
    // Update treatment history
    $queryUpdate = "UPDATE treatment_history SET treatment_details = ?, remarks = ? WHERE a_id = ?";
    $stmtUpdate = $db->prepare($queryUpdate);
    $stmtUpdate->bind_param("ssi", $treatmentDetails, $remarks, $appointmentId);

    // Check for successful update
    if ($stmtUpdate->execute()) {
        $response = ['success' => true, 'message' => 'Treatment history updated successfully'];
    } else {
        $response = ['success' => false, 'message' => 'Error updating treatment history'];
    }

    // Close the prepared statement for update
    $stmtUpdate->close();
} else {
    // Treatment history does not exist, return an error message
    $response = ['success' => false, 'message' => 'Treatment history does not exist for the given appointment ID'];
}

// Close the statement for checking treatment history and database connection
$stmtCheckHistory->close();
$db->close();

// Respond with JSON
echo json_encode($response);
?>
