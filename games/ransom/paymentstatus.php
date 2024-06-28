<?php
// Include necessary configuration and utility files
require_once "./utils/config.php";
require_once "./utils/common.php";
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$local_cookie_id = $_COOKIE['randomValue'] ?? null;

if (!$local_cookie_id) {
    echo "Cookie ID not found. Please enable cookies in your browser.";
    exit;
}

// Function to retrieve POST data with a fallback to session data
function get_post_or_session($key) {
    return $_POST[$key] ?? $_SESSION[$key] ?? null;
}

// Retrieve data from POST or session
$name = get_post_or_session('name');
$email = get_post_or_session('email');
$date = get_post_or_session('date');
$timeslot = get_post_or_session('timeslot');
$mobile = get_post_or_session('mobile');
$qty = get_post_or_session('qty');
$amount = get_post_or_session('amount');
$merchantId = get_post_or_session('merchantId');
$transactionId = get_post_or_session('transactionId');

// Check if all required variables are set
if (!$name || !$email || !$date || !$timeslot || !$mobile || !$qty || !$amount || !$merchantId || !$transactionId) {
    echo "Something went wrong, please contact us if the payment was successful.";
    exit;
}

// Save POST data to session if available
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['date'] = $date;
$_SESSION['timeslot'] = $timeslot;
$_SESSION['mobile'] = $mobile;
$_SESSION['qty'] = $qty;
$_SESSION['amount'] = $amount;
$_SESSION['merchantId'] = $merchantId;
$_SESSION['transactionId'] = $transactionId;

// Determine API URL and salt key based on API status
if (API_STATUS == "LIVE") {
    $url = LIVESTATUSCHECKURL . $merchantId . "/" . $transactionId;
    $saltkey = SALTKEYLIVE;
} else {
    $url = STATUSCHECKURL . $merchantId . "/" . $transactionId;
    $saltkey = SALTKEYUAT;
}
$saltindex = SALTINDEX;

// Generate checksum for the API request
$st = "/pg/v1/status/$merchantId/$transactionId$saltkey";
$dataSha256 = hash("sha256", $st);
$checksum = "$dataSha256###$saltindex";

// Set up headers for the API request
$headers = [
    "Content-Type: application/json",
    "accept: application/json",
    "X-VERIFY: $checksum",
    "X-MERCHANT-ID: $merchantId"
];

// Initialize and configure cURL session
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
]);

// Execute the API request and close cURL session
$resp = curl_exec($curl);
curl_close($curl);

// Decode the API response
$responsePayment = json_decode($resp, true);

// Handle decoding error
if (!$responsePayment) {
    echo "Error decoding response";
    exit;
}

// Extract transaction ID from the response
$tran_id = $responsePayment['data']['transactionId'] ?? null;
$_SESSION['tran_id'] = $tran_id;

// Check if the payment was successful
if ($responsePayment['success'] && $responsePayment['code'] == "PAYMENT_SUCCESS") {
    // Include database connection
    include "./utils/db.php";

    // Prepare and execute SQL statement to insert booking details
    $stmt = $conn->prepare("INSERT INTO ransom (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $mobile, $date, $qty, $timeslot, $tran_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Update payment status in the cart table
        $stmt = $conn->prepare("UPDATE cart SET payment_status='success' WHERE cookie_id=?");
        $stmt->bind_param("s", $local_cookie_id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: success.php');
            exit;
        } else {
            echo "<script>alert('Failed to update payment status');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Database insertion failed');</script>";
        exit;
    }
} else {
    // Initialize PHPMailer
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
        $mail->Subject = 'Booking Failed for Ransom';
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='shortcut icon' href='https://escapemgm.com/Gallary/escapelogo.webp' type='image/x-icon'>
            <title>Booking Failed</title>
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
                <h1>Booking Failed</h1>
                <h2>Contact us if the Transaction Was Completed.</h2>
            </center>
            <p>
                For any further queries or information regarding our offerings, you can reach out to us at <a href='tel:7676372273'>+91 7676372273</a>.<br>
                Our Address: Escape room, 3rd Floor Pragati Mansion, 1st Cross Rd, 5th Block, Koramangala, Karnataka 560034.
                Or <a href='https://maps.app.goo.gl/mcGNwANdqHG7pQ969'>click here</a>             
            </p>
            <h3><b>We at Escape room are looking forward to hosting you. Meanwhile you can get to know our team better.</b></h3>
            <center>
                <img src='https://escapemgm.com/Gallary/teamimg.jpeg' width='80%' height='auto' alt='Escape Team'>
            </center>
        </body>
        </html>
        ";
        $mail->AltBody = 'Booking Unsuccessful for Ransom. Check your email for details.';

        // Send email
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    }

    // Include database connection
    include "./utils/db.php";

    // Update payment status to 'failed' in the cart table
    $stmt = $conn->prepare("UPDATE cart SET payment_status='failed' WHERE cookie_id=?");
    $stmt->bind_param("s", $local_cookie_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: failure.php');
        exit;
    } else {
        echo "<script>alert('Failed to update payment status');</script>";
        exit;
    }
}
?>
