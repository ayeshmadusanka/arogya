<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dsId = mysqli_real_escape_string($db, $_POST['ds_id']);

    // Check if there are reserved slots for the selected schedule
    $sqlCheckReservedSlots = "SELECT COUNT(*) as count FROM doctor_schedule_slots WHERE schedule_id = '$dsId' AND status = 'Reserved'";
    $resultCheckReservedSlots = mysqli_query($db, $sqlCheckReservedSlots);

    if ($resultCheckReservedSlots) {
        $row = mysqli_fetch_assoc($resultCheckReservedSlots);
        $reservedSlotsCount = $row['count'];

        if ($reservedSlotsCount > 0) {
            // There are reserved slots, set an error message in the response
            $response['status'] = 'error';
            $response['message'] = 'Cannot delete schedule. There are reserved slots.';
        } else {
            // Start a transaction for atomicity
            mysqli_begin_transaction($db);

            try {
                // Delete from doctor_schedule
                $sqlDeleteSchedule = "DELETE FROM doctor_schedule WHERE ds_id = '$dsId'";
                $resultDeleteSchedule = mysqli_query($db, $sqlDeleteSchedule);

                if (!$resultDeleteSchedule) {
                    throw new Exception(mysqli_error($db));
                }

                // Delete from doctor_schedule_slots
                $sqlDeleteSlots = "DELETE FROM doctor_schedule_slots WHERE schedule_id = '$dsId'";
                $resultDeleteSlots = mysqli_query($db, $sqlDeleteSlots);

                if (!$resultDeleteSlots) {
                    throw new Exception(mysqli_error($db));
                }

                // Commit the transaction if all queries are successful
                mysqli_commit($db);

                // Set a success message in the response
                $response['status'] = 'success';
                $response['message'] = 'Schedule deleted successfully!';
            } catch (Exception $e) {
                // Rollback the transaction if any query fails
                mysqli_rollback($db);

                // Set an error message in the response
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $e->getMessage();
            }
        }
    } else {
        // Handle the case where the query to check reserved slots fails
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . mysqli_error($db);
    }
}

// Send the JSON response
echo json_encode($response);
?>
