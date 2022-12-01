<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Qatar');
$serviceDate = date("Y-m-d H:i:s");
$MSISDN = $_GET['msisdn'];
$serviceId = "ARSH0104";
$advName = "SHEMAROO";
$pubName = "DREAM_MOBI";
$serviceName = "SHEMAROO";
$country = "QATAR";
$PromoID = 29266;
$PartnerID = 8;
$TransactionID = rand();
$fp = fopen("SHEMAROO_REQUEST_" . $time_india, "a");
fwrite($fp, "\n [$time_india] Received $MSISDN with PromoID $PromoID  PartnerID $PartnerID TransactionID $TransactionID \n");
//Added By Rajendra
// API URL
$url = "http://m.shemaroo.com/intl/TimweService/GenerateOTP";

// Create a new cURL resource
$ch = curl_init($url);
$data = array(
  "MSISDN" => $MSISDN,
  "PromoID" => $PromoID,
  "PartnerID" => $PartnerID,
  "TransactionID" => $TransactionID
);
$payload = json_encode($data);

// Attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

// Return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the POST request
$result = curl_exec($ch);

// Close cURL resource
curl_close($ch);

$subscriptionResult = json_encode($result);
$check = json_decode($result, true);

$code = $check['Code'];
$reason = $check['Message'];
$TransactionID = $check['TransactionID'];

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$subscriptionResult','$reason','$TransactionID','$serviceDate','$time_india_one')";
mysql_query($sql_subscription);

fwrite($fp, "\n[$time_india] Received $MSISDN hitting the url $url with payload $payload Received $subscriptionResult QUERY $sql_subscription \n");
fclose($fp);

if ($code == 0) {

  echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
  exit();
} else {
  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn'));
}
