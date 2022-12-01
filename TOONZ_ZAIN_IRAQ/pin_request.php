
  <?php

  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Baghdad');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0121";
  $advName = "U2OPIA";
  $pubName = "OLIMOB";
  $serviceName = "TOONZ";
  $country = "IRAQ-ZAIN";
  $msisdn = $_GET['msisdn'];
  $transactionId = rand();

  $fp = fopen("TOONZ_Request_" . $time_india_one, "a");
  //fwrite($fp, "\n[$time_india_one] Received $msisdn \n");
  // API URL
  $url = "http://3.109.105.30:8181/InappZainIraq/sendOtp?msisdn=$msisdn&serviceName=INMTNZ&operatorId=ZIQ&transactionId=$transactionId&vendorId=ZainIraq&serviceId=270&shortCode=4089";

  $result = file_get_contents($url);

  $check = json_decode($result, true);

  $code = $check['success'];
  $reason = $check['statusDescription'];
  $TransactionId = $check['transactionId'];

  fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url with payload $payload And Result $result\n");
  fclose($fp);

  $newsql = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$code','$TransactionId','$serviceDate','$time_india_one')";
  mysql_query($newsql);

  if ($code == "true") {

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  } else {
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong Msisdn'));
    exit();
  }
  ?>