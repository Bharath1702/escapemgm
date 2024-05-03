<?php
include './utils/db.php';   
$date = $_POST['date'];
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
if($date > $current_date){
    $sql = "SELECT id, time FROM timeslots WHERE id NOT IN (SELECT timeslot_id FROM the_nuclear_bunker WHERE date = '$date')";
}elseif($date==$current_date){
    $sql = "SELECT id, time FROM timeslots WHERE time > '$current_time' AND id NOT IN (SELECT timeslot_id FROM the_nuclear_bunker WHERE date = '$date')";
}elseif($current_date>$date){
    $row ='select proper date';
}

$result = $conn->query($sql);
$timeslots = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $timeslots[] = $row;
    }
}
echo json_encode($timeslots);
?>
