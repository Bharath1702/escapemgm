

<?php

session_start();
// session_set_cookie_params(3600, '/', '', false, true);


require_once "./utils/config.php";
require_once "./utils/common.php";



$ch = curl_init();
$url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
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

    // Set session variables
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['date'] = $date;
    $_SESSION['mobile'] = $mobile;
    $_SESSION['timeslot'] = $timeslot;
    $_SESSION['qty'] = $qty;
    $_SESSION['amount'] = $amount;
    session_write_close();


    
    //     if (!isset($_SESSION['name'])) {
    //         echo "<h1>Name is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['email'])) {
    //         echo "<h1>Email is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['date'])) {
    //         echo "<h1>Date is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['mobile'])) {
    //         echo "<h1>Mobile is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['timeslot'])) {
    //         echo "<h1>Timeslot is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['qty'])) {
    //         echo "<h1>Quantity is not Set.</h1>";
    //         exit;
    //     }
    
    //     if (!isset($_SESSION['amount'])) {
    //         echo "<h1>Amount is not Set.</h1>";
    //         exit;
    //     }
    // } else {
    //     echo "<h1>Your Properties are not set properly in pay.php</h1>";
    //     exit;
    // }

//     if(!isset($_SESSION['name'])) {
//         echo "<h1>Session name is not set</h1>";
//         exit;
//     } else {
//         echo "<h1>Session name is set properly</h1>";
//         echo $_SESSION['name'];
//  exit;
//     }



    $merchantid  = MERCHANTIDUAT;
    $saltkey = SALTKEYUAT;
    $saltindex = SALTINDEX;


    $payLoad = array(
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


    $sha256 = hash("sha256", $payloaddata);

    $checksum = $sha256 . '###' . $saltindex;

    echo $checksum;


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
