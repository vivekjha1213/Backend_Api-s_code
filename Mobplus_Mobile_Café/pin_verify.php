<?php

// if ($service == "MOBILECAFE_UAE") {
    include("connect.php");
    $ip = $_SERVER['REMOTE_ADDR'];
    date_default_timezone_set('Asia/Kolkata');
    $dats = date("Y-m-d H:i:s");
    $phone = $_GET['msisdn'];
    $pin1 = $_GET['pin'];
    $sql = "SELECT `finalStatus` from `in_app_pin_request` WHERE `msisdn`='" . $phone . "' order by id desc limit 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $correlatorId = $row['finalStatus'];
    $pack = 'daily';
    function validatePhoneNumber($num)
    {
        if (!ctype_digit($num)) {
            return false;
        }
        $len = strlen($num);
        if ($len == 9) {
            return "971" . $num;
        } else {
            return FALSE;
        }
    }


    function aes128Encrypt($key, $data)
    {
        if (16 !== strlen($key)) $key = hash('MD5', $key, true);
        #  if (16 !== strlen($key)) $key = hash('sha256', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
    }

    function sendOtp($phone, $pin1, $correlatorId, $pack)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $default_timeZone = date("Y-m-d H:i:s");

        /*$sql_user = "SELECT * FROM `UaePinRequest` WHERE msisdn= '$phone' AND token_used = '0' ORDER BY id DESC ";
			$res_user = mysql_query($sql_user);
			$num_user = mysql_num_rows($res_user);
			$row = mysql_fetch_assoc($res_user);
	echo$row['Token'];
			var_dump($row);
			if ($row['Token'] != '') {*/
        $correlatorId = urlencode($correlatorId);

        $key1 = "BDFHJLNPpnljhfdb";
        $key1 = "DHDUFYlinsGDDSSs";
        $username1 = 'Arshiya';
        $password1 = 'ARshiy9865';
        $username1 = 'Arshiya'; //Added By Rajendra
        $password1 = 'o@jiGIm@0IIfA8N'; //Added By Rajendra
        $packageid1 = '1323'; // weekly offer, daily offer is 1322
        if ($pack == 'daily') {
            $packageid1 = '1322'; // daily offer, weekly offer is 1323^M
        } else {
            $packageid1 = '1323'; // weekly offer, daily offer is 1322^M
        }

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
        $TransactionID = "ABC-" . rand() . "-XYZ";
        $requestArray['txnId'] = $TransactionID;
        $requestArray['channel'] = 'web';
        $requestArray['token'] = $correlatorId;
        $requestArray['pin'] = $pin2;
        $requestArray['sourceIP'] = $ip;
        $requestArray['adPartnerName'] = '';
        $requestArray['pubId'] = '';

        $data_string = json_encode($requestArray);

        http: //pt5.etisalat.ae/Moneta/confirmPinSubscription.htm?usr=<USERNAME>&pwd=<PASSWORD>& msis dn=<		MSISDN>&packageid=<PACKAGEID>&pin=<PIN>&token=<TOKEN>
        $api_url = 'http://pt5.etisalat.ae/Moneta/confirmPinSubscription.htm?usr=' . $username . '&pwd=' . $password . '&msisdn=' . $mobile . '&packageid=' . $packageid . '&pin=' . $pin2 . '&token=' . $correlatorId;

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
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Dubai');
        $serviceDate = date("Y-m-d H:i:s");
        $serviceId = "ARSH0044";
        $advName = "INHOUSE";
        $pubName = "DREAM_MOBI";
        $serviceName = "MOBILECAFE_UAE";
        $country = "UAE";
        // echo($response);
        /*sample:
					pin_sent
					TxwYEn0ba0fB8adUVsfv7bc%2BFg7%2FGVqBURXVbSddzQA%3D*/

        if ($response == 'success') {
            include("connect.php");
            $date = date('Y-m-d h:i:s');
            $fp = fopen("PIN_VERIFY_" . date("Y-m-d"), "a");
            $ans = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=281&op=MOBPLUS_MOBILE_CAFE_UAE');
            if ($ans == 0) {
                date_default_timezone_set('Asia/Kolkata');
                $dats = date("Y-m-d H:i:s");
                $status = "SUCCESS";
                $panda = "PASSED";

                // $sql_subscription = "INSERT INTO `new_promotional_uae_pin_verify`(`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$phone', '$pin1','$response','$panda','$dats')";
                // mysql_query($sql_subscription);

                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$phone','$pin1','$result','$panda','$status','$serviceDate','$time_india_one')";
                mysql_query($sql);

                fwrite($fp, "\n[$date] Inside Passed ,status $response $phone , pin $pin1 and triggered  query $sql_subscription\n");
                fclose($fp);
				echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
                exit();
            }
            if ($ans == 1) {
                date_default_timezone_set('Asia/Kolkata');
                $dats = date("Y-m-d H:i:s");
                $status = "Fail";
                $panda = "BLOCK";

                // $sql_subscription = "INSERT INTO `new_promotional_uae_pin_verify`(`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$phone', '$pin1','$response','$panda','$dats')";
                // mysql_query($sql_subscription);

                $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$phone','$pin1','$result','$panda','$status','$serviceDate','$time_india_one')";
                mysql_query($sql);

                fwrite($fp, "\n[$date] Inside Passed ,status $response $phone , pin $pin1 and triggered  query $sql_subscription\n");
                fclose($fp);
                echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
                exit();
            }

            /*$sql_block_update = "UPDATE `uae_mobilearts_verify` SET `is_blocked` = '1' WHERE `msisdn` = '$phone'";
						mysql_query($sql_block_update);*/
        } else {
            date_default_timezone_set('Asia/Kolkata');
            $dats = date("Y-m-d H:i:s");

            // $sql_subscription = "INSERT INTO `new_promotional_uae_pin_verify`(`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$phone', '$pin1','$response','$response','$dats')";
            // mysql_query($sql_subscription);

            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$phone','$pin1','$result','$result','$reason','$serviceDate','$time_india_one')";
            mysql_query($sql);


            // print_r($sql);
            // die();
			echo json_encode(array('response' => 'Fail', 'errorMessage' => "INVALID PIN"));


            exit();
        }
        /*} else {
				return '{"Status": "18", "Description":"Token mismatch"}';
			}*/
    }
    sendOtp($phone, $pin1, $correlatorId, $pack);
// }
