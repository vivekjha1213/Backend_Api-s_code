<?php
$msisdn = $_GET['msisdn'];
$cid = " N.A";
launcher($msisdn, $cid);

function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}
function launcher($msisdn, $cid)
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

    $msisdn1 = $msisdn;
    $cid = $cid;
    if ($msisdn1 != '') {
        //$msisdn1 = strlen($msisdn1) == '9' ? "966".$msisdn1 : $msisdn1;
        if ($msisdn1 != '') {

            $trackingid = rand(1, 234567891011121557);
            $external_id = rand(1, 23456789101112155);
            //for authentication parameter
            date_default_timezone_set('UTC');
            $default_timeZone = date();
            $unix_time = date('Ymdhis', strtotime($default_timeZone));
            $key1 = "0dDivO0AB8ypZFMK";
            $timestamp = $unix_time;
            $plaintext = '3575#' . $timestamp;
            //echo $plaintext;
            //exit();
            //for authentication closed
            //extra dummy parameter for table
            //$token='PROMOTIONAL API OMAN';
            //$clubId='OMAN';
            //$clickId='PROMOTIONAL API OMAN';
            //closed dummy parameter
            $authen = aes128Encrypt($key1, $plaintext);
            //echo $authen;
            //exit();

            $headers2 = array();
            $headers2[0] = "apikey:4f3c8be4591246e3b63ffa606a748bd9";
            $headers2[1] = "external-tx-id:" . $external_id;
            $headers2[2] = "authentication:" . $authen;
            $headers2[3] = "Content-type: application/json";

            //$arrayData['userIdentifier'] = $msisdn;
            $arrayData['userIdentifier'] = $msisdn1;
            $arrayData['userIdentifierType'] = "MSISDN";
            $arrayData['catalogId'] = "28";
            $arrayData['mcc'] = "420";
            $arrayData['mnc'] = "01";
            $arrayData['subSource'] = "WEB";
            $arrayData['trackingId'] = $trackingid;
            // $arrayData['subKeyword'] = "32";
            // $arrayData['trackingId'] = $trackingid;
            $arrayData['clientIP'] = $_SERVER['REMOTE_ADDR'];
            $arrayData['campaignUrl'] = "";
            $content = json_encode($arrayData);
            $url = "https://unified-ma.timwetech.com/mea/subscription/optin/3652";
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

            date_default_timezone_set('Asia/Kolkata');
            $date = date('Y-m-d h:i:s');
            $fp = fopen("Pin_Request_" . date("Y-m-d"), "a");
            fwrite($fp, "\n[$date] Inside  msisdn $msisdn1 hitting url $url with $content and result $json_response\n");

            if ($subscriptionResult == 'OPTIN_ALREADY_ACTIVE') 
            {


                $message = 'Already Subscribed msisdn';


                $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn1','$subscriptionResult','$message','$subscriptionResult','$serviceDate','$time_india_one')";
                mysql_query($sql_subscription);
                echo json_encode(array('response' => 'Fail', 'errorMessage' => "$message"));
                exit();
            }
            if ($message == "SUCCESS")
             {

                $message1 = 'OK';

                $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn1','$subscriptionResult','$message','$subscriptionResult','$serviceDate','$time_india_one')";
                mysql_query($sql_subscription);
                echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => "$message1"));
                exit();
            } else 
            {

                $message2 = 'Invalid msisdn';

                $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn1','$subscriptionResult','$message2','$subscriptionResult','$serviceDate','$time_india_one')";
                mysql_query($sql_subscription);
                echo json_encode(array('response' => 'Fail', 'errorMessage' => "$message2"));
                exit();
            }
        }
    }
}
