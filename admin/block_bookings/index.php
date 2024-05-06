<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location:../index.html");
  exit;
}

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
  session_unset(); // Unset all session variables
  session_destroy(); // Destroy the session
  header("Location:../index.html"); // Redirect to login page
  exit;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Form</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Booking Form</h2>
  <form id="bookingForm" method="post" action="submit_booking.php">
    <label for="date">Select Date:</label>
    <input type="date" id="date" name="date" required><br><br>
    <div id="timeSlots"></div>
    <input type="submit" value="Book">
  </form>
  <div id="bookedSlots"></div>
</div>
<script src="script.js"></script>
</body>
</html>