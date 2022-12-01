<?php
if(isset($_REQUEST['msisdn'])&&isset($_REQUEST['pin']))
{
    $msisdn=$_REQUEST['msisdn'];
    $pin=$_REQUEST['pin'];
    $cid=$_REQUEST['cid'];
    launcher($msisdn,$pin,$cid);
}
function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

function launcher($msisdn,$pin,$clickid) {
    include("connect.php");
	$msisdn=$msisdn;
	$otp=$pin;
	$clickid=$clickid;
    include("connect.php");
    $date=date('Y-m-d h:i:s');
    //$fp=fopen("pin_verify".date("Y-m-d"),"a");
    fwrite($fp,"\n[$date]  Inside the launcher function MSISDN  $msisdn , otp $otp and clickid $clickid\n");
	if ($otp != '') {
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
        $authen = aes128Encrypt($key1, $plaintext);

        $headers2 = array();
        $headers2[0] = "apikey:14f4214b50d44d3790c1af62e108bc57";
        $headers2[1] = "external-tx-id:" . $external_id;
        $headers2[2] = "authentication:" . $authen;
        $headers2[3] = "Content-type: application/json";

        $arrayData['userIdentifier'] =$msisdn;
        $arrayData['userIdentifierType'] = "MSISDN";
        $arrayData['productId'] ="16527";
        $arrayData['mcc'] ="420";
        $arrayData['mnc'] ="03";
        $arrayData['entryChannel'] = "WEB";
        $arrayData['clientIP'] ="";
        $arrayData['transactionAuthCode'] =$otp;
        $content = json_encode($arrayData);

        $url = "https://mobily-ma.timwe.com/sa/ma/api/external/v1/subscription/optin/confirm/3681";
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
		fwrite($fp,"\n[$date]  hitting the api $url with payload $content and receieved $json_response\n");
		

        $json_response1 = json_decode($json_response, TRUE);
        $message = $json_response1['code'];
        $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
        $subscriptionError = $json_response1['responseData']['subscriptionError'];
		
		//TABLE ADDED BY RAJENDRA
        date_default_timezone_set('Asia/Kolkata');
        $time_india = date('Y-m-d H:i:s');
        date_default_timezone_set('Asia/Riyadh');
        $date_saudi_arabia = date('Y-m-d H:i:s');
		$sql_subscription = "INSERT INTO `saudi_arabia_timwe_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`,`pin`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia','$pin')";
        mysql_query($sql_subscription);
        fwrite($fp,"\n[$date]  received MSISDN  $msisdn1 query $sql_subscription is triggered\n");
        fclose($fp);
		//TABLE CLOSED BY RAJENDRA
		if ($subscriptionResult == "OPTIN_ACTIVE_WAIT_CHARGING") {
				$status=0;
				$message='Successfull Pin verification';
                fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);

                $tracker=fopen('tracker','r+');
                $ans=file_get_contents('tracker');
                $date=date('Y-m-d h:i:s');
                date_default_timezone_set('Asia/Kolkata');
                $time_india = date('Y-m-d H:i:s');
                $fl=fopen("Checker_".date("Y-m-d"),"a");
                if($ans==0)
                {
                    fwrite($fl,"\n[$time_india]  received MSISDN  $msisdn1 and cid $clickid is blocked 0\n");
                    fwrite($tracker,1);
                    fclose($tracker);
                    fclose($fl);
                }
                if($ans==1)
                {   fwrite($fl,"\n[$time_india]  received MSISDN  $msisdn1 and cid $clickid is blocked 1\n");
                    fwrite($tracker,2);
                    fclose($tracker);
                    fclose($fl);
                }
                if($ans==2)
                {
                    fwrite($fl,"\n[$time_india]  received MSISDN  $msisdn1 and cid $clickid is blocked 2\n");
                    fwrite($tracker,0);
                    fclose($tracker);
                    fclose($fl);
                //Callback for offer 18 opened
                $digi_tid_url2 = "http://df3.o18.click/p?mid=1395&auth_token=43643&tid=".$clickid;
                $digi_tid_ch2 = curl_init(); 
                curl_setopt($digi_tid_ch2, CURLOPT_URL, $digi_tid_url2); 
                curl_setopt($digi_tid_ch2, CURLOPT_RETURNTRANSFER, 1); 
                $digi_tid_output2 = curl_exec($digi_tid_ch2); 
                curl_close($digi_tid_ch2);
                //Callback for offer 18 closed
                }
                

                //send_message($msisdn,$clickid);               			
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
        } else if ($subscriptionResult == "OPTIN_CONF_WRONG_PIN") {
				$status=1;
				$message='Wrong Pin try again (OTP خاطئ حاول مرة أخرى)';
                fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);			
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
        } else {
				$status=2;
				$message=$subscriptionResult;
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);	
                fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");                		
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
        }

    }
}

function send_message($msisdn,$clickid)
{
        $date=date('Y-m-d h:i:s');
        //$fp=fopen("pin_verify".date("Y-m-d"),"a");
        fwrite($fp,"\n[$date]  Inside the send_message block and the msisdn is $msisdn and clickid is $clickid\n");
        $default_timeZone1 = date("Y-m-d H:i:s");
        $trackingid = rand(1, 234567891011121557);
        $external_id = rand(1, 23456789101112155);
        //for authentication parameter
        date_default_timezone_set('UTC');
        $default_timeZone = date();
        $unix_time = date('Ymdhis', strtotime($default_timeZone)); 
        $key1 = "CZE9jPcoYrIT2I6Y";
        $timestamp = $unix_time;
        $plaintext = '3535#' . $timestamp;
        $authen = aes128Encrypt($key1, $plaintext);

        $headers2 = array();
        $headers2[0] = "apikey:199fd7719cf6402bbd3366fd1018a8e3";
        $headers2[1] = "external-tx-id:" . $external_id;
        $headers2[2] = "authentication:" . $authen;
        $headers2[3] = "Content-type: application/json";

        //$arrayData['userIdentifier'] = $msisdn;
        //$arrayData['userIdentifierType'] = "MSISDN";
        $Message="You have been subscribed in Beyond Health for 3 days free trial after that 1 SR /Daily.To use service, go to URL http:beyondhealth.mobi/saudi_arabia .To cancel your subscription, for Mobily Saudi Arabia subscribers please send U61 to 606068 .For any inquires please contact us on support@arshiyainfosolutions.com.";
        $arrayData['productId'] ="16527";
        $arrayData['pricepointId']="43204";
        $arrayData['mcc'] = "420";
        $arrayData['mnc'] = "03";
        $arrayData['text']=$Message;
        $arrayData['msisdn']=$msisdn;
        $arrayData['largeAccount']="606334";
        $arrayData['priority']="NORMAL";
        $arrayData['timezone']="Asia/Riyadh";
        $arrayData['context']="STATELESS";
        $arrayData['mtType'] = "WAP";
        //$arrayData['clientIP'] = "";
        //$arrayData['transactionAuthCode'] =$otp;
        //$content = json_encode($arrayData);
        $content=json_encode($arrayData,JSON_UNESCAPED_SLASHES+JSON_UNESCAPED_UNICODE);//Concept learned In Tpay Integration
        $url = "https://mobily-ma.timwe.com/sa/ma/api/external/v2/sms/mt/3681";
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
        fwrite($fp,"\n[$date]  Inside the send_message block msisdn $msisdn hitted the $url with $content and received the output $json_response\n");

}

?>