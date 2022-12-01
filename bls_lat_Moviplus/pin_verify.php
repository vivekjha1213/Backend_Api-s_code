
<?php

include 'connect.php';
date_default_timezone_set("Asia/Calcutta");
$date = date("Y-m-d H:i:s");
$fp = fopen('lat_pin_verify_page_' . date("Y-m-d"), 'a');
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];

date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Europe/Riga');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0066";
$advName = "MOVIPLUS";
$pubName = "IN-HOUSE";
$serviceName = "BEYOND_LIFESTYLE";
$country = "LATVIA";


$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_ip = $_SERVER['REMOTE_ADDR'];

/*$userSource=urlencode("http://beyondlifestyle.mobi/lat/lp/otp.php");
    $user_agent=urlencode($user_agent);*/

$userSource = "http://beyondlifestyle.mobi/lat/lp/otp.php";

$userSource = urlencode("http://beyondlifestyle.mobi/lat/lp/pin_verify.php");
$user_agent = urlencode($user_agent);
$sql = "SELECT `finalStatus` from `in_app_pin_request` WHERE `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$partnerID = $row['finalStatus'];
// echo $partnerID;
//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.1&action=verifypin&partnerOptinID=$partnerID&userPIN=$pin";
$url = "https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.1&action=verifypin&partnerOptinID=$partnerID&userPIN=$pin&account=5&outputFormat=json";

//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?action=$action&MSISDN=$MSISDN&account=$account&userIp=$user_ip&userSource=$userSource&userUA=$user_agent&doiMode=$doiMode";
fwrite($fp, "\n[$date] The final link is created for pin verify $url \n");

$response = file_get_contents($url);

$result = json_decode($response, true);


$status = $result['status'];
$id = $result['id'];
$doiModeSelected = $result['doiModeSelected'];
$carrier = $result['carrier'];

$sql = "INSERT INTO `netsmart_BL_lat_pin_verify` (`cid`,`msisdn`,`user_agent`,`user_ip`,`status`,`id_lat`,`doiModeSelected`,`carrier`,`date`,`otp`) VALUES ('OLIMOB2','$MSISDN','$user_agent','$user_ip','$status','$id','$doiModeSelected','$carrier','$date','$pin')";
mysql_query($sql);

fwrite($fp, "\n[$date] Received MSISDN $MSISDN HITTED THE URL $url and received :: $response \n");
if ($status == 0) {
  $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=300&op=lat_bls_Moviplus_Inhouse');

  if ($results == 0) //MEANS PASS
  {

    $panda = "PASSED";


    // $sql_subscription = "INSERT INTO `beyondlifestyle_uae_alt_pin_verify`(`cid`,`msisdn`,`response`, `status`,`date_india`,`otp`) VALUES ('$clickid', '$msisdn','$response','$status','$date_india','$otp')";
    // mysql_query($sql_subscription);

    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$response','$panda','$status','$serviceDate','$time_india_one')";
    mysql_query($sql);


    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));

    exit();
  }
  if ($results == 1) //MEANS BLOCK

  {

    $panda = "BLOCK";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$response','$panda','$status','$serviceDate','$time_india_one')";
    mysql_query($sql);

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'server error'));
    exit();
  }
} else {

  $status = "Failed";
  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$response','$status','$status','$serviceDate','$time_india_one')";
  mysql_query($sql);

  echo json_encode(array('response' => 'Fail', 'errorMessage' => $status));
}

fclose($fp);
?>

