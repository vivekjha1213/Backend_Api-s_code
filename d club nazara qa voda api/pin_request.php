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
$fp=fopen("nazara_dc_qa_voda_pin_request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server $pswd\n");

$url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=SENDPIN&opco=dcqavodafone&service=DC&msisdn=$msisdn&channel=INAPP&source=nazara&amount=30&ip=$ip&useragent=$server1&transid=$tid&nzuname=digifish&nzpwd=$pswd";

$result=file_get_contents($url);

// print_r($result);
// die();

$str = $result;
$str1 = substr($str,3);
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");


if($result=="OK|SUCESS")
{

$sql_subscription = "INSERT INTO `nazara_dc_qa_voda_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$result','$result','$time_india_one')";
mysql_query($sql_subscription);
echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
        die();
}
else
{
$sql_subscription = "INSERT INTO `nazara_dc_qa_voda_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$result','$result','$time_india_one')";
mysql_query($sql_subscription);
echo json_encode(array('response'=> 'Fail','errorMessage' =>$str1));
                      die();

}

?>