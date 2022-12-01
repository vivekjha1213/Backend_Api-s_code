<?php

if ($service_run == "ALFAMOVI_FIGHTMENIA") {
  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Dubai');
  $serviceDate = date("Y-m-d H:i:s");
  $time_india = date('Y-m-d');
  $serviceId = "ARSH0111";
  $advName = "ALFAMOVIL";
  $pubName = "DREAM-MOBI";
  $serviceName = "FIGHT MANIA";
  $country = "UAE";
  $msisdn = $_GET['msisdn'];
  $pin = $_GET['pin'];

  $sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "'and serviceId='ARSH0111' order by id desc limit 1";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  $tid = $row['finalStatus'];


  $fp = fopen("Alfamovil_Ae_pin_verify" . $time_india, "a");
  fwrite($fp, "\n $time_india_one Received $msisdn with cid $cid server $server\n");


  $url = "http://202.143.97.40/adpokeinapp/cnt/inapi/pin/validation?msisdn=$msisdn&cmpid=75&txid=$tid&pin=$pin";

  $result = file_get_contents($url);
  $check = json_decode($result, true);
  $code = $check['response'];
  $reason = $check['errorMessage'];


  fwrite($fp, "\n[$time_india] Received $msisdn hit the api $url and Received $result \n");
  if ($code == "SUCCESS") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=350&op=FightMania_Uae_DREAM_MOBI_Affa_movil');



    if ($results == 0) //MEANS PASS
    {

      $panda = "PASSED";


      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);

      echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
      exit();

    }

    if ($results == 1) //MEANS BLOCK

    {

      $panda = "BLOCK";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);
      echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple hit on  msisdn'));
      exit();

    }
  } else {
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$code','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
    exit();
  }
}
