<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.html"); // Redirect to login page
    exit;
}

// Database connection
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request is made
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM deadly_chamber WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $date = $_POST['date'];
    $no_of_players = $_POST['no_of_players'];
    $timeslot_id = $_POST['timeslot_id'];
    $txnID = $_POST['txnID'];

    $insert_sql = "INSERT INTO deadly_chamber (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES ('$name', '$email', '$mobile', '$date', '$no_of_players', '$timeslot_id', '$txnID')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "<script>alert('Record inserted successfully');</script>";
    } else {
        echo "<script>alert('Error inserting record: " . $conn->error . "');</script>";
    }
}

// Retrieve data for next 14 days
$today = date("Y-m-d");
$twoDaysLater = date("Y-m-d", strtotime("+14 days"));

$sql = "SELECT dc.*, ts.time 
        FROM deadly_chamber dc
        JOIN timeslots ts ON dc.timeslot_id = ts.id
        WHERE dc.date BETWEEN '$today' AND '$twoDaysLater'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }

        /* Styles for overlay */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            display: none; /* Initially hidden */
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Ensure it appears above other content */
        }

        #formContainer {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
        }

        button {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<button onclick="toggleForm()">Add New Record</button>

<div id="overlay">
    <div id="formContainer">
        <h2>Add New Record</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="mobile">Mobile:</label><br>
            <input type="text" id="mobile" name="mobile" required><br><br>

            <label for="date">Date:</label><br>
            <input type="date" id="date" name="date" required><br><br>


            <label for="no_of_players">No. of Players:</label><br>
            <select type="number" name="no_of_players" placeholder="Choose the number of players" id="no_of_players" required>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
          </select><br><br>

            <label for="timeslot_id">Timeslot</label><br>
            <select name="timeslot_id" id="timeslot_id" class="form-control" required>
            <option value="1">11:00</option>
            <option value="2">12:30</option>
            <option value="3">02:00</option>
            <option value="4">03:30</option>
            <option value="5">05:00</option>
            <option value="6">06:30</option>
            <option value="7">08:00</option>
            <option value="8">09:00</option>
          </select><br><br>

            <label for="txnID">Transaction ID:</label><br>
            <input type="text" id="txnID" name="txnID" required><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Date</th>
            <th>No. of Players</th>
            <th>Timeslot</th>
            <th>Transaction ID</th>
            <th>Action</th> <!-- New column for delete button -->
        </tr>
    </thead>
    <tbody>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['mobile'] ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['no_of_players'] ?></td>
                <td><?= $row['time'] ?></td> <!-- Display timeslot time -->
                <td><?= $row['txnID'] ?></td>
                <td><a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a></td> <!-- Delete button -->
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9">No records found</td></tr>
    <?php endif; ?>

    </tbody>
</table>

<script>
    function toggleForm() {
        var overlay = document.getElementById('overlay');
        if (overlay.style.display === "none" || overlay.style.display === "") {
            overlay.style.display = "flex";
        } else {
            overlay.style.display = "none";
        }
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
