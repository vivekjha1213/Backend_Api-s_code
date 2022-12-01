<?php
if($service=="GEMEZZ_OZO_OMENTEL")
{

include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
date_default_timezone_set('Asia/Muscat');
$serviceDate=date("Y-m-d H:i:s");
$msisdn=$_GET['msisdn'];
$serviceId="ARSH0122";
$advName="OZO"; 
$pubName="OLIMOB";
$serviceName="GEMEZZ";
$country="OMAN";
$TransactionId=rand();


$fp=fopen("OZO_Pin_Request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with $op \n");

$url="http://3.6.36.130:8700/sendPin?msisdn=$msisdn&productid=20025&t=oManTel2oz1-OZO";

$result=file_get_contents($url);



$check=json_decode($result,true);
$code=$check['http_code'];


 $reason=$check['message'];

$trx_id=$check['trx_id'];
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");

if($code=="200")
{
  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$trx_id','$serviceDate','$time_india_one')";

  mysql_query($sql_subscription);
 echo json_encode(array('status'=> 0,'errorMessage' =>'Pin generated successfully'));
}
else
{
 echo json_encode(array('status'=> 1,'errorMessage' =>'wrong msisdn'));
 exit();
}


}



?>



