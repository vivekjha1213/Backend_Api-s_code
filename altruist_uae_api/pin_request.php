<?php

if ($service == "Altruist_UAE_Etisalat") {

  include 'connect.php';
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Muscat');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0114";
  $advName = "ALTRUIST_UAE_ETISALAT";
  $pubName = "DREAM_MOBI";
  $serviceName = "Altruist_UAE_Etisalat_Etisalat_Game2play";
  $country = "UAE";
  // $clickid="OLIMOB";
  $msisdn = $_GET['msisdn'];
  $ip = $_SERVER['REMOTE_ADDR'];
  $device_id = rand(0, 99999999);
  $fp = fopen('Altruist_UAE_Etisalat_Etisalat_Game2play_pin_request_log' . date("Y-m-d"), 'a');
  fwrite($fp, "\n[$time_india]  received msisdn $msisdn tid $device_id \n");
  $api = "https://api.games2play.co/api/subscription";
  $raw_data = array(
    "request" => "pin_gen",
    "alias" => "9779",
    "usr" => "digi_fish",
    "pass" => "6sDtJpwC4gQ",
    "package_id" => "1591",
    "msisdn" => $msisdn,
    "ip" => $ip,
    "device_id" => "$device_id",
    "source_id" => "2"
  );
  $payload = json_encode($raw_data);
  $ch = curl_init('https://api.games2play.co/api/subscription');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLINFO_HEADER_OUT, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


  // Set HTTP Header for POST request
  curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($payload)
    )
  );

  // Submit the POST request
  $result = curl_exec($ch);
  //JUGAAD 
  $result = str_replace(':015,', ':"015",', $result);

  $response = json_decode($result, true);
  $code = $response['result']['status'];
  $reason = $response['result']['response'];
  // print_r($result);
  $tid = $response['result']['transaction_id'];
  $request_id = $response['result']['data'];

  fwrite($fp, "\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");

  
  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$tid','$request_id','$serviceDate','$time_india_one')";
  mysql_query($sql_subscription);

  if ($code == "1") {
    // $sql = "INSERT INTO  `gulftech_game2play_uae_etisalat_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`,`request_id`) VALUES('" . $clickid . "','" . $msisdn . "','" . $result . "','" . $reason . "','" . $time_india . "','" . $tid . "','" . $request_id . "')";
    // mysql_query($sql);

    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  } else {
 
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Invalid_Msisdn'));
    exit();
  }
}
