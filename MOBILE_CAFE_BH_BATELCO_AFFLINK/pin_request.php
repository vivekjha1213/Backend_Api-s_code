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


$unix_time = date('Ymdhis', strtotime($default_timeZone));
$key1 = "fnSE14QdAGB4vuYs";
$timestamp = $unix_time;
$plaintext = '2178#' . $timestamp;
function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}



//HERE PRMOTION TYPE DEFINED
$campaign = "IN APP PROMOTION";
$msisdn = $_GET['msisdn'];
$ip = $_SERVER['REMOTE_ADDR'];

$fp = fopen("MOBILE_CAFE_Request_" . $time_india_one, "a");
//PRMOTION TYPE CLOSED
if ($msisdn != '') {


    $default_timeZone1 = date("Y-m-d H:i:s");

    $trackingid = rand(1, 234567891011121557);

    $external_id = rand(1, 23456789101112155);
    $authen = aes128Encrypt($key1, $plaintext);

    $headers2 = array();
    //$headers2[0] = " 63e9645480de4e4991fc1e6a0f6d9062";
    $headers2[0] = "apikey:c963c790348b45d59748f9c235112bbd";
    $headers2[1] = "external-tx-id:" . $external_id;
    $headers2[2] = "authentication:" . $authen;
    $headers2[3] = "Content-type: application/json";
    //"97333817512"
    $arrayData['userIdentifier'] = $msisdn;

    $arrayData['userIdentifierType'] = "MSISDN";
    //staging prodcutID:4769
    $arrayData['productId'] = "7436";

    $arrayData['mcc'] = "426";
    $arrayData['mnc'] = "01";
    $arrayData['entryChannel'] = "WAP";

    $arrayData['largeAccount'] = "94436";

    $arrayData['subKeyword'] = "SUB";
    //ECHO$trackingid;
    $arrayData['trackingId'] = $trackingid;

    $arrayData['clientIp'] = $ip;

    $arrayData['campaignUrl'] = "";
    $content = json_encode($arrayData);
    //var_dump($content);
    $url = "https://batelcobhr-ma.timwe.com/bh/ma/api/external/v1/subscription/optin/2141";
    // $content;
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
    //print_r($json_response);
    //exit();
    date_default_timezone_set('Asia/Kolkata');
    $time_india = date('Y-m-d H:i:s');
    $json_response1 = json_decode($json_response, TRUE);
    // echo "<pre>";
    // print_r($json_response);

    $newsql = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$subscriptionError1','$serviceDate','$time_india_one')";
    mysql_query($newsql);

    fwrite($fp, "\n[$time_india_one] Received $msisdn hitting the url $url and Received $result QUERY $newsql \n");


    if ($subscriptionResult == "OPTIN_PREACTIVE_WAIT_CONF") {

        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();

    } 
     else {     
        echo json_encode(array('response' => 'Fail', 'errorMessage' => $reason));
        exit();
    }

}