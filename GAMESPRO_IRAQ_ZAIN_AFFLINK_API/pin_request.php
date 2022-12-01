  <?php
  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india_one = date("Y-m-d H:i:s");
  date_default_timezone_set('Asia/Baghdad');
  $serviceDate = date("Y-m-d H:i:s");
  $serviceId = "ARSH0132";
  $advName = "INHOUSE";
  $pubName = "AFFLINK";
  $serviceName = "gamespro";
  $country = "IRAQ_ZAIN";
  $TransactionId = rand(111111111111111,999999999999999);
  $msisdn = $_GET['msisdn'];
  $ip = $_SERVER['REMOTE_ADDR'];



  $fp = fopen("Request_" . $time_india_one, "a");

  fwrite($fp, "\n[$time_india_one] we fetched the tid $TransactionId for msisdn $msisdn  \n");

  //Chceking the msisdn is blocked or not
  $url = "http://vascld-afl.mcomviva.com:8112/AFL/checkBlockedMDN?key=iQ9Mt1/=&comp_id=102&op_id=4&con_type=813&msisdn=$msisdn";

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "$url");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);

   if($result!=0)
   {
     fwrite($fp, "\n[$indiaTimeZone] The Msisdn $msisdn is blocked at telco end with response $result \n");
     echo json_encode(array('response' => 'Fail', 'errorMessage' => 'MSISDN IS BLOCKED BY TELECOM'));
     exit();
   }


  curl_close($ch);

  //Pin Request URL
  $url = "http://vascld-cgw.mcomviva.com:8093/API/CGRequest?MSISDN=$msisdn&productID=ZnIQ-GamesPro&pName=Mooditt+Games+Pro&pPrice=200&pVal=1&CpId=WAPKD-08&CpPwd=WAPKD08&CpName=ARSHIYA-ZI&reqMode=APP&reqType=Subscription&ismID=17&transID=$TransactionId&sRenewalPrice=0&sRenewalValidity=0&request_locale=en&srcIP=$ip&serviceType=default&planId=default&opId=102&contentId=default&optionalParameter1=1001&optionalParameter2=1002";

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "$url");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);

  curl_close($ch);
  $resultLoad = simplexml_load_string($result);
  $resultEncode = json_encode($resultLoad);
  // Convert into associative array
  $resultArray = json_decode($resultEncode, true);
  $errorCode = $resultArray['error_code'];
  $errorDesc = $resultArray['errorDesc'];


  fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url and Received $result  and status $errorDesc\n");

  $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$resultEncode','$errorDesc','$TransactionId','$serviceDate','$time_india_one')";
  mysql_query($sql_subscription);


  fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url with  Received $result and trigger Query $sql \n");

  if ($errorCode == "0") {
    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  } else {
    echo json_encode(array('response' => 'Fail', 'errorMessage' => $errorDesc));

    exit();
  }
  ?>