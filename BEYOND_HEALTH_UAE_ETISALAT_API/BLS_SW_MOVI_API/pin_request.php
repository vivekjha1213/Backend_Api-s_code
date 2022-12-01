<?php
include("../connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Europe/Stockholm');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0126";
$advName = "INHOUSE";
$pubName = "MOVIPLUS";
$serviceName = "BEYOND_LIFE_STYLE";
$country = "SWEDEN";
$MSISDN = $_GET['msisdn'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_ip = $_SERVER['REMOTE_ADDR'];


$fp = fopen('sweden_pin_generate_page_' . date("Y-m-d"), 'a');

$userSource = "http://beyondlifestyle.mobi/sweden/lp/gen_pin.php";

$userSource = urlencode("http://beyondlifestyle.mobi/sweden/lp/gen_pin.php");
$user_agent = urlencode($user_agent);
$partnerID = rand(11111111, 999999999999);


$url = "https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.9&action=initiateDOI&account=6&msisdn=$MSISDN&partnerID=$partnerID&userIP=$user_ip&userSource=$userSource&userUA=$user_agent&outputFormat=json&doiMode=pin";

// fwrite($fp, "\n[$date] the link is ready for pin generation $url \n");

//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?action=$action&MSISDN=$MSISDN&account=$account&userIp=$user_ip&userSource=$userSource&userUA=$user_agent&doiMode=$doiMode";

$response = file_get_contents($url);
$result = json_decode($response, true);

$status = $result['status'];
$id = $result['id'];
$doiModeSelected = $result['doiModeSelected'];
$carrier = $result['carrier'];

// $sql = "INSERT INTO `netsmartSwedenPinRequest` (`cid`,`msisdn`,`user_agent`,`user_ip`,`status`,`id_lat`,`doiModeSelected`,`carrier`,`date`) VALUES ('$cid','$MSISDN','$user_agent','$user_ip','$status','$id','$doiModeSelected','$carrier','$date')";
// mysql_query($sql);
$newsql = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$response','$status','$carrier','$serviceDate','$time_india_one')";
mysql_query($newsql);


fwrite($fp, "\n[$time_india_one] Received $MSISDN hitting the url $url with  Received $response and trigger Query $sql \n");

if ($status == "0") {
  echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
  exit();
} else {

  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn'));

  exit();
}
