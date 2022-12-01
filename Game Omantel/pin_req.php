<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$cid="OLIMOB";
$msisdn=$_GET['msisdn'];
$user_ip=$_SERVER['REMOTE_ADDR'];
$fp = fopen('Game_Omantel_daily_pin_req_log'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn \n");
$api="http://203.122.59.25:8080/otpHandler/omanOmantel/sendOtp";

$raw_data=array(  
        "userIdentifier"=>"$msisdn",
        "entryChannel"=>"WEB",
        "packName"=>"Game_Omantel_daily",
        "clientIP"=>"$user_ip",
        "agency"=>"clickzmedia"

);  
$payload=json_encode($raw_data);

$ch = curl_init('http://203.122.59.25:8080/otpHandler/omanOmantel/sendOtp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);

$result = curl_exec($ch);


 fwrite($fp,"\n[$time_india]  received msisdn $msisdn  payload $payload  api $api  \n");
$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);       
$code=$check['responseData']['subscriptionResult'];
$reason=$check['responseData']['subscriptionError'];


fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
if ($code=="OPTIN_PREACTIVE_WAIT_CONF") {
       
$sql_subscription = "INSERT INTO `cm_om_omentel_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$result','$reason','$time_india')";
mysql_query($sql_subscription);


echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
        die();
}
else
{
$sql_subscription = "INSERT INTO `cm_om_omentel_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$result','$reason','$time_india')";
mysql_query($sql_subscription);
echo json_encode(array('response'=> 'Fail','errorMessage' =>$reason));

                      die();
}

?>

