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
    $event   = 'BIRTHDAY';
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
            $mail->Subject = 'Bulk Booking Successful for Birthday We will confirm shortly';
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
                                    <th class='header'>No. Of Players</th>
                                    <th>$qty</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </center>
            </body>
            </html>
        ";            // Send email 
            $mail->send();
            
            echo "<script>alert('sent successfully');window.location.href = './';</script>";
            
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
        }

        // Close the statement
    } else {
        echo "<script>alert('Error occurred while inserting data into database')</script>";
    }
} else {
    echo "<script>alert('ohoh!! something went wrong')</script>";
}
?>
