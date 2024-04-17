<?php
// Database connection
include 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted for inserting record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $date = $_POST['date'];
    $no_of_players = $_POST['no_of_players'];
    $timeslot_id = $_POST['timeslot_id'];
    $txnID = $_POST['txnID'];

    $sql = "INSERT INTO ruins_of_hampi (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES ('$name', '$email', '$mobile', '$date', '$no_of_players', '$timeslot_id', '$txnID')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
