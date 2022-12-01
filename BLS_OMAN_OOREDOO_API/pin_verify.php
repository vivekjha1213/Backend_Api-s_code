<?php


function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}



$msisdn = $_GET['msisdn'];
//  $cid = $_REQUEST['cid'];
$cid = "N.A";
$otp = $_GET['pin'];

function pin_verify($cid, $msisdn, $otp)
{

    include("connect.php");
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Muscat');
    $serviceDate = date("Y-m-d H:i:s");
    $msisdn = $_GET['msisdn'];
    $serviceId = "ARSH0076";
    $advName = "IN-HOUSE";
    $pubName = "AFFLINK";
    $serviceName = "BEYOND_LIFE_STYLE";
    $country = "OMAN OOREDOO";

    $trackingid = rand(1, 234567891011121557);
    $external_id = rand(1, 23456789101112155);
    date_default_timezone_set('UTC');
    $default_timeZone = date();
    $unix_time = date('Ymdhis', strtotime($default_timeZone));
    $key1 = "AHdfT2rMp41xrC4P";
    $timestamp = $unix_time;
    $plaintext = '5495#' . $timestamp;
    $authen = aes128Encrypt($key1, $plaintext);
    $headers2 = array();
    $headers2[0] = "apikey:b087ed2345834cd88c8883d35a0b6b99";
    $headers2[1] = "external-tx-id:" . $external_id;
    $headers2[2] = "authentication:" . $authen;
    $headers2[3] = "Content-type: application/json";

    $arrayData['userIdentifier'] = $msisdn;
    $arrayData['userIdentifierType'] = "MSISDN";
    $arrayData['productId'] = "21558";  //for weekly

    // productId

    // 21565	Beyond Lifestyle Monthly
    // 21558	Beyond Lifestyle Weekly
    // 21559	Beyond Lifestyle Daily


    $arrayData['mcc'] = "422";
    $arrayData['mnc'] = "03";
    $arrayData['entryChannel'] = "WEB";
    $arrayData['clientIP'] = $_SERVER['REMOTE_ADDR'];
    $arrayData['transactionAuthCode'] = $otp;
    $content = json_encode($arrayData);

    //$url = "https://ooma.timwe.com/external/v3/subscription/confirm/5600";
    $url = "https://ooma.timwe.com/external/v3/subscription/optin/confirm/5600";
    // $url = "https://ooma.timwe.com/external/v3/subscription/optin/5600";
    // $url = "https://ooma.timwe.com/external/v3/subscription/optin/confirm/5600";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
    // $otp = $_REQUEST['otp'];
    date_default_timezone_set('Asia/Kolkata');
    $time_india = date('Y-m-d H:i:s');
    $fp = fopen('OOredoo_Pin_Verify_' . date("Y-m-d"), 'a');
    fwrite($fp, "\n[$time_india] Received  MSISDN $msisdn and cid $cid and otp $otp\n");
    $json_response1 = json_decode($json_response, TRUE);
    $message = $json_response1['code'];
    // print_r($message);
    // die();
    $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
    $subscriptionError = $json_response1['responseData']['subscriptionError'];
    fwrite($fp, "\n[$time_india] After hitting the API $url with header $headers2 and payload $content received the response $json_response  \n");
    fclose($fp);

    // $sql_subscription = "INSERT INTO `beyondlifestyle_om_ooredoo_pin_verify`(`cid`,`msisdn`,`response`, `status`,`date_india`,`otp`) VALUES ('$cid', '$msisdn','$json_response','$subscriptionResult','$time_india','$otp')";
    // mysql_query($sql_subscription);


    fwrite($fp, "\n[$time_india] $request getting $json_response1 and $subscriptionResult and insert $sql_subscription \n");
    fclose($fp);

    if ($subscriptionResult == "OPTIN_ACTIVE_WAIT_CHARGING") {

//Fileter for P,B
         $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=313&op=TIMWE_BLS_OM_OOREDOO_INHOUSE_OLIMOB');

        if ($results == 0) //MEANS PASS
        {
            $panda = "PASSED";
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$panda','$json_response','$serviceDate','$time_india_one')";
            mysql_query($sql);

            fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");

            echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
            exit();
        }
        if ($results == 1) //MEANS BLOCK

        {
            $panda = "BLOCK";
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$panda','$json_response','$serviceDate','$time_india_one')";
            mysql_query($sql);
            fwrite($fp, "\n $time_india_one Received $msisdn Query $sql \n");

            echo json_encode(array('response' => 'Fail', 'errorMessage' => 'server error'));

            exit();
        }
    } else 
    
    {
        $send = "wrong pin";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$send','$json_response','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
    }

    // if ($subscriptionResult == "OPTIN_ACTIVE_WAIT_CHARGING") {
    //     echo json_encode(array('success' => 1));
    // } else {

    //     echo json_encode(array('success' => $subscriptionResult));
    // }


}

pin_verify($cid, $msisdn, $otp);
