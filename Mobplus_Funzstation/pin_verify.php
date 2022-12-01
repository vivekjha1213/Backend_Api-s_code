<?php
    include 'connect.php';
    $msisdn = $_GET[msisdn];
    $otp = $_GET[pin];
    $clickid = "";
    $sql = "SELECT `cid` from `in_app_pin_request` WHERE `msisdn`='" . $msisdn . "' order by id desc limit 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $extra = $row['cid'];
    //  $extra=$_GET[correlatorId];
    //start_first($msisdn,$otp,$clickid,$extra);
 
    function aes128Encrypt($key, $data)
    {
        if (16 !== strlen($key)) $key = hash('sha256', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
    }
    start_first($msisdn, $otp, $clickid, $extra);
    function start_first($msisdn, $otp, $clickid, $extra)
    {

        $ip = $_SERVER['REMOTE_ADDR'];
        $phone = $msisdn;
        $pin1 = $otp;
        $cid = $clickid;
        $key1 = "DHDUFYlinsGDDSSs";
        $username1 = 'Arshiya';
        $password1 = 'o@jiGIm@0IIfA8N';
        //for Daily
        $packageid1 = '1750';
        $correlatorId = $extra;
        $username = aes128Encrypt($key1, $username1);
        $password = aes128Encrypt($key1, $password1);
        $mobile12 = aes128Encrypt($key1, $phone);
        $mobile = urlencode($mobile12);
        $packageid = aes128Encrypt($key1, $packageid1);
        $pin2 = aes128Encrypt($key1, $pin1);

        $requestArray = array();
        $requestArray['user'] = $username;
        $requestArray['password'] = $password;
        $requestArray['msisdn'] = $mobile12;
        $requestArray['packageId'] = $packageid;
        $requestArray['txnId'] = $clickid;
        $requestArray['channel'] = 'web';
        $requestArray['token'] = $correlatorId;
        $requestArray['pin'] = $pin2;
        $requestArray['sourceIP'] = $ip;
        $requestArray['adPartnerName'] = '';
        $requestArray['pubId'] = '';

        $data_string = json_encode($requestArray);


        $api_url = 'https://pt5.etisalat.ae/Moneta/confirmPOSTPin.htm';
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
        curl_close($ch);
        $data = explode('|', $result);
        $response = $data[0];
        $token = $data[1];


        date_default_timezone_set('Asia/Kolkata');
        $date_india = date("Y-m-d H:i:s");

        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Dubai');
        $serviceDate = date("Y-m-d H:i:s");
        $serviceId = "ARSH0046";
        $advName = "INHOUSE";
        $pubName = "MOBPLUS";
        $serviceName = "FUNZSTATION";
        $country = "UAE";
        //$sql_subscription = "INSERT INTO `funzstation_uae_alt_pin_verify`(`cid`,`msisdn`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$response','$status','$date_india')";
        //mysql_query($sql_subscription);
        $request = $request = rand(111, 99999);
        //$fp = fopen('funzstation_pin_verify_'.date("Y-m-d"), 'a');
        fwrite($fp, "\n[$date_india] $request  MSISDN $phone hitting Url $api_url with payload $data_string and received result $result\n");

        // fclose($fp);
        if ($response == 'success') {
            //send_message($msisdn);
            $result = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=282&op=MOBPLUS_FUNZSTATION_UAE');
            if ($result == 0) //PASS CODE
            {
                $status = 'PASSED';
                // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_verify`(`cid`,`msisdn`,`otp`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$pin1','$result','$status','$date_india')";
                // mysql_query($sql_subscription);

                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$status','$result','$serviceDate','$time_india_one')";
                mysql_query($sql);


                //fwrite($fp,"\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
                //fclose($fp);
				echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
                exit();
            }
            if ($result == 1) // BLOCK CODE
            {
                $status = 'BLOCK';
                // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_verify`(`cid`,`msisdn`,`otp`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$pin1','$result','$status','$date_india')";
                // mysql_query($sql_subscription);

                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$status','$reason','$serviceDate','$time_india_one')";
                mysql_query($sql);

                //fwrite($fp,"\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
                //fclose($fp);
				echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
                exit();
            }
        } elseif ($response == 'Already_Active') {
            $status = 'FAILED';
            // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_verify`(`cid`,`msisdn`,`otp`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$pin1','$result','$status','$date_india')";
            // mysql_query($sql_subscription);

            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$status','$result','$serviceDate','$time_india_one')";
            mysql_query($sql);

            //fwrite($fp,"\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
            //fclose($fp);

            echo json_encode(array('response' =>'Fail','errorMessage' => $response));
        } else {
            $status = 'FAILED';
            // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_verify`(`cid`,`msisdn`,`otp`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$pin1','$result','$status','$date_india')";
            // mysql_query($sql_subscription);
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$status','$result','$serviceDate','$time_india_one')";
            mysql_query($sql);

            // print_r($sql);
            // die();

            //fwrite($fp,"\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
            //fclose($fp);
			echo json_encode(array('response' =>'Fail','errorMessage' => $response));
        }
    }



