<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Dubai');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0050";
$advName = "MOBILITY";
$pubName = "OLIMOB";
$serviceName = "GAMEBAR";
$country = "UAE";

$fp = fopen("GAMEBAR_MOBILITY_pin_Request_" . $time_india_one, "a");
fwrite($fp, "\n[$time_india_one] Received $msisdn \n");



$url = "http://103.247.149.59/int/AEmob/SendPIN?cid=26&op=8&msisdn=$msisdn";

$result = file_get_contents($url);


$check = json_decode($result, true);
$code = $check['response'];
$reason = $check['errorMessage'];
// $tid=$check['transactionId'];

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$result','$serviceDate','$time_india_one')";

mysql_query($sql_subscription);

fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api_request $url  and Received $result and Insert into $sql_subscription\n");

if ($code == "SUCCESS") {

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
} else {


    echo json_encode(array('response' => 'Fail', 'errorMessage' => $reason));
    exit();
}
