

<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
session_start();




require_once "./utils/config.php";
require_once "./utils/common.php";

if(API_STATUS=="LIVE"){ 
    $merchantid  = MERCHANTIDLIVE;
    $saltkey = SALTKEYLIVE;
    $saltindex = SALTINDEX;
    }else{
        $merchantid  = MERCHANTIDUAT;
        $saltkey = SALTKEYUAT;
        $saltindex = SALTINDEX;
    }



//---------------test-----------------------



$ch = curl_init();
$url = "";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);



if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['date']) && isset($_POST['mobile']) && isset($_POST['qty']) && isset($_POST['amount'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $mobile = $_POST['mobile'];
    $qty = $_POST['qty'];
    $amount = $_POST['amount'];
    $timeslot = $_POST['timeslot'];

    $_SESSION['name']    =$name     ; 
    $_SESSION['email']   =$email    ;  
    $_SESSION['date']    =$date     ; 
    $_SESSION['mobile']  =$mobile   ; 
    $_SESSION['timeslot']=$timeslot ;  
    $_SESSION['qty']     =$qty      ;  
    $_SESSION['amount']  =$amount   ;  
    header("Location: paymentstatus.php");

    $payLoad = array(
        'name' => $name,
        'email' => $email,
        'date' => $date,
        'mobile' => $mobile,
        'qty' => $qty,
        'timeslot' => $timeslot,
        'merchantId' => $merchantid,
        'merchantTransactionId' => "MT-" . getTransactionID(), // test transactionID
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

    echo "<p> payloadbase64:  ". $payloadbase64 ."</p>";

    $sha256 = hash("sha256", $payloaddata);

    $checksum = $sha256 . '###' . $saltindex;

    echo $checksum;

    echo "<p> X-verify:   ". $checksum ."</p>";

    $request = json_encode(array('request' => $payloadbase64));

    $url = '';
    if (API_STATUS == "LIVE") {
        $url = LIVEURLPAY;
    } else {
        $url = UATURLPAY;
    }

    echo "<br/>" . $url;




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

        echo "<br/>response===";
        print_r($res);

        if (isset($res->success) && $res->success == '1') {
            
            // $paymentCode=$res->code;
            // $paymentMsg=$res->message;
            $payUrl = $res->data->instrumentResponse->redirectInfo->url;

            //  $name = $_REQUEST['name'];
            //  $email = $_REQUEST['email'];
            //  $date = $_REQUEST['date'];
            //  $mobile = $_REQUEST['mobile'];
            // $qty = $_REQUEST['qty'];
            // $amount = $_REQUEST['amount'];

            // echo "<script>window.location.href='paymentstatus.php?name=".$name."&email=".$email."&date=".$date."&mobile=".$mobile."&qty=$mobile&qty=';</script>";

            header('Location:' . $payUrl);
        }
    }
}
