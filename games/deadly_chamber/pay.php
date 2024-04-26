<?php
require './utils/config.php';
require './utils/common.php';

if(API_STATUS == "LIVE"){
    $merchantid  = MERCHANTIDLIVE;
    $saltkey = SALTKEYLIVE;
    $saltindex = SALTINDEX;
    $url = LIVEURLPAY;
}else{
    $merchantid  = MERCHANTIDUAT;
    $saltkey = SALTKEYUAT;
    $saltindex = SALTINDEX;
    $url = UATURLPAY;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['date']) && isset($_POST['timeslot']) && isset($_POST['mobile']) && isset($_POST['qty']) && isset($_POST['amount'])){
    // echo"success";
    session_start();
    $_SESSION['name']    = $_POST['name'];
    $_SESSION['email']   = $_POST['email'];
    $_SESSION['date']    = $_POST['date'];
    $_SESSION['timeslot'] = $_POST['timeslot'];
    $_SESSION['mobile']   = $_POST['mobile'];
    $_SESSION['qty']      = $_POST['qty'];
    $_SESSION['amount']   = $_POST['amount'];  
    
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $date    = $_POST['date'];
    $timeslot= $_POST['timeslot'];
    $mobile  = $_POST['mobile'];
    $qty     = $_POST['qty'];
    $amount  = $_POST['amount'];

}else{
    echo "<script>alert('Please try clearing the browser cache and try agaub')</script>";
}
$payLoad = array(
    'merchantId' => $merchantid,
    'merchantTransactionId' => "ESCMT-" . getTransactionID(), // test transactionID
    "merchantUserId" => "M-" . uniqid(),
    'amount' => $amount * 100, // phone pe works on paise
    'redirectUrl' => BASE_URL . REDIRECTURL,
    'redirectMode' => "POST",
    'callbackUrl' => BASE_URL . REDIRECTURL,
    "mobileNumber" => $mobile,
    // "email" => $email,
    // "param1"=>$email,
    "paymentInstrument" => array(
        "type" => "PAY_PAGE",
    )
);
$jsonencode = json_encode($payLoad);

    $payloadbase64 = base64_encode($jsonencode);

    $payloaddata = $payloadbase64 . "/pg/v1/pay" . $saltkey;

    // echo "<p> payloadbase64:  ". $payloadbase64 ."</p>";

    $sha256 = hash("sha256", $payloaddata);

    $checksum = $sha256 . '###' . $saltindex;

    // echo $checksum;

    // echo "<p> X-verify:   ". $checksum ."</p>";

    $request = json_encode(array('request' => $payloadbase64));
    // echo "<br/>" . $url;
    $curl = curl_init(); // This extention should be installed

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-VERIFY: " . $checksum,
            "accept: application/json"
        ],
    ]);

    
    $response = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $res = json_decode($response);

        // echo "<br/>response===";
        // print_r($res);

        if (isset($res->success) && $res->success == '1') {
            $payUrl = $res->data->instrumentResponse->redirectInfo->url;
            header('Location:' . $payUrl);
        }
    }
?>