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
$fp = fopen('sweden_pin_verify_page_' . date("Y-m-d"), 'a');
$MSISDN = $_GET['msisdn'];
$pin = $_GET['pin'];

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_ip = $_SERVER['REMOTE_ADDR'];

/*$userSource=urlencode("http://beyondlifestyle.mobi/sweden/lp/otp.php");
    $user_agent=urlencode($user_agent);*/

$userSource = "http://beyondlifestyle.mobi/sweden/lp/otp.php";

$userSource = urlencode("http://beyondlifestyle.mobi/sweden/lp/otp.php");
$user_agent = urlencode($user_agent);
$partnerID = $_GET['partnerID'];
//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.1&action=verifypin&partnerOptinID=$partnerID&userPIN=$pin";
$url = "https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.1&action=verifypin&partnerOptinID=$partnerID&userPIN=$pin&account=6&outputFormat=json";

//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?action=$action&MSISDN=$MSISDN&account=$account&userIp=$user_ip&userSource=$userSource&userUA=$user_agent&doiMode=$doiMode";
fwrite($fp, "\n[$time_india_one] The final link is created for pin verify $url \n");

$response = file_get_contents($url);
$result = json_decode($response, true);


$status = $result['status'];
$id = $result['id'];
$doiModeSelected = $result['doiModeSelected'];
$carrier = $result['carrier'];

// $sql = "INSERT INTO `netsmartSwedenPinVerify` (`cid`,`msisdn`,`user_agent`,`user_ip`,`status`,`id_lat`,`doiModeSelected`,`carrier`,`date`,`otp`) VALUES ('$cid','$MSISDN','$user_agent','$user_ip','$status','$id','$doiModeSelected','$carrier','$date','$pin')";
// mysql_query($sql);

fwrite($fp, "\n[$time_india_one] Received MSISDN $MSISDN HITTED THE URL $url and received :: $response \n");

if ($status == 0) {

  $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=363&op=Beyond_Life_Style_Moviplus_Inhouse_SwedeN');
  if ($results == 0) //MEANS PASS
  {
    $panda = "PASSED";
    $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$pin','$response','$panda','$status','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  }

  if ($results == 1) //MEANS BLOCK
  {
    $panda = "BLOCK";
    $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$pin','$response','$panda','$status','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'multiple hits on this msisdn'));
    exit();
  }
} else {

  $panda="wrong pin";

  $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$pin','$response','$panda','$status','$serviceDate','$time_india_one')";
  mysql_query($sql_subscription);
  echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
  exit();
}
