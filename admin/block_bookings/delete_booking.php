<?php
// Database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $timeslotId = $_POST['timeslotId'];

    $delete_sql = "DELETE FROM deadly_chamber WHERE date = '$date' AND timeslot_id = '$timeslotId'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Booking deleted successfully";
    } else {
        echo "Error deleting booking: " . $conn->error;
    }
}
?>
