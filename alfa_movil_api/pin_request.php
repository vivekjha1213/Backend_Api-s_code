<?php

if ($service_run == "ALFAMOVI_FIGHTMENIA") {

  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Dubai');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0111";
  $advName = "ALFAMOVIL";
  $pubName = "DREAM-MOBI";
  $serviceName = "FIGHT MANIA";
  $country = "UAE";
  $msisdn = $_GET['msisdn'];
  $tid = rand();
  $fp = fopen("Alfamovil_Ae_pin_request" . $time_india, "a");
  fwrite($fp, "\n $time_india_one Received $msisdn with Tid $tid  \n");

  $url = "http://202.143.97.40/adpokeinapp/cnt/inapi/pin/send?msisdn=$msisdn&cmpid=75&txid=$tid";


  $result = file_get_contents($url);

  $check = json_decode($result, true);
  $code = $check['response'];
  $reason = $check['errorMessage'];

  fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $url and Received $result \n");


  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$tid','$serviceDate','$time_india_one')";

  mysql_query($sql_subscription);

  if ($code == "SUCCESS") {

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();

  } else {
 
    echo json_encode(array('response' => 'Fail', 'errorMessage' => $reason));
    exit();
  }
}
