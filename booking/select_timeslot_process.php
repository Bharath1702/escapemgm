<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gateway";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['date_id'])) {
    // AJAX request
    $dateID = $_POST['date_id'];

    // Prepare and execute query to get all booked time slots for the given date
    $stmt = $conn->prepare("SELECT timeslot FROM bookings WHERE date = ?");
    $stmt->bind_param("s", $dateID);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedTimeSlots = [];
    while ($row = $result->fetch_assoc()) {
        $bookedTimeSlots[] = $row['timeslot'];
    }

    // Prepare and execute query to get all available time slots for the given date
    $stmt2 = $conn->prepare("SELECT start_time, end_time FROM timeslots WHERE date_id = ?");
    $stmt2->bind_param("s", $dateID);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $availableTimeSlots = [];
    while ($row2 = $result2->fetch_assoc()) {
        $timeSlot = $row2['start_time'] . " - " . $row2['end_time'];
        // Check if the time slot is booked or available
        if (in_array($timeSlot, $bookedTimeSlots)) {
            // Time slot is booked
            // Do nothing, skip adding to availableTimeSlots array
        } else {
            // Time slot is available
            $availableTimeSlots[] = $timeSlot;
        }
    }

    echo json_encode($availableTimeSlots); // Send the available time slots as JSON
} else {
    // Regular form submission
    // Handle the form submission here, if needed
}
?>
