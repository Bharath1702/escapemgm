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

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add timeslot
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_timeslot'])) {
    $time = sanitize_input($_POST['time']);

    $sql = "INSERT INTO timeslots (time) VALUES ('$time')";

    if ($conn->query($sql) === TRUE) {
        echo "New timeslot added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete timeslot
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_timeslot'])) {
    $id = sanitize_input($_POST['id']);

    $sql = "DELETE FROM timeslots WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Timeslot deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update timeslot
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_timeslot'])) {
    $id = sanitize_input($_POST['id']);
    $time = sanitize_input($_POST['time']);

    $sql = "UPDATE timeslots SET time='$time' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Timeslot updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all timeslots
$sql = "SELECT * FROM timeslots";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timeslot Management</title>
    <!-- CSS styles -->
</head>
<body>
    <h1>Timeslot Management</h1>
    
    <!-- Add Timeslot Form -->
    <h2>Add Timeslot</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Time: <input type="text" name="time" required><br>
        <input type="submit" name="add_timeslot" value="Add Timeslot">
    </form>
    
    <!-- List Timeslots -->
    <h2>List of Timeslots</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["time"] . "</td>";
                echo "<td>
                        <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' name='delete_timeslot' value='Delete'>
                        </form>
                        <form action='update_timeslot.php' method='get'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' value='Update'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No timeslots found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();