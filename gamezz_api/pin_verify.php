<?php
if ($service == "GEMEZZ_OZO_OMENTEL") {
  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  $time_india = date('Y-m-d');
  date_default_timezone_set('Asia/Muscat');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0122";
  $advName = "OZO";
  $pubName = "OLIMOB";
  $serviceName = "GEMEZZ";
  $country = "OMAN";

  $msisdn = $_GET['msisdn'];
  $pin = $_GET['pin'];

  //  $sql="SELECT `status` from `in_app_pin_request` where `msisdn`='".$msisdn."' order by id desc limit 1";
  //  $result=mysql_query($sql);
  //  $row = mysql_fetch_array($result);
  //  $op=$row['status'];


  $fp = fopen("OZO_All_Op_Pin_Verify_log" . $time_india, "a");
  fwrite($fp, "\n $time_india_one Received $msisdn with $op \n");


  //  die();



  $ch = curl_init();
  $url = "http://3.6.36.130:8700/verifyPin?msisdn=$msisdn&productid=20025&pin=$pin&t=oManTel2oz1-OZO";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);
  $check = json_decode($result, true);
  $code = $check['http_code'];


  $reason = $check['message'];

  $reason = preg_replace('/[0-9]+/', '', $reason);


  fwrite($fp, "\n[$time_india] Received $msisdn hit the api $url and Received $result \n");
  if ($code == "200") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=359&op=GEMEZZ_OZO_OMANEL_Switch');


    if ($results == 0) //MEANS PASS
    {

      $panda = "PASSED";


      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);


      echo json_encode(array('status' => 0, 'errorMessage' => 'Pin verified successfully'));

      exit();
    }

    if ($results == 1) //MEANS BLOCK

    {

      $panda = "BLOCK";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);

      echo json_encode(array('status' => 1, 'errorMessage' => 'Multiple Hits on msisdn'));

      exit();
    }
  } else {
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$reason','$serviceDate','$time_india_one')";

    mysql_query($sql);

    echo json_encode(array('status' => 1, 'errorMessage' => 'wrong pin'));
  }
}
