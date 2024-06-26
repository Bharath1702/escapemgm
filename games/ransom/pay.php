<?php
require './utils/config.php';
require './utils/common.php';

// Check API status and set variables accordingly
if (API_STATUS == 'LIVE') {
    $merchantid = MERCHANTIDLIVE;
    $saltkey = SALTKEYLIVE;
    $saltindex = SALTINDEX;
    $url = LIVEURLPAY;
} else {
    $merchantid = MERCHANTIDUAT;
    $saltkey = SALTKEYUAT;
    $saltindex = SALTINDEX;
    $url = UATURLPAY;
}

// Initialize cURL to check URL availability
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Check if all required POST data is set
if (isset($_POST['name'], $_POST['email'], $_POST['date'], $_POST['timeslot'], $_POST['mobile'], $_POST['qty'], $_POST['amount'])) {
    session_start();
    session_regenerate_id();

    // Assign POST data to session variables
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['date'] = $_POST['date'];
    $_SESSION['timeslot'] = $_POST['timeslot'];
    $_SESSION['mobile'] = $_POST['mobile'];
    $_SESSION['qty'] = $_POST['qty'];
    $_SESSION['amount'] = $_POST['amount'];
    $game = 'RANSOM';

    include './utils/db.php';

    // Insert data into the cart table
    $stmt = $conn->prepare("INSERT INTO cart (name, email, mobile, game, date, no_of_players, timeslot_id, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssssiis", $_SESSION['name'], $_SESSION['email'], $_SESSION['mobile'], $game, $_SESSION['date'], $_SESSION['qty'], $_SESSION['timeslot'], $_SESSION['amount']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
} else {
    echo "<script>alert('Please try clearing the browser cache and try again')</script>";
    exit;
}

// Create payload for payment request
$payLoad = array(
    'merchantId' => $merchantid,
    'merchantTransactionId' => "ESCMT-" . getTransactionID(), // test transactionID
    "merchantUserId" => "M-" . uniqid(),
    'amount' => $_SESSION['amount'] * 100, // phone pe works on paise
    'redirectUrl' => BASE_URL . REDIRECTURL,
    'redirectMode' => "POST",
    'callbackUrl' => BASE_URL . REDIRECTURL,
    "mobileNumber" => $_SESSION['mobile'],
    "paymentInstrument" => array(
        "type" => "PAY_PAGE",
    )
);

// Encode payload to JSON and then to base64
$jsonencode = json_encode($payLoad);
$payloadbase64 = base64_encode($jsonencode);

// Create checksum
$payloaddata = $payloadbase64 . "/pg/v1/pay" . $saltkey;
$sha256 = hash("sha256", $payloaddata);
$checksum = $sha256 . '###' . $saltindex;

// Create request
$request = json_encode(array('request' => $payloadbase64));
$curl = curl_init(); // Initialize cURL

// Set cURL options
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

// Execute cURL request
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Handle cURL errors
if ($err) {
    echo "Check Your Internet Connection or Try Again after sometimes ";
} else {
    $res = json_decode($response);
    if (isset($res->success) && $res->success == '1') {
        $payUrl = $res->data->instrumentResponse->redirectInfo->url;
        header('Location:' . $payUrl); // Redirect to payment URL
        exit;
    } else {
        echo "Payment initiation failed. Please try again.";
    }
}
?>
