<?php

include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$clickid="OLIMOB";
$msisdn=$_GET['msisdn'];

$fp = fopen('qatar_oreedoo_pin_req'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn key $key \n");
$api="http://172.16.203.23:8080/batelcobahrainotpBilling/request/sendotp";

$raw_data=array(
        "MSISDN"=>"$msisdn",
        "packName"=>"hry",
        "channel"=>"WEB"
);  
$payload=json_encode($raw_data);
$ch = curl_init('http://172.16.203.23:8080/batelcobahrainotpBilling/request/sendotp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);

$result = curl_exec($ch);
print_r($result);
die();
 fwrite($fp,"\n[$time_india]  received msisdn $msisdn  payload $payload  api $api  \n");
$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);
$code=$check['statusCode'];        
$reason=$check['statusDescription'];

fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
if ($code=="200") {
       $sql="INSERT INTO  `qatar_oreedoo_pin_req` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`data`) VALUES('".$clickid."','".$msisdn."','".$result."','".$reason."','".$time_india."','".$reason."')";

       mysql_query($sql);
         // print_r($sql);
       echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
}
else{
  $sql="INSERT INTO  `qatar_oreedoo_pin_req` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`data`) VALUES('".$clickid."','".$msisdn."','".$result."','".$reason."','".$time_india."','".$reason."')";
      //  print_r($sql);
      //  die();
  mysql_query($sql);
  echo json_encode(array('response'=> 'Fail','errorMessage' =>$reason));
} 




?>