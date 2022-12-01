<?php
    $msisdn = $_GET[msisdn];
    // $operator=$_GET[operator];
    $clickid = "";

    function aes128Encrypt($key, $data)
    {
        if (16 !== strlen($key)) $key = hash('sha256', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
    }

    start_first($msisdn, $clickid);
    function start_first($phone_no, $clickid)
    {

        include 'connect.php';
        date_default_timezone_set('Asia/Kolkata');
        $date_india = date("Y-m-d h:i:s");

        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Dubai');
        $serviceDate = date("Y-m-d H:i:s");
        $serviceId = "ARSH0046";
        $advName = "INHOUSE";
        $pubName = "MOBPLUS";
        $serviceName = "FUNZSTATION";
        $country = "UAE";


        $fp = fopen('funzstation_pin_request_' . date("Y-m-d"), 'a');
        $request = $request = rand(111, 99999);

        //NEW PARAMETERS ADDED BY RAJENDRA

        /*if (false) {
						                return '{"Status": "3", "Description":"User already subscribed"}';
						            } else {
						                //$key1 = "BDFHJLNPpnljhfdb";
						                $key1 = "DHDUFYlinsGDDSSs";
						                $username1 = 'Arshiya';
						                //$password1 = 'ARshiy9865';
						                $password1 = 'o@jiGIm@0IIfA8N';
						            if($pack=='daily'){
						                    $packageid1 = '1322'; // daily offer, weekly offer is 1323
						            }else{
						                    $packageid1 = '1323'; // weekly offer, daily offer is 1322
						                }*/
        $ip = $_SERVER['REMOTE_ADDR'];
        $phone = $phone_no;
        $msisdn = $phone_no;
        $cid = $clickid;
        //fwrite($fp,"\n [$date_india] $request  MSISDN $phone and cid $cid \n");

        $key1 = "DHDUFYlinsGDDSSs";
        $username1 = 'Arshiya';
        $password1 = 'o@jiGIm@0IIfA8N';
        $packageid1 = '1750';
        //fwrite($fp,"\n[$date_india] $request  MSISDN $phone and cid $cid and $username1 $password1 $packageid1 $username $password $mobile12 $mobile $packageid  \n");
        $username = aes128Encrypt($key1, $username1);
        $password = aes128Encrypt($key1, $password1);
        $mobile12 = aes128Encrypt($key1, $phone);
        $mobile = urlencode($mobile12);
        //for daily
        $packageid = aes128Encrypt($key1, $packageid1);
        fwrite($fp, "\n[$date_india] $request  MSISDN $phone and cid $cid and $username1 $password1 $packageid1 $username $password $mobile12 $mobile $packageid  \n");
        //$packageid=urlencode($packageid12);
        //$username=urlencode('Arshiya');
        $requestArray = array();
        $requestArray['user'] = $username;
        $requestArray['password'] = $password;
        $requestArray['msisdn'] = $mobile12;
        $requestArray['packageId'] = $packageid;
        //$TransactionID=rand(111111133,999999999999);
        $requestArray['txnid'] = $cid;
        $requestArray['channel'] = 'web';
        $requestArray['sourceIP'] = $ip;
        $requestArray['adPartnerName'] = '';
        $requestArray['pubId'] = '';

        $data_string = json_encode($requestArray);
        //fwrite($fp,"\n[$date_india] $request  MSISDN $phone and cid $cid generated encoded string $data_string\n");

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
        curl_close($ch);
        $data = explode('|', $result);
        $response = $data[0];
        $token = $data[1];

        //ADD LOGS BY RAJENDRA
        //$sql_subscription = "INSERT INTO `funzstation_uae_alt_pin_request`(`cid`,`msisdn`,`response`, `status`,`date_india`) VALUES ('$cid', '$msisdn','$response','$status','$date_india')";
        //mysql_query($sql_subscription);


        fwrite($fp, "\n[$date_india] $request  MSISDN $phone hitting Url $api_url with payload $data_string and received result $response and $token\n");

        //fclose($fp);
        $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$response','$response','$serviceDate','$time_india_one')";
        mysql_query($sql_subscription);

        if ($response == "pin_sent") {

            $status = '0';
            // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_request`(`cid`,`msisdn`,`response`, `status`,`date_india`) VALUES ('$token', '$msisdn','$response','$status','$date_india')";
            // mysql_query($sql_subscription);
            fwrite($fp, "\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
            fclose($fp);
            echo json_encode(array('success' => 1, 'correlatorId' => 'OK'));
        } else {
            $status = '1';
            // $sql_subscription = "INSERT INTO `new_funzstation_uae_alt_pin_request`(`cid`,`msisdn`,`response`, `status`,`date_india`) VALUES ('$token', '$msisdn','$response','$status','$date_india')";
            // mysql_query($sql_subscription);
            fwrite($fp, "\n[$date_india] $request getting $response and $status and insert $sql_subscription \n");
            fclose($fp);
            echo json_encode(array('response' => $response));
        }
    }




