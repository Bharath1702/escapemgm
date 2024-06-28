<?php
include './utils/db.php';   
$date = $_POST['date'];
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$sql = '';
if ($date > $current_date) {
    $sql = "SELECT id, time FROM timeslots WHERE id NOT IN (SELECT timeslot_id FROM ruins_of_hampi WHERE date = ?)";
} elseif ($date == $current_date) {
    $sql = "SELECT id, time FROM timeslots WHERE time > ? AND id NOT IN (SELECT timeslot_id FROM ruins_of_hampi WHERE date = ?)";
} elseif ($current_date > $date) {
    echo json_encode(["error" => "Select a valid date"]);
    exit;
}

$timeslots = [];
if (!empty($sql)) {
    $stmt = $conn->prepare($sql);
    if ($date == $current_date) {
        $stmt->bind_param('ss', $current_time, $date);
    } else {
        $stmt->bind_param('s', $date);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $timeslots[] = $row;
        }
    }
    $stmt->close();
}

echo json_encode($timeslots);
?>
