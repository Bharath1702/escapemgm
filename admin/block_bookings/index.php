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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: block;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <center>
    <form action="indblock.php" method="POST">
    <center><h1>Block Individual</h1></center>
    <label for="fromdate">From Date:</label>
    <input type="date" name="fromdate" id="date">
    <label for="todate">To Date:</label>
    <input type="date" name="todate" id="date">
    <label for="game">Game:</label>
    <select name="game" id="game">
        <option value="deadly_chamber">Deadly Chamber</option>
        <option value="the_nuclear_bunker">The Nuclear Bunker</option>
        <option value="ruins_of_hampi">Ruins of Hampi</option>
        <option value="ransom">Ransom</option>
        <option value="killbill">Killbill</option>
    </select>
    <input type="submit" name="submit" id="submit" value="Block">
</form>
<form action="indunblock.php" method="POST">
    <center><h1>Unblock Individual</h1></center>
    <label for="fromdate">From Date:</label>
    <input type="date" name="fromdate" id="date">
    <label for="todate">To Date:</label>
    <input type="date" name="todate" id="date">
    <label for="game">Game:</label>
    <select name="game" id="game">
        <option value="deadly_chamber">Deadly Chamber</option>
        <option value="the_nuclear_bunker">The Nuclear Bunker</option>
        <option value="ruins_of_hampi">Ruins of Hampi</option>
        <option value="ransom">Ransom</option>
        <option value="killbill">Killbill</option>
    </select>
    <input type="submit" name="submit" id="submit" value="Unblock">
</form>
 <br><br><br>   


<div>
<form action="block.php" method="POST">
<center>
    <h1>Block All Game Bookings</h1>
</center>
    <label for="fromdate">From Date:</label>
    <input type="date" name="fromdate" id="date">
    <label for="todate">To Date:</label>
    <input type="date" name="todate" id="date">
    <input type="submit" name="submit" id="submit" value="Block">
</form>


<form action="unblock.php" method="POST">
<center>
    <h1>Unblock All Game Bookings</h1>
</center>
    <label for="fromdate">From Date:</label>
    <input type="date" name="fromdate" id="date">
    <label for="todate">To Date:</label>
    <input type="date" name="todate" id="date">
    <input type="submit" name="submit" id="submit" value="Unblock">
</form>
</div>
    </center>


</body>
</html>
