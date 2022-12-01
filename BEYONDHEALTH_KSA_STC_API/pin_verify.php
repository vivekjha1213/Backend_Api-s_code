<?php
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$cid = "N.A";
launcher($msisdn, $pin, $cid);

function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

function launcher($msisdn, $pin, $clickid)
{
    include("connect.php");
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Riyadh');
    $serviceDate = date("Y-m-d H:i:s");
    $serviceId = "ARSH0083";
    $advName = "IN-HOUSE";
    $pubName = "AFFLINK";
    $serviceName = "BEYOND_HEALTH";
    $country = "KSA";
    $msisdn = $msisdn;
    $otp = $pin;
    $clickid = $clickid;

    $fp = fopen("pin_verify" . date("Y-m-d"), "a");
    fwrite($fp, "\n[$time_india_one]  Inside the launcher function MSISDN  $msisdn , otp $otp and clickid $clickid\n");
    if ($otp != '') {
        $default_timeZone1 = date("Y-m-d H:i:s");
        $trackingid = rand(1, 234567891011121557);
        $external_id = rand(1, 23456789101112155);
        //for authentication parameter
        date_default_timezone_set('UTC');
        $default_timeZone = date();
        $unix_time = date('Ymdhis', strtotime($default_timeZone));
        $key1 = "0dDivO0AB8ypZFMK";
        $timestamp = $unix_time;
        $plaintext = '3575#' . $timestamp;
        $authen = aes128Encrypt($key1, $plaintext);

        $headers2 = array();
        $headers2[0] = "apikey:4f3c8be4591246e3b63ffa606a748bd9";
        $headers2[1] = "external-tx-id:" . $external_id;
        $headers2[2] = "authentication:" . $authen;
        $headers2[3] = "Content-type: application/json";

        $arrayData['userIdentifier'] = $msisdn;
        $arrayData['userIdentifierType'] = "MSISDN";
        $arrayData['catalogId'] = "28";
        $arrayData['mcc'] = "420";
        $arrayData['mnc'] = "01";
        //$arrayData['entryChannel'] = "WEB";
        //$arrayData['clientIP'] ="";
        $arrayData['transactionAuthCode'] = $otp;
        $content = json_encode($arrayData);

        $url = "https://unified-ma.timwetech.com/mea/subscription/optin/confirm/3652";
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
        //$otp = $_GET['otp'];
        fwrite($fp, "\n[$date]  hitting the api $url with payload $content and receieved $json_response\n");


        $json_response1 = json_decode($json_response, TRUE);
        $message = $json_response1['code'];
        $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
        $subscriptionError = $json_response1['responseData']['subscriptionError'];


        //$sql_subscription = "INSERT INTO `saudi_arabia_timwe_stc_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$date_india','$date_saudi_arabia')";
        //mysql_query($sql_subscription);
        fwrite($fp, "\n[$time_india_one]  received MSISDN  $msisdn1 OTP $otp \n");
        //TABLE CLOSED BY RAJENDRA
        if ($subscriptionResult == "OPTIN_WAIT_FOR_ACTIVE_AND_CHARGING") {


            $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=322&op=BEYOND_HEALTH_KSA_STC_AFFLINK');


            if ($results == 0) //MEANS PASS
            {
                $panda = "PASSED";
                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$panda','$message','$serviceDate','$time_india_one')";
                mysql_query($sql);

                fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");

                echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
                exit();
            }
            if ($results == 1) //MEANS BLOCK

            {
                $panda = "BLOCK";
                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$panda','$message','$serviceDate','$time_india_one')";
                mysql_query($sql);
                fwrite($fp, "\n $time_india_one Received $msisdn Query $sql \n");

                echo json_encode(array('response' => 'Fail', 'errorMessage' => 'server error'));

                exit();
            }
        } else {
            $send = "Otp Not Send";
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$otp','$subscriptionResult','$send','$message','$serviceDate','$time_india_one')";
            mysql_query($sql);

            fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");
            echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
        }
    }
}
