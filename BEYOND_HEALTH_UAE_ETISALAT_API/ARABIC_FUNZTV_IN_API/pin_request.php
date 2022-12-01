<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Baghdad');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0128";
$advName = "INHOUSE";
$pubName = "OLIMOB";
$serviceName = "ARABIC_FUNTV";
$country = "IRAQ_ZAIN";
$TransactionId = rand();

$fp = fopen("Arabic_FunTV_request" . $time_india_one, "a");
fwrite($fp, "\n $time_india_one Received $msisdn \n");

$url = "http://202.143.97.40/adpokeinapp/cnt/inapi/pin/send?msisdn=$msisdn&cmpid=105&txid=$TransactionId";

$result = file_get_contents($url);
$check = json_decode($result, true);
$code = $check['response'];
$reason = $check['errorMessage'];

fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $url and Received $result \n");

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$TransactionId','$serviceDate','$time_india_one')";
mysql_query($sql_subscription);

fwrite($fp, "\n[$time_india_one] Query $sql_subscription  \n");

if ($code == "SUCCESS") {

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
} else {

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn'));
}
?>