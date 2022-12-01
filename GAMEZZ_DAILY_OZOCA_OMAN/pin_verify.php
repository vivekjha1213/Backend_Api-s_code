<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Muscat');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$serviceId = "ARSH0043";
$advName = "OZOCA";
$pubName = "OLIMOB";
$serviceName = "GEMEZZ_DAILY";
$country = "OMAN";
$fp = fopen('GEMEZZ_DAILY_pin_verify_log' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn \n");

// curl -X POST   'http://13.250.242.221:7329/api/v2/pin/verify?msisdn=96879272045&pin=1234&token=6CkpMUDuGfs8wLRWujXb&service=daily'

$url="http://13.250.242.221:7329/api/v2/pin/verify";

$raw_data=array(  
  "msisdn"=>"$msisdn",
  "pin"=>"$pin",
  "token"=>"6CkpMUDuGfs8wLRWujXb",
  "service"=>"daily"
);  
$payload=json_encode($raw_data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://13.250.242.221:7329/api/v2/pin/verify?msisdn=$msisdn&pin=$pin&token=6CkpMUDuGfs8wLRWujXb&service=daily");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$result = curl_exec($ch);


curl_close($ch);
$check = json_decode($result, true);
$code = $check['result'];
$reason = $check['message'];


 $reason= preg_replace('/[^A-Za-z0-9\-]/', '', $reason);
$reason = preg_replace('/[0-9]+/', '', $reason);


fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $url and  payload $payload Received $result \n");


if ($code == 1) 

{
  $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=283&op=GEMEZZ_DAILY_OZOCA');

  if ($results == 0) //MEANS PASS
  {

    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);


    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified Successfully'));

    exit();
  }
  if ($results == 1) //MEANS BLOCK

  {
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
    exit();
  }
} else {


  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$result','$serviceDate','$time_india_one')";
  mysql_query($sql);

  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));

}
