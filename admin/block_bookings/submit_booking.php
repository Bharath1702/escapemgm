<?php
// Database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $date = $_POST['date'];
    $timeslot_ids = $_POST['timeslot_id'];
    $txnid = $_POST['txnid'];

    // Loop through selected time slots and insert into database
    foreach ($timeslot_ids as $timeslot_id) {
        $insert_sql = "INSERT INTO deadly_chamber (name, email, mobile, date, timeslot_id, txnid) VALUES ('$name', '$email', '$phno', '$date', '$timeslot_id', '$txnid')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>alert('Blocked successfully');</script>";
            
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>
