<?php
session_start();
if($_SESSION['loggedin']==true){
    echo"<script>alert('Login Success');</script>";
}
else{
    echo"<script>alert('You cant fool me');window.location.href = './index.html';</script>";
}
// Database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $date = sanitize_input($_POST['date']);
    $mobile = sanitize_input($_POST['mobile']);
    $event = sanitize_input($_POST['event']);
    $details = sanitize_input($_POST['details']);
    $qty = sanitize_input($_POST['qty']);

    $sql = "INSERT INTO events (name, email, date, mobile, event,details, qty) VALUES ('$name', '$email', '$date', '$mobile', '$event','$details', '$qty')";

    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event'])) {
    $id = sanitize_input($_POST['id']);

    $sql = "DELETE FROM events WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Event deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $id = sanitize_input($_POST['id']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $date = sanitize_input($_POST['date']);
    $mobile = sanitize_input($_POST['mobile']);
    $event = sanitize_input($_POST['event']);
    $details = sanitize_input($_POST['details']);
    $qty = sanitize_input($_POST['qty']);

    $sql = "UPDATE events SET name='$name', email='$email', date='$date', mobile='$mobile', event='$event',details='$details' qty='$qty' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Event updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
    <!-- CSS styles -->
</head>
<body>
    <h1>Event Management</h1>
    
    <!-- Add Event Form -->
    <h2>Add Event</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Date: <input type="date" name="date" required><br>
        Mobile: <input type="text" name="mobile" required><br>
        Event: <input type="text" name="event" required><br>
        Details: <input type="text" name="details" required><br>
        Quantity: <input type="number" name="qty" required><br>
        <input type="submit" name="add_event" value="Add Event">
    </form>
    
    <!-- List Events -->
    <h2>List of Events</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
            <th>Mobile</th>
            <th>Event</th>
            <th>Details</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["mobile"] . "</td>";
                echo "<td>" . $row["event"] . "</td>";
                echo "<td>" . $row["details"] . "</td>";
                echo "<td>" . $row["qty"] . "</td>";
                echo "<td>
                        <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' name='delete_event' value='Delete'>
                        </form>
                        <form action='update_event.php' method='get'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' value='Update'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No events found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
