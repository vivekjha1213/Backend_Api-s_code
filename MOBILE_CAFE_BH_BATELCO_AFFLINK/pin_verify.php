<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Bahrain');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0118";
$advName = "INHOUSE";
$pubName = "AFFLINK";
$serviceName = "MOBILE-CAFE";
$country = "BH-BATELCO";

$otp = $_GET['pin'];
$msisdn = $_GET['msisdn'];

//check closed by Rajendra
$key1 = "fnSE14QdAGB4vuYs";
$referenceurl = $_SERVER['HTTP_REFERER'];
$ip = $_SERVER['REMOTE_ADDR'];
date_default_timezone_set('UTC');
$default_timeZone = date();
$unix_time = date('Ymdhis', strtotime($default_timeZone));
$timestamp = $unix_time;
//echo $timestamp;
$plaintext = '2178#' . $timestamp;

function aes128Encrypt($key, $data)
{
  if (16 !== strlen($key)) $key = hash('MD5', $key, true);
  $padding = 16 - (strlen($data) % 16);
  $data .= str_repeat(chr($padding), $padding);
  return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

//HERE PRMOTION TYPE DEFINED
$camp = "IN APP PROMOTION";
//PRMOTION TYPE CLOSED
$fp = fopen("MOBILE_CAFE_VERIFY_" . $time_india_one, "a");

if ($_GET['pin'] != '') {
  $default_timeZone1 = date("Y-m-d H:i:s");
  //ECHO $_GET['msisdn'];
  /********************* Inbox API ********************/
  $trackingid = rand(1, 234567891011121557);

  $external_id = rand(1, 23456789101112155);
  $authen = aes128Encrypt($key1, $plaintext);
  $headers2 = array();
  //$headers2[0] = "partnerRoleId:1841";
  $headers2[0] = "apikey:c963c790348b45d59748f9c235112bbd";
  $headers2[1] = "external-tx-id:" . $external_id;
  $headers2[2] = "authentication:" . $authen;
  $headers2[3] = "Content-type: application/json";
  //"97333817512"
  $arrayData['userIdentifier'] = $_GET['msisdn'];

  $arrayData['userIdentifierType'] = "MSISDN";

  $arrayData['productId'] = "7436";

  $arrayData['mcc'] = "426";
  $arrayData['mnc'] = "01";
  $arrayData['entryChannel'] = "WEB";
  $arrayData['clientIp'] = $ip;
  $arrayData['transactionAuthCode'] = $_GET['pin'];
  $content = json_encode($arrayData);
  //print_r($content);

  $url = "https://batelcobhr-ma.timwe.com/bh/ma/api/external/v1/subscription/optin/confirm/2141";
  //ECHO $content;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  //curl_setopt($curl, CURLOPT_HTTPHEADER, array('partnerRoleId:1841','apikey:63e9645480de4e4991fc1e6a0f6d9062','external-tx-id='.$external_id,'authentication='.$authen));
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers2);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
  //curl_setopt($curl, CURLOPT_HEADER,TRUE);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  //curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));

  curl_setopt($curl, CURLOPT_POST, true);

  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

  $json_response = curl_exec($curl);
  curl_close($curl);

  $json_response1 = json_decode($json_response);
  $message = $json_response1->message;
  $inError = $json_response1->inError;
  $requestId = $json_response1->requestId;
  $code = $json_response1->code;
  $transactionId = $json_response1->responseData->transactionId;
  $externalTxId = $json_response1->responseData->externalTxId;
  $subscriptionResult = $json_response1->responseData->subscriptionResult;
  $subscriptionError = $json_response1->responseData->subscriptionError;
  $subscriptionError1 = urldecode("$subscriptionError");
  $otp = $_GET['pin'];

  date_default_timezone_set('Asia/Kolkata');
  $date_india = date('Y-m-d H:i:s');
  date_default_timezone_set('Asia/Bahrain');
  $date_bahrain = date('Y-m-d H:i:s');



  $json_response1 = json_decode($json_response, TRUE);
  $message = $json_response1['code'];
  $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
  $subscriptionError = $json_response1['responseData']['subscriptionError'];
  $operator = "BATTELCO";


  fwrite($fp, "\n[$time_india_one] Received $msisdn and OTP $otp  hitting the url $url and Received $result \n");


  if ($subscriptionResult == "OPTIN_ACTIVE_WAIT_CHARGING") {
    $result = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=355&op=MOBILE_CAFE_BH_BATELCO_INHOUSE_OLIMOB');
    if ($result == 0) //MEANS PASS
    {

      $panda = "PASSED";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$json_response','$panda','$subscriptionError','$serviceDate','$time_india_one')";
      mysql_query($sql);
      echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
      exit();
    }
    if ($result == 1) //MEANS BLOCK
    {
      $panda = "BLOCK";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$json_response','$panda','$subscriptionError','$serviceDate','$time_india_one')";
      mysql_query($sql);
      echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi Hits on the Msisdn'));
      exit();
    }
  } else {
    $panda = "WRONG PIN";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$json_response','$panda','$subscriptionError','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => "$subscriptionError"));
    exit();
  }
}
