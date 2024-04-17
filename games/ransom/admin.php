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

// SQL query to select all elements from the table
$sql = "SELECT * FROM ransom";
$result = $conn->query($sql);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // You can customize how you want to display the elements
        echo "
        <style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>
        <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Date</th>
                <th>No. of Players</th>
                <th>Timeslot</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            <td> " . $row["name"] . " </td>
            <td> " . $row["email"] . " </td>
            <td> " . $row["mobile"] . " </td>
            <td> " . $row["date"] . " </td>
            <td> " . $row["no_of_players"] . " </td>
            <td> " . $row["timeslot"] . " </td>
            <td> " . $row["txnID"] . " </td>
        </tbody>
    </table>
    ";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();

?>
