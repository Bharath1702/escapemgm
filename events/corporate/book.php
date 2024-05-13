<?php
include '../db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

if (isset($_POST['name'])) {
    $name    = $_POST['name'];
    $mobile  = $_POST['mobile'];
    $email   = $_POST['email'];
    $date    = $_POST['date'];
    $event   = 'CORPORATE';
    $qty     = $_POST['qty'];
    $details = $_POST['details'];

    // Prepare the SQL statement using placeholders
    $sql = "INSERT INTO events (name, email, date, mobile, event, details, qty) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bind_param("ssssssi", $name, $email, $date, $mobile, $event, $details, $qty);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp-relay.brevo.com';          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = '74bba4001@smtp-brevo.com';           // SMTP username
        $mail->Password   = 'Z1HATDU6V0gSBq78';                // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect to

            //Recipients
            $mail->setFrom('escaperoombangalore@gmail.com', 'escapemgm-noreply');
            $mail->addAddress($email, $name); 
            $mail->addAddress('escaperoombangalore@gmail.com', 'escapemgm');    // Add a recipient
            $mail->addAddress('escapemgm@escapemgm.com');               // Name is optional
            $mail->addReplyTo('escaperoombangalore@gmail.com', 'escapemgm');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Bulk Booking Successful for Corporate Party We will confirm shortly.';
            $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $phone<br>Date : $date  <br>No. of Players: $qty <br>Event Details : $details";
            // Send email 
            $mail->send();
            
            echo "<script>alert('sent successfully');window.location.href = './';</script>";
            
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error occurred while inserting data into database')</script>";
    }
} else {
    echo "<script>alert('ohoh!! something went wrong')</script>";
}
?>
