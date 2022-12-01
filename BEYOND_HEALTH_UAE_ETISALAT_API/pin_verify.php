<?php
include 'connect.php';
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$operator = "42402";

date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Dubai');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0105";
$advName = "IN-HOUSE";
$pubName = "DREAM_MOBI";
$serviceName = "BEYOND_HEALTH";
$country = "UAE";

$language = "en";
$transactionId = $_POST['transactionId'];
$PublicKey = "xOyCvw0OLxhz5UGo3t4B";
$PrivateKey = "EQHB82KCv4vX9nn5K7KR";

//FOR FIRST AND SECOUND TIME USERS EXTRA PARAMETER
$sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' and serviceId='ARSH0105' order by id limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$code = $row['finalStatus'];
$SubscriptionContractId = $code;
//FOR FIRST AND SECOUND TIME USERS EXTRA PARAMETER
$Message = $SubscriptionContractId . $pin . $transactionId . $charge;
$date = date("Y-m-d h:i:s");
$request = $request = rand(111, 99999);
$fp = fopen('beyondh_pin_verify_log' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$date] $request received MSISDN $msisdn OPERATOR $operator pin $pin LANGUAGE $language subscription id $SubscriptionContractId transction id $transactionId and message $Message\n");
$Digest = $PublicKey . ":" . hash_hmac("sha256", $Message, $PrivateKey);
$verify_data = array("signature" => $Digest, "pinCode" => $pin, "subscriptionContractId" => $SubscriptionContractId, "charge" => $charge, "transactionId" => $transactionId);
$payload = json_encode($verify_data);
//$api_staging="http://staging.tpay.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract";
$api = "http://live.tpay.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract";
// Prepare new cURL resource
$ch = curl_init('http://live.tpay.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract');
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
$response = json_decode($result, true);

// Close cURL session handle
curl_close($ch);
$final_response = $response['errorMessage'];
$operationStatusCode = $response['operationStatusCode'];
fwrite($fp, "\n[$date] $request received MSISDN $msisdn after hitting URL $api with payload $payload recieve result $result and response $final_response\n");
fclose($fp);
if ($operationStatusCode == "0") {

  $result_one = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=58&op=BEYONDHEALTH_UAE_TAPY_PR0_ONE');
  if ($result_one == 0) //MEANS PASS
  {
    $panda = "PASSED";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$final_response','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
    exit();
  }
  if ($result_one == 1) //MEANS BLOCK
  {
    $panda = "BLOCK";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$final_response','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi hit on the msisdn'));
    exit();
  }
} else {
  //  $sql="INSERT INTO  `tpay_bh_pin_verify` (`cid`,`msisdn`,`response`,`contract_id`) VALUES('".$cid."','".$msisdn."','".$final_response."','".$SubscriptionContractId."')";

  $panda = "$final_response";
  $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$final_response','$serviceDate','$time_india_one')";
  mysql_query($sql);
  echo json_encode(array('response' => 'Fail', 'errorMessage' => $final_response));
  exit();
}
