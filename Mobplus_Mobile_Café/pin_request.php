<?php
// if ($service == "MOBILECAFE_UAE") {
    require_once("connect.php");
    date_default_timezone_set('Asia/Kolkata');
    $default_timeZone = date("Y-m-d H:i:s");
    $get_phone = $_GET['msisdn'];
    $pack = 'daily';
    //echo $get_phone;
    function aes128Encrypt_old($key, $data)
    {
        if (16 !== strlen($key)) $key = hash('sha256', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
    }


    function aes128Encrypt($key, $data)
    {
        if (16 !== strlen($key)) $key = hash('sha256', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
    }


    function sendOtp($phone, $pack)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $default_timeZone = date("Y-m-d H:i:s");


        $username1 = 'Arshiya';
        $password1 = 'o@jiGIm@0IIfA8N';
        $key1 = 'DHDUFYlinsGDDSSs';
        $pack = 'daily';
        $packageid1 = '1322';
        $username = aes128Encrypt($key1, $username1);
        $password = aes128Encrypt($key1, $password1);
        $mobile12 = aes128Encrypt($key1, $phone);
        //$mobile = urlencode($mobile12);
        $packageid = aes128Encrypt($key1, $packageid1);
        //$packageid=urlencode($packageid12);
        //$username=urlencode('Arshiya');
        $requestArray = array();
        $requestArray['user'] = $username;
        $requestArray['password'] = $password;
        $requestArray['msisdn'] = $mobile12;
        $requestArray['packageId'] = $packageid;
        $TransactionID = "ABC-" . rand() . "-XYZ";
        $requestArray['txnid'] = $TransactionID;
        $requestArray['channel'] = 'wap';
        $requestArray['sourceIP'] = $ip;
        $requestArray['adPartnerName'] = '';
        $requestArray['pubId'] = '';


        $data_string = json_encode($requestArray);

        //	$data_string = json_encode($requestArray);
        //var_dump( $data_string);
        //$api_url = 'http://pt5.etisalat.ae/Moneta/pushPIN.htm?usr=' . $username . '&pwd=' . $password . '&msisdn=' . $mobile . '&packageid=' . $packageid;
        $api_url = 'https://pt5.etisalat.ae/Moneta/pushPOSTPin.htm';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        //var_dump($result);
        curl_close($ch);
        $data = explode('|', $result);
        $response = $data[0];
        $token = $data[1];


        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Dubai');
        $serviceDate = date("Y-m-d H:i:s");
        $serviceId = "ARSH0047";
        $advName = "INHOUSE";
        $pubName = "MOBPLUS";
        $serviceName = "MOBILECAFE";
        $country = "UAE";

        $req_dump = "=======" . date('Y-m-d H:i:s') . "========";
        $req_dump .= print_r($requestArray, TRUE);
        $req_dump .= print_r($data, TRUE);
        $fp = fopen('UaePinRequest.log_' . date('Ymd'), 'a');
        fwrite($fp, $req_dump . " $packageid1 $pack");
        fwrite($fp, "\n $phone THE api URL is $api_url with payload $data_string gives response $result\n");
        fclose($fp);

        /*sample:
						pin_sent
						TxwYEn0ba0fB8adUVsfv7bc%2BFg7%2FGVqBURXVbSddzQA%3D*/

        

        if ($response == "pin_sent") {
            // $sqlinsert1 = "INSERT INTO `new_UaePinRequest`(`id`, `ip`,`Response`,`Token`, `msisdn`,`subPackage`,`source`, `Date`,cid) VALUES (NULL,'" . $ip . "','" . $response . "','" . $token . "','" . $phone . "','W', 'mobile_arts','" . $default_timeZone . "','".$GLOBALS['TransactionID']."')";
            // mysql_query($sqlinsert1);
            //echo '{"response": "SUCCESS", "errorMessage":"Pin Generated Successfully", "correlatorId": "'.$token.'"}';

            $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$get_phone','$result','$token', '$phone','$serviceDate','$default_timeZone')";
            mysql_query($sql_subscription);


            echo json_encode(array('success' => 1, 'correlatorId' => 'OK'));


        } elseif ($response == "Invalid_MSISDN") {
            //echo "{\"response\": \"Fail\", \"errorMessage\":\"Invalid msisdn\"}";

            $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$get_phone','$result','$token', '$phone','$serviceDate','$default_timeZone')";
        mysql_query($sql_subscription);

            echo json_encode(array('response' => "Invalid_MSISDN"));
        } else {
            //echo "{\"response\": \"Fail\", \"errorMessage\":\"Other\"}";



            $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$get_phone','$result','$token', '$phone','$serviceDate','$default_timeZone')";
            mysql_query($sql_subscription);

            echo json_encode(array('response' => $response));
        }
    }
    sendOtp($get_phone, $pack);
//  }
?>
