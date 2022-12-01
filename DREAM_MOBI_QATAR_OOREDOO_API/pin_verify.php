<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Qatar');
$serviceDate = date("Y-m-d H:i:s");
$MSISDN = $_GET['msisdn'];
$OTP = $_GET['pin'];
$serviceId = "ARSH0104";
$advName = "SHEMAROO";
$pubName = "DREAM_MOBI";
$serviceName = "SHEMAROO";
$country = "QATAR";
$PromoID = 29266;
$PartnerID = 8;
$sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "'  and serviceId='ARSH0104' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$TransactionID = $row['finalStatus'];

$fp = fopen("SHEMAROO_VERIFY_" . $time_india_one, "a");
fwrite($fp, "\n[$time_india_one] Received $MSISDN with PromoID $PromoID OTP $OTP PartnerID $PartnerID TransactionID $TransactionID\n");
$url = "http://m.shemaroo.com/intl/TimweService/ValidateOTP";

// Create a new cURL resource
$ch = curl_init($url);

//exit();
$data = array(
  "MSISDN" => $MSISDN,
  "PromoID" => $PromoID,
  "OTP" => $OTP,
  "PartnerID" => $PartnerID,
  "TransactionID" => $TransactionID
);
$payload = json_encode($data);

//exit();

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
fwrite($fp, "\n[$time_india_one] Received $MSISDN with $url and with payload $payload result $subscriptionResult'\n");
if ($code == 0) {

  $result_shell = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=344&op=Shemaroo_Qatar_Dream_mobi');
  if ($result_shell == 0) //MEANS PASS
  {
    $panda = "PASSED";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$OTP','$subscriptionResult','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  }
  if ($result_shell == 1) //MEANS BLOCK
  {
    $panda = "BLOCK";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$OTP','$subscriptionResult','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi hit on the msisdn'));
    exit();
  }
} else {

  $send = $reason;
  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$OTP','$send','$subscriptionResult','$reason','$serviceDate','$time_india_one')";
  mysql_query($sql);

  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
