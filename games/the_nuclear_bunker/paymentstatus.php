<?php
// Include necessary configuration and utility files
require_once "./utils/config.php";
require_once "./utils/common.php";

session_start();

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
    echo "Something went wrong, Please contact us If the payment was successful";
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
    $stmt = $conn->prepare("INSERT INTO the_nuclear_bunker (name, email, mobile, date, no_of_players, timeslot_id, txnID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $mobile, $date, $qty, $timeslot, $tran_id);
    
    if ($stmt->execute()) {
        header('Location:success.php');
    } else {
        echo "<script>alert('Database insertion failed');</script>";
        exit;
    }
} else {
    header('Location:failure.php');
    exit;
}
?>
