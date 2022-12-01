<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
$cid="OLIMOB";
$msisdn=$_GET['msisdn'];
// $pswd='$!gIfi$h_14';
// $pswd1=urlencode($pswd);
// $server =  $_SERVER['HTTP_USER_AGENT'];
// $server1=urlencode($server);
// $ip = $_SERVER['REMOTE_ADDR'];
// $appurl=$_SERVER['HTTP_HOST'];
$tid=rand();

$fp=fopen("ENTERTAINMENT_GALAXY_Pin_Request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid  \n");

$url="http://202.143.97.40/adpokeinapp/cnt/inapi/pin/send?msisdn=$msisdn&cmpid=91&txid=$tid";

// print_r($url);
// die();


//  print_r($url);
// die();
$result=file_get_contents($url);
  // print_r($result);
  //    die();

// $str = $result;
  // print_r($str);
  //  die();
 $str1 = substr($result, 13,7);
//  print_r($str1);
//  die();
// $len=strlen($result);
// $tid1 = substr($result,12,$len);
//  print_r($tid1);
//     die();

fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");

if($str1=="SUCCESS")


{

$sql_subscription = "INSERT INTO `ENTERTAINMENT_GALAXY_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$str1','$time_india_one','$tid')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OTP Sent...'));
}
else
{
$sql_subscription = "INSERT INTO `ENTERTAINMENT_GALAXY_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$str1','$time_india_one','$tid')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong msisdn'));

}
?>