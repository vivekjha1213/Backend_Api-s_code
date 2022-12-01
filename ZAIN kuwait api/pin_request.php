<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
$cid="OLIMOB";
$msisdn=$_GET['msisdn'];
$pswd='$!gIfi$h_14';
$pswd1=urlencode($pswd);
$server =  $_SERVER['HTTP_USER_AGENT'];
$server1=urlencode($server);
$tid=rand();
$ip = $_SERVER['REMOTE_ADDR'];
$appurl=$_SERVER['HTTP_HOST'];
// $tid=rand();

$fp=fopen("Zain_kuwait_NZ_Pin_Request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server \n");



$url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=SENDPIN&opco=zainkw&service=GC&msisdn=$msisdn&channel=INAPP&source=digifish&pid=4&ip=$ip&useragent=$server1&nzuname=digifish&nzpwd=$pswd";




//  print_r($url);
// die();
$result=file_get_contents($url);

//  print_r($result);
//   die();

$str = $result;
$str1 = substr($str, 0,2);
$len=strlen($result);
$tid1 = substr($result,12,$len);
//  print_r($tid1);
//     die();
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");


if($str1=="OK")
{

$sql_subscription = "INSERT INTO `Zain_kuwait_NZ_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$str1','$time_india_one','$tid1')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
}
else
{
$sql_subscription = "INSERT INTO `Zain_kuwait_NZ_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$str1','$time_india_one','$tid1')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong msisdn'));

}
?>