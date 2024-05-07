<?php
// Database connection
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['date'])) {
    $date = $_GET['date'];
    $sql = "SELECT * FROM timeslots WHERE id NOT IN (SELECT timeslot_id FROM deadly_chamber WHERE date = '$date')";
    $result = $conn->query($sql);
    $timeslots = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $timeslots[] = $row;
        }
    }
    echo json_encode($timeslots);
} else {
    echo json_encode([]);
}
?>