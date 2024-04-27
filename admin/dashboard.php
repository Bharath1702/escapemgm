<?php
session_start();
if($_SESSION['loggedin']=true){
    echo'<!DOCTYPE html>
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
                <a href="./ruins_of_hampi.php">
                    <div class="col"><h3>Ruins of hampi</h3></div>
                </a>
                <a href="./deadly_chamber.php">
    
                    <div class="col"><h3>deadly chamber</h3></div>
                </a>
                <a href="./killbill.php">
                    <div class="col"><h3>killbill</h3></div>
    
                </a>
                <a href="./ransom.php">
    
                    <div class="col"><h3>ransom</h3></div>
                </a>
            </div>
          </div>
          <div class="container text-center" id="cont1">
            <div class="row row-cols-4">
                <a href="./nuclear_bunker.php">
                    <div class="col"><h3>the Nuclear bunker</h3></div>
                </a>
                <a href="timeslots.php">
                    <div class="col"><h3>Edit timeslots</h3></div>
                </a>
                <a href="./contact.php">
                    <div class="col"><h3>Wants to contact</h3></div>
    
                </a>
                <a href="events.php">
                    <div class="col"><h3>Bulk bookings</h3></div>
                </a>
            </div>
          </div>
    
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
    </html>';
}else{
    echo"<script>alert('You cant fool me');window.location.href = './index.html';</script>";
}
?>
