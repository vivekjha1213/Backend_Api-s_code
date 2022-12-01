<?php

//Qatar Oreedoo API_Digi
//http://45.114.143.51/eapi/d3s/pingenerate
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$clickid="OLIMOB";
$msisdn=$_GET['msisdn'];
$key=$_GET['key'];

// $op=$_GET['operator'];
// if ($op=="VOda") {
//     $PromoID="28672";
// }
// if ($op=="orange") {
//     $PromoID="28675";
// }
// if ($op=="etislat") {
//     $PromoID="28678";
// }

$tid=rand();

$fp = fopen('sheemaro_egyot_pin_request'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
$api="http://45.114.143.51/eapi/d3s/pingenerate";

$raw_data=array(
        "MSISDN"=>"$msisdn",
        "key"=>"b7ruarry"
        // "PromoID"=>"$PromoID",
        // "PartnerID"=>8,
        // "TransactionID"=>"$tid",

);
$payload=json_encode($raw_data);
$ch = curl_init('http://45.114.143.51/eapi/d3s/pingenerate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    // Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);

    // Submit the POST request
$result = curl_exec($ch);

$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);

$code=$check['Message'];        
$tid=$check['TransactionID'];

fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
if ($code=="Success") {
       $sql="INSERT INTO  `sheemaro_egypt_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`data`) VALUES('".$clickid."','".$msisdn."','".$result."','".$code."','".$time_india."','".$tid."')";

       mysql_query($sql);
         // print_r($sql);
       echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
}
else{
  $sql="INSERT INTO  `sheemaro_egypt_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`data`) VALUES('".$clickid."','".$msisdn."','".$result."','".$code."','".$time_india."','".$tid."')";
      // print_r($sql);
  mysql_query($sql);
  echo json_encode(array('response'=> 'Fail','errorMessage' =>$code));
} 




?>