<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['a_id'])) {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contact = $_POST['contact'];
    $message = $_POST['message'];

    $user = "TEXTIT.BIZ USERNAME";
    $password = "TEXTIT.BIZ PW";

    $recipient = $contact;
    $text = urlencode($message);

    $baseurl = "http://www.textit.biz/sendmsg";
    $url = "$baseurl?id=$user&pw=$password&to=$recipient&text=$text";

    $ret = file($url);
}
?>