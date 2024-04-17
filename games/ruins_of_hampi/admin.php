<?php
// Database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functions to perform CRUD operations

// Insert data
function insertData($name, $email, $mobile, $date, $no_of_players, $timeslot_id, $txnID, $conn) {
    $sql = "INSERT INTO ruins_of_hampi (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES ('$name', '$email', '$mobile', '$date', '$no_of_players', '$timeslot_id', '$txnID')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update data
function updateData($id, $name, $email, $mobile, $date, $no_of_players, $timeslot_id, $txnID, $conn) {
    $sql = "UPDATE ruins_of_hampi SET name='$name', email='$email', mobile='$mobile', date='$date', no_of_players='$no_of_players', timeslot_id='$timeslot_id', txnID='$txnID' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete data
function deleteData($id, $conn) {
    $sql = "DELETE FROM ruins_of_hampi WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Check if form submitted for CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['insert'])) {
        insertData($_POST['name'], $_POST['email'], $_POST['mobile'], $_POST['date'], $_POST['no_of_players'], $_POST['timeslot_id'], $_POST['txnID'], $conn);
    } elseif (isset($_POST['update'])) {
        updateData($_POST['id'], $_POST['name'], $_POST['email'], $_POST['mobile'], $_POST['date'], $_POST['no_of_players'], $_POST['timeslot_id'], $_POST['txnID'], $conn);
    } elseif (isset($_POST['delete'])) {
        deleteData($_POST['id'], $conn);
    }
}

// Retrieve data for next 2 days
$today = date("Y-m-d");
$twoDaysLater = date("Y-m-d", strtotime("+2 days"));

$sql = "SELECT * FROM ruins_of_hampi WHERE date BETWEEN '$today' AND '$twoDaysLater'";
$result = $conn->query($sql);

echo "
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Date</th>
            <th>No. of Players</th>
            <th>Timeslot_id</th>
            <th>Transaction ID</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['mobile']}</td>
            <td>{$row['date']}</td>
            <td>{$row['no_of_players']}</td>
            <td>{$row['timeslot_id']}</td>
            <td>{$row['txnID']}</td>
            <td>
                <form method='post' action='admin_dashboard.php'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit' name='update'>Update</button>
                    <button type='submit' name='delete'>Delete</button>
                </form>
            </td>
        </tr>";
    }
} else {
    echo "0 results";
}

echo "
    </tbody>
</table>";

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <div id="data-container">
    <?php include 'getData.php'; ?>
  </div>

  <div id="form-container">
    <h2>Add New Record</h2>
    <form id="data-form" method="post" action="admin_dashboard.php">
      <input type="text" name="name" placeholder="Name" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="tel" name="mobile" placeholder="Mobile" required><br>
      <input type="date" name="date" required><br>
      <input type="number" name="no_of_players" placeholder="No. of Players" required><br>
      <select name="timeslot_id" required>
        <option value="">Select Timeslot</option>
        <option value="1">9:00 to 10:30</option>
        <option value="2">11:00 to 12:00</option>
        <option value="3">12:30 to 1:30</option>
        <option value="4">02:00 to 03:00</option>
        <option value="5">03:30 to 04:30</option>
        <option value="6">05:00 to 06:00</option>
      </select><br>
      <input type="text" name="txnID" placeholder="Transaction ID"><br>
      <button type="submit" name="insert">Add Record</button>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Fetch data using AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "getData.php", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var dataContainer = document.getElementById("data-container");
          dataContainer.innerHTML = xhr.responseText;
        }
      };
      xhr.send();
    });
  </script>
</body>
</html>
