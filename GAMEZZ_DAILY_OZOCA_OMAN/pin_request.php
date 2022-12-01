<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Muscat');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0043";
$advName = "OZOCA"; 
$pubName = "OLIMOB";
$serviceName = "GEMEZZ_DAILY";
$country = "OMAN";

$fp = fopen('GEMEZZ_DAILY_pin_request_log' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn \n");

	// curl -X POST   'http://13.250.242.221:7329/api/v2/pin/send?msisdn=96879272045&pin=1234&token=6CkpMUDuGfs8wLRWujXb&service=daily'

	$url="http://13.250.242.221:7329/api/v2/pin/send";


	$raw_data=array(  
		"msisdn"=>"$msisdn",
		"token"=>"6CkpMUDuGfs8wLRWujXb",
		"service"=>"daily"
	  
	  );  
	  $payload=json_encode($raw_data);
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://13.250.242.221:7329/api/v2/pin/send?msisdn=$msisdn&token=6CkpMUDuGfs8wLRWujXb&service=daily");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$result = curl_exec($ch);
curl_close($ch);
$check = json_decode($result, true);
$code = $check['result'];
$reason = $check['message'];

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$result','$serviceDate','$time_india_one')";

mysql_query($sql_subscription);

fwrite($fp,"\n[$time_india_one] Received $msisdn hit the api $url and payload $payload Received $result \n");

if($code=="true")
{

echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
}
else
{

echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong msisdn'));
}

?>
