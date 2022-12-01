<?php

include 'connect.php';
date_default_timezone_set("Asia/Calcutta");
$date = date("Y-m-d H:i:s");
$fp = fopen('lat_pin_generate_page_' . date("Y-m-d"), 'a');
$msisdn = $_GET['msisdn'];
//37125544473
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

/*$userSource=urlencode("http://beyondlifestyle.mobi/lat/lp/gen_pin.php");
    $user_agent=urlencode($user_agent);*/

$userSource = "http://beyondlifestyle.mobi/lat/lp/gen_pin.php";

$userSource = urlencode("http://beyondlifestyle.mobi/lat/lp/pin_request.php");
$user_agent = urlencode($user_agent);
$partnerID = rand(11111111, 999999999999);
$url = "https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?v=1.9&action=initiateDOI&account=5&msisdn=$msisdn&partnerID=$partnerID&userIP=$user_ip&userSource=$userSource&userUA=$user_agent&outputFormat=json&doiMode=pin";

fwrite($fp, "\n[$date] the link is ready for pin generation $url \n");



//$url="https://gateway.netsmart.eu/clients/digifish/GLOBAL/doi.php?action=$action&msisdn=$msisdn&account=$account&userIp=$user_ip&userSource=$userSource&userUA=$user_agent&doiMode=$doiMode";

$response = file_get_contents($url);

$result = json_decode($response, true);

// print_r($result);
$status = $result['status'];
$id = $result['id'];
$doiModeSelected = $result['doiModeSelected'];
$carrier = $result['carrier'];
$msg = $result['errorMessage'];

$sql = "INSERT INTO `netsmart_BL_lat_pin_request` (`cid`,`msisdn`,`user_agent`,`user_ip`,`status`,`id_lat`,`doiModeSelected`,`carrier`,`date`) VALUES ('OLIMOB2','$msisdn','$user_agent','$user_ip','$status','$id','$doiModeSelected','$carrier','$date')";
mysql_query($sql);

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$doiModeSelected','$response','$partnerID','$serviceDate','$time_india_one')";

mysql_query($sql_subscription);

fwrite($fp, "\n[$date] Received msisdn $msisdn HITTED THE URL $url and received :: $response \n");
if ($status == "0") {


    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
} else {

    echo json_encode(array('response' => 'Fail', 'errorMessage' => $msg));
}

fclose($fp);
