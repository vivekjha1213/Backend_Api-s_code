include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
$cid="OLIMOB";
$msisdn=$_GET['msisdn'];
$tid=rand();

$fp=fopen("ENTERTAINMENT_GALAXY_Pin_Request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid  \n");

$url="http://202.143.97.40/adpokeinapp/cnt/inapi/pin/send?msisdn=$msisdn&cmpid=91&txid=$tid";

// print_r($url);
// die();


//  print_r($url);
// die();
$result=file_get_contents($url);
 $check=json_decode($result,true);
  $code=$check['response'];
  $reason=$check['errorMessage'];

fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");

if($code=="SUCCESS")


{

$sql_subscription = "INSERT INTO `ENTERTAINMENT_GALAXY_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$reason','$time_india_one','$tid')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OTP Sent...'));
}
else
{
$sql_subscription = "INSERT INTO `ENTERTAINMENT_GALAXY_Pin_Request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`)  VALUES('$cid','$msisdn','$result','$reason','$time_india_one','$tid')";
mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong msisdn'));

}
