<?php
session_start();
if(isset($_POST['merchantId']) && isset($_POST['transactionId']) && isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['date']) && isset($_SESSION['timeslot']) && isset($_SESSION['mobile']) && isset($_SESSION['qty']) && isset($_SESSION['amount'])) {
    // Retrieve session variables
    $name     = $_SESSION['name'];
    $email    = $_SESSION['email'];
    $date     = $_SESSION['date'];
    $timeslot = $_SESSION['timeslot'];
    $mobile   = $_SESSION['mobile'];
    $qty      = $_SESSION['qty'];
    $amount   = $_SESSION['amount'];
    $merchantId      = $_POST['merchantId'];
    $transactionId      = $_POST['transactionId'];
    $_SESSION['merchantId'] = $mid;
    $_SESSION['transactionId'] = $tid;
        echo"$transactionId";
        echo"contact us with the above reference transaction id";
}else{
    echo"some error contact us if the payment was complete";
}
session_unset();
session_destroy();
?>