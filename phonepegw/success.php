<?php 

session_start();

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
    <p>Transaction ID : <?php echo $_GET['tid']; ?></p>
    <p>Amount : <?php echo $_GET['amount'] / 100; ?></p>
    <p>We received your purchase request;<br /> we'll be in touch shortly!</p>

  </div>
</body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "escapemgm_gateway";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //  echo $_SESSION['name'];

    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $date = $_SESSION['date'];
    $timeslot = $_SESSION['timeslot'];
    $mobile = $_SESSION['mobile'];
    $qty = $_SESSION['qty'];
    $amount = $_SESSION['amount'];

$date = $_SESSION['date'];

// Remove the first 8 characters
$date = substr($date, 8);

// Remove leading zero from the day if it's between 1-9
$dateParts = explode("-", $date);
$day = isset($dateParts[1]) ? $dateParts[1] : '';
if (!empty($day) && $day[0] === '0') {
    $dateParts[1] = substr($day, 1);
}
$date = implode("-", $dateParts);

if ($date === "01") {
    $date = 1;
} elseif ($date === "02") {
    $date = 2;
} elseif ($date === "03") {
    $date = 3;
} elseif ($date === "04") {
    $date = 4;
} elseif ($date === "05") {
    $date = 5;
} elseif ($date === "06") {
    $date = 6;
} elseif ($date === "07") {
    $date = 7;
} elseif ($date === "08") {
    $date = 8;
} elseif ($date === "09") {
    $date = 9;
}

echo $date; // Output: 3-1



    if (!isset($_SESSION['name'])) {
        echo "Please Confirm Your Name";
        exit;
    } else if (!isset($email)) {
        echo "Please Confirm Your Email";
        exit;
    } else if (!isset($date)) {
        echo "Please Select Your Booking Date";
        exit;
    } else if (!isset($timeslot)) {
        echo "Please Select Your Booking Time";
        exit;
    } else if (!isset($mobile)) {
        echo "Please Select Your Valid Mobile";
        exit;
    } else if (!isset($qty)) {
        echo "Please Select Your Quantity";
        exit;
    } else if (!isset($amount)) {
        echo "Please Select Your Amount";
        exit;
    }


    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (name, date, number_of_players, timeslot) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $date, $qty, $timeslot);
    if ($stmt->execute()) {
        echo "<h1> Booking Successfull </h1>";
    } else {
        echo "<h1> Booking Failed </h1>";
        exit;
    }
// }
?>

</html>