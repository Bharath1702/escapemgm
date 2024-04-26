<?php
include './utils/db.php';
$date = $_POST['date'];
$sql = "SELECT id, time FROM timeslots WHERE id NOT IN (SELECT timeslot_id FROM ransom WHERE date = '$date')";
$result = $conn->query($sql);
$timeslots = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $timeslots[] = $row;
    }
}
echo json_encode($timeslots);
?>
