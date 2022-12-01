
  <?php
  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Qatar');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0120";
  $advName = "ASCENSO"; 
  $pubName = "OLIMOB";
  $serviceName = "VDYOZINE"; 
  $country = "QATAR-OOREDOO";
  $msisdn = $_GET['msisdn'];
  $TransactionID = rand();

  $fp = fopen("Request_" . $time_india_one, "a");
  // API URL
  $url = "http://vodqtr.newstor.net/ascenso-vas-services/2024/sentOTP";

  $ch = curl_init($url);
  $data = array(
    "TransactionID" => "$TransactionID",
    "IDService" => "2041",
    "MSISDN" => "$msisdn",
    "promoMode" => "digifish"

  );
  $payload = json_encode($data);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "$url");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "$payload");
  $headers = array();
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Authorization: Base ZGlnaWZpc2hAJEMxMg==';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $check = json_decode($result, true);
  $code = $check['response'];
  $reason = $check['errorMessage'];
  

  fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url with payload $payload And Result $result\n");
  fclose($fp);

  $newsql = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$code','$TransactionID','$serviceDate','$time_india_one')";
  mysql_query($newsql);

  if ($code == "SUCCESS") {
    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  } else {
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong Msisdn'));
    exit();
  }
  ?>