<?php
// Include the database connection file
require_once 'db.php';

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
// Fetch data from the cart table
$sql = "SELECT id,  name, email, mobile, game, date, no_of_players, timeslot_id, amount FROM cart";
$result = $conn->query($sql);

// Check if there are records
if ($result->num_rows > 0) {
    // Start HTML table
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Mobile</th>';
    echo '<th>Game</th>';
    echo '<th>Date</th>';
    echo '<th>No. of Players</th>';
    echo '<th>Timeslot ID</th>';
    echo '<th>Amount</th>';
    echo '</tr>';
    
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['mobile'] . '</td>';
        echo '<td>' . $row['game'] . '</td>';
        echo '<td>' . $row['date'] . '</td>';
        echo '<td>' . $row['no_of_players'] . '</td>';
        echo '<td>' . $row['timeslot_id'] . '</td>';
        echo '<td>' . $row['amount'] . '</td>';
        echo '</tr>';
    }
    
    // End HTML table
    echo '</table>';
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
