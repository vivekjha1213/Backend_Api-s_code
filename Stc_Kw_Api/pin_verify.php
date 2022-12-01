<?php
//ADDED BY VIVEK.
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kuwait');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$serviceId = "ARSH0087";
$advName = "Shemaroo";
$pubName = "MOVIPLUS";
$serviceName = "SHEEMARO_STC_KUWAIT";
$country = "KUWAIT-STC";


$sql = "SELECT `finalStatus` from `in_app_pin_request` WHERE `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$tid = $row['finalStatus'];

$fp = fopen('Shemaroo_Kuwait_stc_verify_log' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn  pin $pin\n");


$api = "http://m.shemaroo.com/intl/FCCService/ValidateOTP";
$raw_data = array(
  "msisdn" => "$msisdn",
  "PromoId" => 29339,
  "OTP" => "$pin",
  "PartnerId" => 8,
  "TransactionId" => "$tid"
);
$payload = json_encode($raw_data);
$ch = curl_init("http://m.shemaroo.com/intl/FCCService/ValidateOTP");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


// Set HTTP Header for POST request
curl_setopt(
  $ch,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload)
  )
);

// Submit the POST request
$result = curl_exec($ch);
$response = json_decode($result, true);
// print_r($result);
// $tid=$response['TransactionId'];        
$reason = $response['Message'];


fwrite($fp, "\n[$time_india_one]  after hitting the URL $api with payload $payload and got the response $result \n");
// die();
if ($reason == "Success") {

  $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=326&op=SHEMAROO_Stc_Kw_Moviplus');
  if ($results == 0) //MEANS PASS
  {
    // print_r($result);


    $panda = "PASSED";
    // $sql = "INSERT INTO  `Shemaroo_Kuwait_stc_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('" . $clickid . "','" . $msisdn . "','" . $pin . "','" . $result . "','" . $panda . "','" . $time_india . "')";
    // mysql_query($sql);
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
    exit();
  }

  if ($results == 1) //MEANS BLOCK

  {

    $panda = "BLOCK";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
    exit();
  }
} else {

  // $sql = "INSERT INTO  `Shemaroo_Kuwait_stc_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('" . $clickid . "','" . $msisdn . "','" . $pin . "','" . $result . "','" . $reason . "','" . $time_india . "')";
  // mysql_query($sql);
  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
  mysql_query($sql);


  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
