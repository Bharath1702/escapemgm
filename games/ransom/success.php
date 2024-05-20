<?php
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
    include "./utils/db.php";
    if ($stmt = $conn->prepare("SELECT time FROM timeslots WHERE id = ?")) {
      $stmt->bind_param("i", $timeslot); // "i" indicates the type is integer
      $stmt->execute();
      
      // Bind result variables
      $stmt->bind_result($time); 
      // Close statement
      $stmt->close();
  } else {
      echo "Error preparing statement: " . $conn->error;
  }
  
  // Close connection
  $conn->close();
}
 $mail = new PHPMailer(true);

         try {
             //Server settings
             $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = 'brackets.developer17@gmail.com';           // SMTP username
        $mail->Password   = 'nzrlvzmsdobatsfn';                // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect to

             //Recipients
             $mail->setFrom('escaperoombangalore@gmail.com', 'escapemgm-noreply');
             $mail->addAddress($email, $name);
             $mail->addAddress('escaperoombangalore@gmail.com', 'escapemgm');     // Add a recipient
             $mail->addAddress('escapemgm@escapemgm.com');               // Name is optional
             $mail->addReplyTo('escaperoombangalore@gmail.com', 'escapemgm');
             $mail->addCC('cc@example.com');
             $mail->addBCC('bcc@example.com');

             // Content
             $mail->isHTML(true);                                  // Set email format to HTML
             $mail->Subject = 'Booking Successful for Ransom';
             $mail->Body = "Name: $name<br>Email: $email<br>Phone: $mobile<br>Date: $date<br>Timeslot: $time<br>No. of Players: $qty<br>Advance Paid: $amount<br>TransactionId: $tran_id";
             // Send email 
             $mail->send();
            
            //  echo "<script>alert('sent successfully');window.location.href = 'success.php';</script>";
            
         } catch (Exception $e) {
             echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
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
    background: black;
  }

  h1 {
    color: gold;
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-weight: 900;
    font-size: 40px;
    margin-bottom: 10px;
  }

  p {
    color: white;
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
    background: black;
    padding: 60px;
    border-radius: 4px;
    box-shadow: 0 2px 3px #C8D0D8;
    display: inline-block;
    margin: 0 auto;
  }
</style>
<body>
  <div class="card">
    <div style="border-radius:200px; height:200px; width:200px; background: gold; margin:0 auto;">
      <i class="checkmark">✓</i>
    </div>

    <h1>Booking Successful for Ransom</h1>
    <p>Name: <?php echo $name; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Date: <?php echo $date; ?></p>
    <p>Timeslot: <?php echo $time; ?></p>
    <p>No of Players: <?php echo $qty; ?></p>
    <p>Amount paid : <?php echo $amount; ?></p>
    <p>Transaction ID: <?php echo $tran_id ?></p>
    <p>We received your purchase request;<br /> we'll be in touch shortly!</p>
    <p>check your spam mail folder if you dont receive any mails yet</p><br><a href="../../" style="font-size:30px">go back</a>
  </div>
</body>
<?php
session_start();
$_SESSION = [];

// Destroy the session
session_destroy();
?>

</html>