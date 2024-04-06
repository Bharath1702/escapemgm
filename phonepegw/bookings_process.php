<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "escapemgm_gateway";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $timeslot = $_POST['timeslot'];
    $mobile = $_POST['mobile'];
    $qty = $_POST['qty'];
    $amount = $_POST['amount'];


    if (!isset($name)) {
        echo "Please Confirm Your Name";
        exit;
    } else if (!isset($email)) {
        echo "Please Confirm Your Email";
        exit;
    } else if (!isset($date)) {
        echo "Please Select Your Booking Date";
        exit;
    } else if (!isset($timeslot)) {
        echo "Please Select Your Booking Time";
        exit;
    } else if (!isset($mobile)) {
        echo "Please Select Your Valid Mobile";
        exit;
    } else if (!isset($qty)) {
        echo "Please Select Your Quantity";
        exit;
    } else if (!isset($amount)) {
        echo "Please Select Your Amount";
        exit;
    }


    $stmt = mysqli_prepare($onn, "INSERT INTO bookings (name, date, number_of_players, timeslot) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $date, $qty, $timeslot);
    if ($stmt->execute()) {
        echo "<h1> Booking Successfull </h1>";
    } else {
        echo "<h1> Booking Failed </h1>";
        exit;
    }
}
