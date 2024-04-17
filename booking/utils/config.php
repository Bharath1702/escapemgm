<?php

define("BASE_URL", "http://localhost/phonepegw/");
define("API_STATUS", "LIVE"); //LIVE OR UAT
define("MERCHANTIDLIVE", "M22LJ64K27TRM");
define("MERCHANTIDUAT", "PGTESTPAYUAT");  //Sandbox testing 
define("SALTKEYLIVE", "bb0e4def-61cc-47cf-aef9-03b4cba84a85");
define("SALTKEYUAT", "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399");//
define("SALTINDEX", "1");
define("REDIRECTURL", "paymentstatus.php");
define("SUCCESSURL", "success.php");
define("FAILUREURL", "failure.php");
define("UATURLPAY", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
define("LIVEURLPAY", "https://api.phonepe.com/apis/hermes/pg/v1/pay");
define("STATUSCHECKURL", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/");
define("LIVESTATUSCHECKURL", "https://api.phonepe.com/apis/hermes/pg/v1/status/");
?>