<?php
require_once "./utils/config.php";
require_once "./utils/common.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

session_start();
if(isset($_POST['merchantId']) && isset($_POST['transactionId']) && isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount']))
    {
        $name                       = $_SESSION['name'];
        $email                      = $_SESSION['email'];
        $date                       = $_SESSION['date'];
        $timeslot                   = $_SESSION['timeslot'];
        $mobile                     = $_SESSION['mobile'];
        $qty                        = $_SESSION['qty'];
        $amount                     = $_SESSION['amount'];
        $merchantId                 = $_POST['merchantId'];
        $transactionId              = $_POST['transactionId'];
        $_SESSION['merchantId']     = $merchantId;
        $_SESSION['transactionId']  = $transactionId;
        // echo"everything properly set";

if (API_STATUS == "LIVE") {
    $url = LIVESTATUSCHECKURL . $merchantId . "/" . $transactionId;
    $saltkey = SALTKEYLIVE;
    $saltindex = SALTINDEX;
} else {
    $url = STATUSCHECKURL . $merchantId . "/" . $transactionId;
    $saltkey = SALTKEYUAT;
    $saltindex = SALTINDEX;
}



$st = "/pg/v1/status/" . $merchantId . "/" . $transactionId . $saltkey;

$dataSha256 = hash("sha256", $st);

$checksum = $dataSha256 . "###" . $saltindex;


//GET API CALLING
$headers = array(
    "Content-Type: application/json",
    "accept: application/json",
    "X-VERIFY: " . $checksum,
    "X-MERCHANT-ID:" . $merchantId
);



$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$resp = curl_exec($curl);

curl_close($curl);

$responsePayment = json_decode($resp, true);

// echo "<pre>";
// print_r($responsePayment);
// echo "</pre>";


$tran_id = $responsePayment['data']['transactionId'];
$amount = $responsePayment['data']['amount'];
$_SESSION['tran_id']=$tran_id;


if ($responsePayment['success'] && $responsePayment['code'] == "PAYMENT_SUCCESS") {
    include "./utils/db.php";
        $stmt = mysqli_prepare($conn, "INSERT INTO deadly_chamber (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $mobile, $date, $qty, $timeslot, $tran_id);
        if ($stmt->execute()) {
            echo "<script>alert('Booking Successful');</script>";
            header('Location: success.php');
            exit; // Exit after redirection
        } else {
            echo "<script>alert('Oops, something went wrong. Please contact us.');</script>";
            exit; // Exit after error message
        }
    // Send email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp-relay.brevo.com';          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = '74bba4001@smtp-brevo.com';           // SMTP username
        $mail->Password   = 'Z1HATDU6V0gSBq78';                // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect to

        // Recipients
        $mail->setFrom('escaperoombangalore@gmail.com', 'escapemgm-noreply');
        $mail->addAddress($email, $name);     // Add a recipient
        $mail->addAddress('escapemgm@escapemgm.com');               // Name is optional
        $mail->addReplyTo('escaperoombangalore@gmail.com', 'escapemgm');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Booking Successful for The Nuclear Bunker';
        $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $phone<br>Date : $date <br>Timeslot : $timeslot <br>No. of Players: $qty<br>Advance Paid: $amount <br>TransactionId : $transactionId";
        
        // Send email
        $mail->send();

        // Insert into database
        
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        exit; // Exit after error message
    }
} else {
    // Payment failure, redirect to failure page
    header('Location: failure.php');
    exit; // Exit after redirection
}else{
    echo"Retry again....";
}
?>