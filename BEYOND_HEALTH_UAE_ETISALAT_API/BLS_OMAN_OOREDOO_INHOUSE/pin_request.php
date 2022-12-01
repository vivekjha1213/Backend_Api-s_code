<?php
function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}
function pin_request($cid,$msisdn)
{
    include("connect.php");
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Muscat');
    $serviceDate = date("Y-m-d H:i:s");
    $cid = "N.A";
    $msisdn = $_GET['msisdn'];
    $serviceId = "ARSH0072";
    $advName = "IN-HOUSE";
    $pubName = "OLIMOB";
    $serviceName = "BEYOND_LIFE_STYLE";
    $country = "OMAN OOREDOO";
    $msisdn = $msisdn;
    $clickid = $cid;

    $fp = fopen('OOREDOO_Pin_Request' . date("Y-m-d"), 'a');
    fwrite($fp, "\n[$time_india_one] Received  MSISDN $msisdn \n");

    $trackingid = rand(1, 234567891011121557);
    $external_id = rand(1, 23456789101112155);
    date_default_timezone_set('UTC');
    $default_timeZone = date("Y-m-d");
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
    $arrayData['productId'] = "21559";
    $arrayData['mcc'] = "422";
    $arrayData['mnc'] = "03";
    $arrayData['entryChannel'] = "WEB";
    $arrayData['largeAccount'] = "92879"; //for staging 92122 
    $arrayData['subKeyword'] = "";
    $arrayData['trackingId'] = $trackingid;
    $arrayData['clientIP'] =  $_SERVER['REMOTE_ADDR'];
    $arrayData['campaignUrl'] = "";
    $content = json_encode($arrayData);

    $url = "https://ooma.timwe.com/external/v3/subscription/optin/5600";
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

    $json_response1 = json_decode($json_response, TRUE);
    $message = $json_response1['code'];


    $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
    fwrite($fp, "\n[$time_india_one] After hitting the API $url with header $headers2 and payload $content received the response $json_response\n");
    fclose($fp);

    // $sql_subscription = "INSERT INTO `beyondlifestyle_om_ooredoo_pin_request`(`cid`,`msisdn`,`response`, `status`,`time_india_one`) VALUES ('$cid', '$msisdn','$json_response','$subscriptionResult','$time_india_one')";
    // mysql_query($sql_subscription);


    $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$message','$subscriptionResult','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);

    fwrite($fp, "\n[$time_india_one] $request getting $json_response and $status and insert $sql_subscription \n");
    fclose($fp);


    if ($message == "SUCCESS") {
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    }

    if ($subscriptionResult == 'OPTIN_ALREADY_ACTIVE') 
    {
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Already Active Subscriber'));
        exit();
    } else {

        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn'));
        // echo json_encode(array('success' => $json_response));
        exit();
    }
}

pin_request($cid, $msisdn);
