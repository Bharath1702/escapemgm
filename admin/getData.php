<?php
// Database connection
include 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request is made
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM ruins_of_hampi WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Retrieve data for next 2 days
$today = date("Y-m-d");
$twoDaysLater = date("Y-m-d", strtotime("+2 days"));

$sql = "SELECT * FROM ruins_of_hampi WHERE date BETWEEN '$today' AND '$twoDaysLater'";
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
        @media only screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid #ddd;
                margin-bottom: 15px;
            }
            td {
                border: none;
                border-bottom: 1px solid #ddd;
                position: relative;
                padding-left: 50%;
            }
            td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }
            td:nth-of-type(1):before { content: "ID: "; }
            td:nth-of-type(2):before { content: "Name: "; }
            td:nth-of-type(3):before { content: "Email: "; }
            td:nth-of-type(4):before { content: "Mobile: "; }
            td:nth-of-type(5):before { content: "Date: "; }
            td:nth-of-type(6):before { content: "No. of Players: "; }
            td:nth-of-type(7):before { content: "Timeslot ID: "; }
            td:nth-of-type(8):before { content: "Transaction ID: "; }
        }
    </style>
</head>
<body>

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
                <td><?= $row['timeslot_id'] ?></td>
                <td><?= $row['txnID'] ?></td>
                <td><a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a></td> <!-- Delete button -->
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9">No records found</td></tr>
    <?php endif; ?>

    </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
