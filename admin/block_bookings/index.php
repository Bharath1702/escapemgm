<?php
if($_SESSION['loggedin']==true){
    echo"<script>alert('Login Success');</script>";
}
else{
    echo"<script>alert('You cant fool me');window.location.href = './index.html';</script>";
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
