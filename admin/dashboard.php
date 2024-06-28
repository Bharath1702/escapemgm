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
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <style>
        body{
            background-color: gold;
        }
        #cont1{
            margin-top: 2%;
            border: 2px solid black;
            height: 300px;
            padding: 20px;
            background-color: black;
            border-radius: 20px;
        }
        #cont2{
            margin-top: 2%;
            border: 2px solid black;
            height: 300px;
            border-radius: 20px;
        }
        .col{
            border: 2px solid black;
            height: 250px;
            margin-top: auto;
            margin-bottom: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            text-decoration: none;
            border-radius: 20px;
        }
    
        a{
            text-decoration: none;
            color: black;
        }
    </style>
    <body>
    <form align="right" action="logout.php"><button type="submit" placeholder="logout" style="height:50px;width:10%;">LOGOUT</button></form>
        <div class="container text-center" id="cont1">
            <div class="row row-cols-4">
                 <a href="./allbookings.php">
                    <div class="col"><h3>View All Bookings</h3></div>
                </a>
                
                <a href="./ruins_of_hampi.php">
                    <div class="col"><h3>Ruins of Hampi</h3></div>
                </a>
                
                <a href="./deadly_chamber.php">
    
                    <div class="col"><h3>Deadly Chamber</h3></div>
                </a>
                
                <a href="./killbill.php">
                    <div class="col"><h3>Killbill</h3></div>
                </a>
                
            </div>
          </div>
          <div class="container text-center" id="cont1">
            <div class="row row-cols-4">
                <a href="./nuclear_bunker.php">
                    <div class="col"><h3>the Nuclear Bunker</h3></div>
                </a>
                
                <a href="./ransom.php">
                    <div class="col"><h3>Ransom</h3></div>
                </a>
                
                <a href="timeslots.php">
                    <div class="col"><h3>Edit Timeslots</h3></div>
                </a>
                
                <a href="./contact.php">
                    <div class="col"><h3>Wants to Contact</h3></div>
                </a>
                
            </div>
          </div>
          <div class="container text-center" id="cont1">
            <div class="row row-cols-4">
                <a href="https://escapemgm.com:2083/cpsess8487825038/3rdparty/phpMyAdmin/index.php?route=/database/structure&db=escapemgm_gateways">
                    <div class="col"><h3>MYSQL</h3></div>
                </a>
                
                <a href="events.php">
                    <div class="col"><h3>Bulk Bookings</h3></div>
                </a>
                <a href="./block_bookings/index.php">
                <div class="col"><h3>manage timeslots</h3></div>
                </a>
                <a href="cart.php">
                <div class="col"><h3>view cart</h3></div>
                </a>
            </div>
          </div>
    
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
    </html>
