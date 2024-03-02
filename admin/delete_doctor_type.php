<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dtId'])) {
    $dtId = $_POST['dtId'];

    // Prepare a delete statement
    $deleteQuery = "DELETE FROM doctor_types WHERE dt_id = ?";
    $stmt = mysqli_prepare($db, $deleteQuery);

    if ($stmt) {
        // Bind the parameter and execute the statement
        mysqli_stmt_bind_param($stmt, 'i', $dtId);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Deletion successful
            echo "Record deleted successfully";
            // You might want to perform additional tasks upon successful deletion
        } else {
            // Deletion failed
            echo "Error deleting record: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        // Error in prepared statement
        echo "Prepared statement error: " . mysqli_error($db);
    }
} else {
    // Handle cases where POST data (dtId) is not set
    echo "Invalid request";
}
?>