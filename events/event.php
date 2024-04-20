<?php
include 'db.php';

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $date = $conn->real_escape_string($_POST['date']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $event = $conn->real_escape_string($_POST['event']);
    $qty = $conn->real_escape_string($_POST['qty']);

    // Insert data into table
    $sql = "INSERT INTO events (name, email, date, mobile, event, qty) 
            VALUES ('$name', '$email', '$date', '$mobile', '$event', '$qty')";

    if ($conn->query($sql) === TRUE) {
        header("Location:index.html");
    } else {
        
    }
}

// Close connection
$conn->close();
?>
