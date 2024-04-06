<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "escapemgm_gateway";
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
<style>
	select {
		width: 250px;
		padding: 8px;
	}
</style>

<div class="container">

	<div class="card bg-light" style="margin-top:50px">
		<article class="card-body mx-auto" style="max-width: 400px;">
			<h4 class="card-title mt-3 text-center">Phone pe payment Demo</h4>


			<form method="POST" action="pay.php">
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-user"></i> </span>
					</div>
					<input name="name" class="form-control" placeholder="Name" type="text">
				</div> <!-- form-group// -->
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
					</div>
					<input name="email" class="form-control" placeholder="Email address" type="email">
				</div> <!-- form-group// -->


				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-users"></i> </span>
					</div>
					<input name="date" id="date_input" class="form-control" placeholder="Select the date" type="date">
				</div>

				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa-solid fa-clock"></i> </span>
					</div>
					<select name="timeslot" id="timeslot">
					</select>
				</div>


				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-phone"></i> </span>
					</div>

					<input name="mobile" class="form-control" placeholder="Phone number" type="text">
				</div>

				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-users"></i> </span>
					</div>
					<input name="qty" class="form-control" placeholder="No. of players" type="" maxlength="1">
				</div>

				<!-- form-group// -->

				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
					</div>
					<input name="amount" class="form-control" placeholder="Create password" type="number" value="10">
				</div> <!-- form-group// -->

				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block"> Pay </button>
				</div> <!-- form-group// -->

			</form>


		</article>
	</div> <!-- card.// -->

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
</script>