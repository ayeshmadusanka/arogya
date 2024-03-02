<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
    $patient_id = $_GET['p_id'];

 // Delete treatment history for the patient based on matching records in appointment table
 $delete_history_sql = "DELETE th FROM treatment_history th
 JOIN appointment a ON th.a_id = a.a_id
 WHERE a.p_id = ?";
 $delete_history_stmt = $db->prepare($delete_history_sql);
 $delete_history_stmt->bind_param('i', $patient_id);
 $delete_history_result = $delete_history_stmt->execute();
 
   // Retrieve appointment details before deletion
$select_appointments_sql = "SELECT a_date, a_time_slot FROM appointment WHERE p_id = ?";
$select_appointments_stmt = $db->prepare($select_appointments_sql);
$select_appointments_stmt->bind_param('i', $patient_id);
$select_appointments_stmt->execute();
$select_appointments_result = $select_appointments_stmt->get_result();

// Fetch and process the appointments
while ($row = $select_appointments_result->fetch_assoc()) {
    $appointment_date = $row['a_date'];
    $appointment_time_slot = $row['a_time_slot'];

    // Get ds_id from doctor_schedule based on schedule_date
    $get_ds_id_sql = "SELECT ds_id FROM doctor_schedule WHERE schedule_date = ?";
    $get_ds_id_stmt = $db->prepare($get_ds_id_sql);
    $get_ds_id_stmt->bind_param('s', $appointment_date);
    $get_ds_id_stmt->execute();
    $get_ds_id_result = $get_ds_id_stmt->get_result();

    if ($ds_row = $get_ds_id_result->fetch_assoc()) {
        $ds_id = $ds_row['ds_id'];

        // Update corresponding slot status in doctor_schedule_slots
        $update_schedule_sql = "UPDATE doctor_schedule_slots SET status = 'Available' WHERE schedule_id = ? AND time_slot = ?";
        $update_schedule_stmt = $db->prepare($update_schedule_sql);
        $update_schedule_stmt->bind_param('is', $ds_id, $appointment_time_slot);
        $update_schedule_stmt->execute();
    }
}

// Now, delete the appointments for the patient
$delete_appointments_sql = "DELETE FROM appointment WHERE p_id = ?";
$delete_appointments_stmt = $db->prepare($delete_appointments_sql);
$delete_appointments_stmt->bind_param('i', $patient_id);
$delete_appointments_result = $delete_appointments_stmt->execute();



    // Delete patient from patient table
    $delete_patient_sql = "DELETE FROM patient WHERE p_id = ?";
    $delete_patient_stmt = $db->prepare($delete_patient_sql);
    $delete_patient_stmt->bind_param('i', $patient_id);
    $delete_patient_result = $delete_patient_stmt->execute();

    if ($delete_patient_result && $delete_appointments_result && $delete_history_result) {
        $success = 'Patient and associated records deleted successfully.';
    } else {
        $error = 'Failed to delete patient and associated records.';
    }
} else {
    $error = 'Patient ID not provided.';
}

// Redirect back to the page where patient list is displayed, showing success or error message
if (!empty($success)) {
    header("Location: all_patients.php?success=" . urlencode($success));
} else {
    header("Location: all_patients.php?error=" . urlencode($error));
}
exit();
?>
