<?php
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount']) && isset($_SESSION['transactionId']) && isset($_SESSION['tran_id'])) {
    // Retrieve session variables
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $date = $_SESSION['date'];
    $timeslot = $_SESSION['timeslot'];
    $mobile = $_SESSION['mobile'];
    $qty = $_SESSION['qty'];
    $amount = $_SESSION['amount'];
    $transactionId = $_SESSION['transactionId'];
    $tran_id = $_SESSION['tran_id'];
    
    include './utils/db.php';
    // Assuming you have a database connection in db.php like:
    // $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    if ($stmt = $conn->prepare("SELECT time FROM timeslots WHERE id = ?")) {
        $stmt->bind_param("i", $timeslot); // "i" indicates the type is integer
        $stmt->execute();
        
        // Bind result variables
        $stmt->bind_result($time);
        
        // Fetch value
        $stmt->fetch();
        
        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close connection
    $conn->close();
    
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'brackets.developer17@gmail.com';
        $mail->Password = 'nzrlvzmsdobatsfn';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('escaperoombangalore@gmail.com', 'escapemgm-noreply');
        $mail->addAddress($email, $name);
        $mail->addAddress('escaperoombangalore@gmail.com', 'escapemgm');
        $mail->addAddress('escapemgm@escapemgm.com');
        $mail->addReplyTo('escaperoombangalore@gmail.com', 'escapemgm');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Successful for Ransom';
        $mail->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Booking Successful</title>
                <style>
                    .table {
                        display: block;
                        margin: auto;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    thead {
                        width: 100%;
                    }
                    th {
                        border: 2px solid black;
                        width: 200px;
                    }
                    .header {
                        background-color: gold;
                    }
                </style>
            </head>
            <body>
                <center>
                    <h1>Booking Successful</h1>
                    <a href='https://escapemgm.com'><img src='https://escapemgm.com/Gallary/escapelogo.webp' width='200px' height='auto' alt='Escape Room Logo'></a>
                    <div class='table'>
                        <table>
                            <thead>
                                <tr>
                                    <th class='header'>Name</th>
                                    <th>$name</th>
                                </tr>
                                <tr>
                                    <th class='header'>Email</th>
                                    <th>$email</th>
                                </tr>
                                <tr>
                                    <th class='header'>Date</th>
                                    <th>$date</th>
                                </tr>
                                <tr>
                                    <th class='header'>TimeSlot</th>
                                    <th>$time</th>
                                </tr>
                                <tr>
                                    <th class='header'>No. Of Players</th>
                                    <th>$qty</th>
                                </tr>
                                <tr>
                                    <th class='header'>Advance Paid</th>
                                    <th>$amount</th>
                                </tr>
                                <tr>
                                    <th class='header'>Transaction ID</th>
                                    <th>$tran_id</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </center>
            </body>
            </html>
        ";
        $mail->AltBody = 'Booking Successful for Ransom. Check your email for details.';

        // Send email
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    }
} else {
    echo "Required session variables are missing.";
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
    <p>Name: <?php echo htmlspecialchars($name); ?></p>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Date: <?php echo htmlspecialchars($date); ?></p>
    <p>Timeslot: <?php echo htmlspecialchars($time); ?></p>
    <p>No of Players: <?php echo htmlspecialchars($qty); ?></p>
    <p>Amount paid : <?php echo htmlspecialchars($amount); ?></p>
    <p>Transaction ID: <?php echo htmlspecialchars($tran_id); ?></p>
    <p>We received your purchase request;<br /> we'll be in touch shortly!</p>
    <p>Check your spam mail folder if you don't receive any mails yet</p><br><a href="../../" style="font-size:30px">Go back</a>
  </div>
</body>
<?php
session_start();
$_SESSION = [];

// Destroy the session
session_destroy();
?>
</html>
