<?php

    $msisdn=$_REQUEST['msisdn'];
    $cid=$_REQUEST['cid'];
    launcher($msisdn,$cid);

function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

function launcher($msisdn,$cid) {
	include("connect.php");
	$msisdn1=$msisdn;
    $cid=$cid;
    if ($msisdn1 != '') {
        //$msisdn1 = strlen($msisdn1) == '9' ? "966".$msisdn1 : $msisdn1;
        if ($msisdn1 != '') {
            $default_timeZone1 = date("Y-m-d H:i:s");
            $trackingid = rand(1, 234567891011121557);
            $external_id = rand(1, 23456789101112155);
			//for authentication parameter
			date_default_timezone_set('UTC');
			$default_timeZone = date();
			$unix_time = date('Ymdhis', strtotime($default_timeZone)); 
			$key1 = "ryWP4X4QsiwheXTK";
			$timestamp = $unix_time;
			$plaintext = '3535#' . $timestamp;
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
            $headers2[0] = "apikey:14f4214b50d44d3790c1af62e108bc57";
            $headers2[1] = "external-tx-id:" . $external_id;
            $headers2[2] = "authentication:" . $authen;
            $headers2[3] = "Content-type: application/json";

            //$arrayData['userIdentifier'] = $msisdn;
			$arrayData['userIdentifier']=$msisdn1;
            $arrayData['userIdentifierType'] = "MSISDN";
            $arrayData['productId'] = "16527";
            $arrayData['mcc'] = "420";
            $arrayData['mnc'] = "03";
            $arrayData['entryChannel'] = "WEB";
            $arrayData['largeAccount'] = "606334";
            $arrayData['subKeyword'] = "61";
            $arrayData['trackingId'] = $trackingid;
            $arrayData['clientIP'] = "127.0.0.1";
            $arrayData['campaignUrl'] = "";
            $content = json_encode($arrayData);

            $url = "https://mobily-ma.timwe.com/sa/ma/api/external/v1/subscription/optin/3681";
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
			//echo $json_response;
			//exit();


            $json_response1 = json_decode($json_response, TRUE);
            $message = $json_response1['code'];
            $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
            //echo $subscriptionResult;
            //exit();
			//TABLE ADDED BY RAJENDRA
			//$sql_subscription = "INSERT INTO `saudi_arabia_timwe_pin_request`(`msisdn`, `status`) VALUES ( '$msisdn1','$message')";
			//mysql_query($sql_subscription);
			//TABLE CLOSED BY RAJENDRA
            date_default_timezone_set('Asia/Kolkata');
            $date=date('Y-m-d h:i:s');
            //$fp=fopen("Pin_Request_".date("Y-m-d"),"a");
            fwrite($fp,"\n[$date] Inside  msisdn $msisdn1 hitting url $url with $content and result $json_response\n");
			if($subscriptionResult == 'OPTIN_ALREADY_ACTIVE') {
                
				$status=1;
				$message='Already Subscribed msisdn';
                date_default_timezone_set('Asia/Kolkata');
                $date_india=date('Y-m-d h:i:s');
                date_default_timezone_set('Asia/Riyadh');
                $date_saudi_arabia=date('Y-m-d h:i:s');
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
                mysql_query($sql_subscription);
                fwrite($fp,"\n[$date] Inside  msisdn $msisdn1 and  result $subscriptionResult and triggered query $sql_subscription\n");
                fclose($fp);
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);			
				echo json_encode($output, JSON_PRETTY_PRINT);
                exit();
            }
            if ($message == "SUCCESS") {
                $status=0;
				$message='Pin genrated Successfully';
                date_default_timezone_set('Asia/Kolkata');
                $date_india=date('Y-m-d h:i:s');
                date_default_timezone_set('Asia/Riyadh');
                $date_saudi_arabia=date('Y-m-d h:i:s');
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
                mysql_query($sql_subscription);
                fwrite($fp,"\n[$date] Inside  msisdn $msisdn1 and  result $subscriptionResult and triggered query $sql_subscription\n");
                fclose($fp);
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);			
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
            } else  {
                $status=2;
				$message='Invalid mobile number OR try again';
                date_default_timezone_set('Asia/Kolkata');
                $date_india=date('Y-m-d h:i:s');
                date_default_timezone_set('Asia/Riyadh');
                $date_saudi_arabia=date('Y-m-d h:i:s');
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
                mysql_query($sql_subscription);
                fwrite($fp,"\n[$date] Inside  msisdn $msisdn1 and  result $subscriptionResult and triggered query $sql_subscription\n");
                fclose($fp);
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);			
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
            }
			
        }
    }
}
?>
