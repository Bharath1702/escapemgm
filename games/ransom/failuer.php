<?php
 require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
 require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
 require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';
 require '../../mailconfig.php';
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
session_start();
if( isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount'])) {
    // Retrieve session variables
    $name     = $_SESSION['name'];
    $email    = $_SESSION['email'];
    $date     = $_SESSION['date'];
    $timeslot = $_SESSION['timeslot'];
    $mobile   = $_SESSION['mobile'];
    $qty      = $_SESSION['qty'];
    $amount   = $_SESSION['amount'];
    $mail = new PHPMailer(true);
 try {
     // Server settings
         $mail->isSMTP();
     $mail->Host = HOST;
     $mail->SMTPAuth = true;
     $mail->Username = USERNAME;
     $mail->Password = PASSWORD;
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
     $mail->Subject = 'Booking Failed for Ransom';
     $mail->Body = "
     <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Enquiry Initiated</title>
                <link rel='shortcut icon' href='https://escapemgm.com/Gallary/escapelogo.webp' type='image/x-icon'>
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
                    
                    <a href='https://escapemgm.com'><img src='https://escapemgm.com/Gallary/escapelogo.webp' width='200px' height='auto' alt='Escape Room Logo'></a>
                    <h1>!!!!Booking Failed</h1>
                <b>Our Address: </b> <br>Escape room, 3rd Floor Pragati Mansion,<br> 1st Cross Rd, 5th Block, Koramangala,<br> Karnataka 560034.
Or  <a href='https://maps.app.goo.gl/mcGNwANdqHG7pQ969'> click here</a> <br><br>
                
                </p>
                <h3><b>If the amount was deducted and the booking was unsuccessful Please contact us</b></h3>
                <center>
                    <img src='https://escapemgm.com/Gallary/teamimg.jpeg' width='80%' height='auto' alt='Escape Team'>
                </center>
            </body>
            </html>
     ";

     // Send email
     $mail->send();
     echo 'Message has been sent';
 } catch (Exception $e) {
     echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
 }   
    
    
}else{
    echo"some error contact us if the payment was complete";
}
session_unset();
session_destroy();
?>