<?php
require_once "./utils/config.php";
require_once "./utils/common.php";
require_once "./utils/SendMail.php";
include "./utils/db.php";
session_start();
if(isset($_POST['merchantId']) && isset($_POST['transactionId']) && isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount']))
    {
        $name     = $_SESSION['name'];
        $email    = $_SESSION['email'];
        $date     = $_SESSION['date'];
        $timeslot = $_SESSION['timeslot'];
        $mobile   = $_SESSION['mobile'];
        $qty      = $_SESSION['qty'];
        $amount   = $_SESSION['amount'];
        $merchantId      = $_POST['merchantId'];
        $transactionId      = $_POST['transactionId'];
        $_SESSION['merchantId'] = $merchantId;
        $_SESSION['transactionId'] = $transactionId;
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


    if ($responsePayment['success'] && $responsePayment['code'] == "PAYMENT_SUCCESS")
    {
        //Send Email and redirect to success page
    //     $now = new DateTime();
    //     $timestring = $now->format('d-M-Y h:i:s');
    //     $msg = 'Dear ' . $name . ",<br/>";
    //     $msg .= '<br/>We have received your payment and Below is your payment Details<br/> ';
    //     $msg .= '<table>';
    //     $msg .= '<tr><td>Name:</td><td>' . $name . '</td></tr>';
    //     $msg .= '<tr><td>Email:</td><td>' . $email . '</td></tr>';
    //     $msg .= '<tr><td>Mobile:</td><td>' . $mobile . '</td></tr>';
    //     $msg .= '<tr><td>Amount:</td><td>Rs.' . $amount/100 . '</td></tr>';
    //     $msg .= '<tr><td>Transaction id:</td><td>' . $tran_id . '</td></tr>';
    //     $msg .= '<tr><td>Date:</td><td>' . $timestring . '</td></tr>';
    //     $msg .= '</table><br/>';
    //     $msg .= '<p>From,</p>';
    //     $msg .= '<p>Techmalasi Team</p>';
    //     $ob = new Mail();
    //    $r =  $ob->sendMail($email, $msg);
    //    echo "response>>".$r;
        // sleep(3);
        // if($r)
        // header('Location:success.php');
    include "./utils/db.php";
        // else
        // header('Location:success.php');
    include "./utils/db.php";
    $stmt = mysqli_prepare($conn, "INSERT INTO ransom (name,email,mobile, date, no_of_players, timeslot_id,txnID) VALUES (?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("sssssss", $name,$email,$mobile, $date, $qty, $timeslot,$tran_id );
    if ($stmt->execute()) {
        echo "<h1> Booking Successfull </h1>";
    } else {
        echo "<h1> Booking Failed </h1>";
        exit;
    }
        header('Location:success.php');
}
else {
    header('Location:failuer.php');
    }
}else{
    echo"Retry again....";
}
?>