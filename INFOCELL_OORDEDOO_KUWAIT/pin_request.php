<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kuwait');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0034";
$advName = "ALTRUSIT";
$pubName = "OLIMOB";
$serviceName = "MYINFO2CELL";
$country = "OOREDOO KUWAIT";
$user_ip = $_SERVER['REMOTE_ADDR'];
$usr = "rvYIZZmCa+dKqH0g4y2R3A==";
$pass = "StQsUnH9CraHlj3FJkbSbg==";
$users = $_SERVER['HTTP_USER_AGENT'];
$request = "pin_gen";

$fp = fopen('MYINFO2CELL_OOREDOO_KUWAIT_REQUEST_LOG' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn server $user_ip \n");

$api ="http://webapi.myinfo2cell.com/mobile_app_api.php";

$raw_data = array(
	"request"=>"$request",
	"alias"=>"9779",
	"usr"=>"$usr",
	"pass"=>"$pass",
	"package_id"=>"9455",
	"msisdn"=>"$msisdn",
	"ip"=>"$user_ip",
	"device_id"=>"$users"
);

$payload = json_encode($raw_data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://webapi.myinfo2cell.com/mobile_app_api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$payload");
$headers = array();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result= curl_exec($ch);
curl_close($ch);

fwrite($fp, "\n[$time_india_one]  after hitting the URL $api with payload $payload and got the response $result \n");

$check = json_decode($result, true);
$code = $check['result']['status'];
$reason = $check['result']['response'];
$data=$check['result']['data'];

fwrite($fp, "\n[$time_india_one]  after hitting the URL $api with payload $payload and got the response $result \n");

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$data','$reason','$serviceDate','$time_india_one')";
mysql_query($sql_subscription);

if ($code == "1") {

	echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
} else {

	echo json_encode(array('response' => 'Fail', 'errorMessage' => "$reason"));
}
?>