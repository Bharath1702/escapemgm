<?php

session_start();
if(isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount']) && isset($_SESSION['transactionId']) && isset($_SESSION['tran_id'])) {
    // Retrieve session variables
    $name     = $_SESSION['name'];
    $email    = $_SESSION['email'];
    $date     = $_SESSION['date'];
    $timeslot = $_SESSION['timeslot'];
    $mobile   = $_SESSION['mobile'];
    $qty      = $_SESSION['qty'];
    $amount   = $_SESSION['amount'];
    $transactionId = $_SESSION['transactionId'];
    $tran_id = $_SESSION['tran_id'];
}
?>
<html>
<head>
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
</head>
<style>
  body {
    text-align: center;
    padding: 40px 0;
    background: #EBF0F5;
  }

  h1 {
    color: red;
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-weight: 900;
    font-size: 40px;
    margin-bottom: 10px;
  }

  p {
    color: #404F5E;
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-size: 20px;
    margin: 0;
  }

  i {
    color: #9ABC66;
    font-size: 100px;
    line-height: 200px;
    margin-left: -15px;
  }

  .card {
    background: white;
    padding: 60px;
    border-radius: 4px;
    box-shadow: 0 2px 3px #C8D0D8;
    display: inline-block;
    margin: 0 auto;
  }
</style>

<body>
  <div class="card">
    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
      <i class="checkmark">✓</i>
    </div>

    <h1>Success</h1>
    <p>Name: <?php echo $name; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Date: <?php echo $date; ?></p>
    <p>Timeslot: <?php echo $timeslot; ?></p>
    <p>Mobile: <?php echo $mobile; ?></p>
    <p>No of Players: <?php echo $qty; ?></p>
    <p>Amount paid : <?php echo $amount; ?></p>
    <p>Transaction ID: <?php echo $tran_id ?></p>
    <p>We received your purchase request;<br /> we'll be in touch shortly!</p>

  </div>
</body>
<?php
    include "./utils/db.php";
    $stmt = mysqli_prepare($conn, "INSERT INTO the_nuclear_bunker (name,email,mobile, date, no_of_players, timeslot_id,txnID) VALUES (?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("sssssss", $name,$email,$mobile, $date, $qty, $timeslot,$tran_id );
    if ($stmt->execute()) {
        echo "<h1> Booking Successfull </h1>";
    } else {
        echo "<h1> Booking Failed </h1>";
        exit;
    }
session_unset();
session_destroy();
?>

</html>