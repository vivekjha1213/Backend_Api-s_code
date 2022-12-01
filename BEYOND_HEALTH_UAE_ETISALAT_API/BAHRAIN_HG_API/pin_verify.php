<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Bahrain');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$serviceId = "ARSH0084";
$advName = "mobility";
$pubName = "olimob";
$serviceName = "HG";
$country = "Bahrain";



$fp = fopen("pin_Verify_" . $time_india_one, "a");
fwrite($fp, "\n[$time_india_one] Received $msisdn with OTP $pin\n");

$data_Raw = array(
  "msisdn" => "$msisdn",
  "packName" => "HG",
  "otpString" => "$pin",
  "channel" => "WEB"
);

$payload = json_encode($data_Raw);

//  echo $payload;
// die();


// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://223.30.211.119/OtpWraper/batelco/validate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$payload");

$headers = array();
$headers[] = 'Authorization: Basic YWRtaW46YWJjZGVAMTIz';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Cookie: JSESSIONID=2800CF76CA6AB2A6AE49D70A064BF95F; JSESSIONID=2099DC941ABD16967AAA41D6180B764D';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

// echo $result;
curl_close($ch);


// $subscriptionResult = json_encode($result);
$check = json_decode($result, true);
// echo "<pre>";
// print_r($check);
$code = $check['result'];
$reason = $check['Description'];

// echo "<pre>";
// print_r($code);

fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url with payload $payload Received $subscriptionResult\n");
fclose($fp);

if ($code == "Success") {

  $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=323&op=HG_BAHRAIN_MOBILITY_OLIMOB');
  if ($results == 0) //MEANS PASS
  {

    $panda = "PASSED";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  }
  if ($results == 1) //MEANS BLOCK
  {

    $panda = "BLOCK";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi hit on the msisdn'));
    exit();
  }
} else {


  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$code','$reason','$serviceDate','$time_india_one')";
  mysql_query($sql);

  echo json_encode(array('response' => 'Fail', 'errorMessage' => $reason));
}
