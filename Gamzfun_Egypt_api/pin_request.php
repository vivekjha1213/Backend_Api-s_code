<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
date_default_timezone_set('Africa/Cairo');
$serviceDate=date("Y-m-d H:i:s");
$msisdn=$_GET['msisdn'];
$operator=$_GET['operator'];
$serviceId="ARSH0023";
$advName="CLICKANDGET"; 
$pubName="OLIMOB";
$serviceName="GAMZFUN";
$country="EGYPT";



if ($operator=="60201") {
    $op="orange";
}
else if ($operator=="60202") {
   $op="voda";
}
else if($operator=="60203"){

  $op="etit";

}
else{
    $op="We";
}
$fp=fopen("Egypt_All_Op_Pin_Request_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with $op \n");

$url="http://3.111.156.11/inapp/pingen.php?pubid=3&co=eg&op=$op&msisdn=$msisdn";

$result=file_get_contents($url);

// print_r($result);
//  die();

$string="$result";
 $a='""';
 $b = str_replace("'",$a,$string);
$check=json_decode($b,true);
$code=$check['response'];
$reason=$check['errorMessage'];


fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");

if($code=="SUCCESS")
{
  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$op','$result','$serviceDate','$time_india_one')";

  mysql_query($sql_subscription);
 echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
}
else
{
  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$op','$result','$serviceDate','$time_india_one')";

  mysql_query($sql_subscription);

 echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong msisdn'));
}


?>

