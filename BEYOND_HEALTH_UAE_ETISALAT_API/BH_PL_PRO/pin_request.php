

<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Gaza');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0095";
$advName = "INHOUSE";
$pubName = "AFFLINK";
$serviceName = "BEYOND_HEALTH";
$country = "PS-Jawal";
$MSISDN = $_GET['msisdn'];


$MerchantID = 51;
$ServiceID = 227;
$PurchaseTypeId = 2;
$TransactionChannel = "Wifi";
$Operator = "JW";
$transactionId = rand();

// date_default_timezone_set("Asia/Calcutta");
// $date_india=date("Y-m-d h:i:s");
// date_default_timezone_set("Asia/Gaza");
// $date_palestine=date("Y-m-d h:i:s");

/////////////////////// AUTHORIZATION //////////////////////////

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://dcb.universe-telecom.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "username=digifish3&password=digifish3@ut&grant_type=password");
$headers = array();
$headers[] = 'Authentication: Bearer ZGlnaWZpc2gzOmRpZ2lmaXNoM0B1dA=='; //Where from this has come.. from client??
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);


$response = json_decode($result, true);
$token = $response['access_token'];

//////////////////////////// PIN Request FOR WIFI FLOW ////////////////////////////////////WORKING FINE TILL HERE

$fp = fopen("Jawwal_Palestine_pin_request_" . $date_india, "a");
fwrite($fp, "\n $date_india Received $MSISDN with transactionId $transactionId And Result $result\n");

$api = "http://dcb.universe-telecom.com/api/v2/Subscription/Pincode";
$raw_data = array(
        "MerchantID" => $MerchantID,
        "ServiceID" => $ServiceID,
        "PurchaseTypeId" => $PurchaseTypeId,
        "MSISDN" => $MSISDN,
        "TransactionChannel" => $TransactionChannel,
        "Operator" => $Operator
);
$payload = json_encode($raw_data);
$ch = curl_init($api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$headers = array();
$headers[] = "Authentication: Bearer ZGlnaWZpc2gzOmRpZ2lmaXNoM0B1dA==";
$headers[] = "Authorization:Bearer $token";
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$APIResult = curl_exec($ch);
curl_close($ch);
$result = json_decode($APIResult, true);

$IDv = $result['PinInfo']['ID'];
$status = $result['ErrorCode'];

fwrite($fp, "\n[$time_india_one] Received $MSISDN hit the api $api and Received $APIResult\n");

$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$APIResult','$IDv','$token','$serviceDate','$time_india_one')";
mysql_query($sql_subscription);

if ($status == "Ok") {
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
} else {



        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong phone number'));
        exit();
}



?>
