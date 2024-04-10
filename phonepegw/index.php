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
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Font Awesome CSS -->
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  }
  .container {
    max-width: 500px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  h2 {
    text-align: center;
  }
  input[type="text"],
  input[type="email"],
  input[type="date"],
  input[type="time"],
  input[type="tel"],
  input[type="number"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
  }
  input[type="submit"] {
    width: 100%;
    background-color: #4caf50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }
  input[type="submit"]:hover {
    background-color: #45a049;
  }
</style>


<div class="container">
  <h2>Reservation Form</h2>
  <form id="reservationForm" method="POST" action="pay.php">
    <label for="name">Name</label>
    <input name="name" class="form-control" placeholder="Enter your Name" type="text" required>

    <label for="email">Email ID</label>
    <input name="email" class="form-control" placeholder="Email address" type="email" required>

    <label for="date">Date</label>
    <input name="date" id="date_input" class="form-control" placeholder="Select the date" type="date" required>

    <label for="time">Timings</label>
    <select name="timeslot" id="timeslot" class="form-control" required>
                        <!-- Add options here -->
                    </select>

    <label for="phone">Phone Number</label>
    <input name="mobile" class="form-control" placeholder="Phone number" pattern="[0-9]{10}" type="text" required>

    <label for="players">Number of Players</label>

    <select name="qty" class="form-control" placeholder="Choose the number of players" id="players" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>

    <label for="amount">Total Amount</label>
    <input type="text" id="amount" name="amount" readonly>

    <button type="submit" class="btn btn-primary btn-block"> Pay </button>
  </form>
</div>
<!--container end.//-->
<script>
	document.getElementById("date_input").addEventListener("change", function(event) {
		event.preventDefault(); // Prevent the default form submission

		var selectedDate = this.value;
		var dateParts = selectedDate.split("-");
		var dateOnly = dateParts[2]; // Extracting the day part

		// alert(dateOnly);

		if (dateOnly) {
			var dateOnlyWithoutLeadingZero = parseInt(dateOnly, 10).toString(); // Remove leading zeros

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'select_timeslot_process.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						var timeslots = JSON.parse(xhr.responseText);
						// alert(xhr.responseText);

						populateTimeslots(timeslots); // Populate the timeslots in the select box
					} else {
						console.error('Error fetching timeslots.');
					}
				}
			};

			// dateOnly = 1;
			xhr.send('date_id=' + dateOnlyWithoutLeadingZero); // Send the selected date to the server
		}
	});

	function populateTimeslots(timeslots) {
		var selectElement = document.getElementById("timeslot");

		// Clear existing options
		selectElement.innerHTML = "";

		// Populate options
		timeslots.forEach(function(timeslot) {
			var option = document.createElement("option");
			option.text = timeslot;
			option.value = timeslot;
			selectElement.add(option);
		});
	}

    document.getElementById("reservationForm").addEventListener("change", function(event) {
    event.preventDefault();

    // Retrieve input values
    var players = parseInt(document.getElementById("players").value);
    var amount = players * 1000; // Assuming $10 per player

    // Display total amount
    document.getElementById("amount").value =amount;
  });
</script>