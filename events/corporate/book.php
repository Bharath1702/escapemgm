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
            $mail->Subject = 'Bulk Booking Enquiry Initiated for Corporate, We will confirm shortly';
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
                    <h1>Enquiry Initiated</h1>
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
                                    <th class='header'>Phone No.</th>
                                    <th>$mobile</th>
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
                <p>
                Enquiry Initiated! We look forward to providing you a memorable experience at our Escape room. For any further queries or information regarding our offerings, you can reach out to us at <a href='tel:7676372273'>+91 7676372273</a>.<br>
                <br>
                <b>Our Address: </b> <br>Escape room, 3rd Floor Pragati Mansion,<br> 1st Cross Rd, 5th Block, Koramangala,<br> Karnataka 560034.
Or  <a href='https://maps.app.goo.gl/mcGNwANdqHG7pQ969'> click here</a> <br><br>
                <br>
                Please reach the location at least 10 minutes before the slot time for a hassle free experience as there would be activities like filling up the consent forms, briefing about the game and payments, which might take some time.
                <br>
                <h3>Important Notice</h3>
                    <ol>
                        <li>
                        In case of any delays, please note that we might have to deduct some time from your slot to ensure that it doesn’t affect the next slots of the day.
                        </li>
                        <li>
                        In case of cancellations, You would have to inform us through call at least 3 hours before your time schedule, as the deposit is non-refundable, it can only be rescheduled based on the availability.
                        </li>
                    </ol>
                </p>
                <h3><b>We at Escape room are looking forward to host you. Meanwhile you can get to know our team better.</b></h3>
                <center>
                    <img src='https://escapemgm.com/Gallary/teamimg.jpeg' width='80%' height='auto' alt='Escape Team'>
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
