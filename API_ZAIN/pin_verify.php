<?php
          $service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=23');
if($service=="ZAIN_API_PRO")
{
if(isset($_REQUEST['msisdn'])&&isset($_REQUEST['pin']))
{
    $msisdn=$_REQUEST['msisdn'];
    $pin=$_REQUEST['pin'];
    $cid="ELIZABETH PROMOTIONS";
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
    $fp=fopen("pin_verify".date("Y-m-d"),"a");
    fwrite($fp,"\n[$date]  Inside the launcher function MSISDN  $msisdn , otp $otp and clickid $clickid\n");
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

        $arrayData['userIdentifier'] =$msisdn;
        $arrayData['userIdentifierType'] = "MSISDN";
        $arrayData['catalogId'] ="12";
        $arrayData['mcc'] ="420";
        $arrayData['mnc'] ="07";
        //$arrayData['subSource'] = "WEB";
        //$arrayData['clientIP'] ="";
        $arrayData['transactionAuthCode'] =$otp;
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
		//$sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
        //mysql_query($sql_subscription);
        fwrite($fp,"\n[$date]  received MSISDN  $msisdn1 query $sql_subscription is triggered\n");
		//TABLE CLOSED BY RAJENDRA
		if ($subscriptionResult == "OPTIN_WAIT_FOR_ACTIVE_AND_CHARGING") {
				
                //Callback for offer 18 opened
                $tracker=fopen('tracker','r+');
                $ans=file_get_contents('tracker');
                if($ans==0)
                {
                    fwrite($tracker,0);
                    fclose($tracker);
                    $status=0;
                    $message='PASSED';
                    fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
                    $output = array('status'=>$status,
                                'errorMessage'=>'Pin Verification Successfull',
                                );
                    $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
                    mysql_query($sql_subscription);
                    echo json_encode($output, JSON_PRETTY_PRINT);
                    exit();
                }
                elseif($ans==1)
                {
                    fwrite($tracker,2);
                    fclose($tracker);
                    $status=3;
                    $message='BLOCKED';
                    fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
                    $output = array('status'=>$status,
                                'errorMessage'=>'Pin Verification failure',
                                );
                    $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
                    mysql_query($sql_subscription);
                    echo json_encode($output, JSON_PRETTY_PRINT);
                    exit();

                }
                else
                {
                    fwrite($tracker,0);
                    fclose($tracker);
                    $status=3;
                    $message='BLOCKED';
                    fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
                    $output = array('status'=>$status,
                                'errorMessage'=>'Pin Verification failure',
                                );
                    $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
                    mysql_query($sql_subscription);
                    echo json_encode($output, JSON_PRETTY_PRINT);
                    exit();

                }
                //send_message($msisdn,$clickid);               			
				
				//exit();
        } else if ($subscriptionResult == "OPTIN_CONF_WRONG_PIN") {
				$status=1;
				$message='Wrong Pin try again (OTP خاطئ حاول مرة أخرى)';
                fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
                mysql_query($sql_subscription);                			
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
        } else {
				$status=2;
				$message=$subscriptionResult;
				$output = array('status'=>$status,
								'errorMessage'=>$message,
								);	
                fwrite($fp,"\n[$date]  for this $msisdn1 the following status $status and message $message is received\n");
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_verify`(`cid`, `msisdn`, `subscriptionResult`, `message`,`date_india`,`date_saudi_arabia`) VALUES ('$clickid', '$msisdn', '$subscriptionResult', '$message','$time_india','$date_saudi_arabia')";
                mysql_query($sql_subscription);                                     		
				echo json_encode($output, JSON_PRETTY_PRINT);
				exit();
        }

    }
}

/*function send_message($msisdn,$clickid)
{
        $date=date('Y-m-d h:i:s');
        $fp=fopen("pin_verify".date("Y-m-d"),"a");
        fwrite($fp,"\n[$date]  Inside the send_message block and the msisdn is $msisdn and clickid is $clickid\n");
        $default_timeZone1 = date("Y-m-d H:i:s");
        $trackingid = rand(1, 234567891011121557);
        $external_id = rand(1, 23456789101112155);
        //for authentication parameter
        date_default_timezone_set('UTC');
        $default_timeZone = date();
        $unix_time = date('Ymdhis', strtotime($default_timeZone)); 
        $key1 = "GgXMw4i4hte01VYo";
        $timestamp = $unix_time;
        $plaintext = '3575#' . $timestamp;
        $authen = aes128Encrypt($key1, $plaintext);

        $headers2 = array();
        $headers2[0] = "apikey:12320e2141c34c7b94f117b8d03febde";
        $headers2[1] = "external-tx-id:" . $external_id;
        $headers2[2] = "authentication:" . $authen;
        $headers2[3] = "Content-type: application/json";

        //$arrayData['userIdentifier'] = $msisdn;
        //$arrayData['userIdentifierType'] = "MSISDN";
        $Message="You have been subscribed in Beyond Health for 3 days free trial after that 1 SR /Daily.To use service, go to URL http:beyondhealth.mobi/saudi_arabia .To cancel your subscription, for Mobily Saudi Arabia subscribers please send U61 to 606068 .For any inquires please contact us on support@arshiyainfosolutions.com.";
        $arrayData['productId'] ="16699";
        $arrayData['pricepointId']="51759";
        $arrayData['mcc'] = "420";
        $arrayData['mnc'] = "07";
        $arrayData['text']=$Message;
        $arrayData['msisdn']=$msisdn;
        $arrayData['largeAccount']="709222";
        $arrayData['priority']="NORMAL";
        $arrayData['timezone']="Asia/Riyadh";
        $arrayData['context']="STATELESS";
        $arrayData['mtType'] = "WAP";
        //$arrayData['clientIP'] = "";
        //$arrayData['transactionAuthCode'] =$otp;
        //$content = json_encode($arrayData);
        $content=json_encode($arrayData,JSON_UNESCAPED_SLASHES+JSON_UNESCAPED_UNICODE);//Concept learned In Tpay Integration
        $url = "https://unified-ma.timwetech.com/mea/SMS/send/mt/3652";
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

}*/
}


        
if($service=="ZAIN_INFOCELL")
{

include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india=date("Y-m-d H:i:s");
    $clickid="OLIMOB";
    $msisdn=$_GET['msisdn'];
    $ip=$_SERVER['REMOTE_ADDR'];
    $device_id=rand(0,99999999);
    $pin=$_GET['pin'];

   $sql="SELECT `request_id` from `fantastic_ksa_zain_infocell_pin_request` WHERE `msisdn`='".$msisdn."' order by id desc limit 1";
            $result=mysql_query($sql);
            $row = mysql_fetch_array($result);
            $request_id=$row['request_id'];
   

    $fp = fopen('fantastic_infocell_ksa_zain_pin_verify_log'.date("Y-m-d"), 'a');
    fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
    $api="https://api.funtastic.mobi/InAppAPI/api/subscription";
    $raw_data=array(
        "request"=>"pin_verify",
        "alias"=>"9779",
        "usr"=>"digi_fish",
        "pass"=>"6sDtJpwC4gQ",
        "package_id"=>"966002002",
        "msisdn"=>$msisdn,
        "ip"=>$ip,
        "device_id"=>"$device_id",
        "pin"=>$pin,
        "request_id"=>"$request_id",
        "source_id"=>"2"
      );





      $payload=json_encode($raw_data);
      $ch = curl_init('https://api.funtastic.mobi/InAppAPI/api/subscription');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
        );

    // Submit the POST request
      $result = curl_exec($ch);

      //JUGAAD 
      $result = str_replace(':010,',':"010",',$result);
      
      //var_dump($result);
      $check=json_decode($result,true);
     // print_r($check);
     //$chk= json_decode('{"result":{"status":false,"status_code":"010","response":"Invalid PIN"}}', true);

    // var_dump( $chk);
     // die("end");
      $code=$check['result']['status'];        
      $reason=$check['result']['response']; 
   
    
    fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
       
       if ($code=="1") {
      

         $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=81&op=KSA_zain_infocell_pro');
          if($results==0)//MEANS PASS
                        {


                            $panda="PASSED";
                                   $sql="INSERT INTO  `fantastic_ksa_zain_infocell_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);
                                 
                                   echo json_encode(array('status'=> 0,'errorMessage' =>'Pin Verified Successfully'));
                                        exit();

                            
                            
                        }

                        if($results==1)//MEANS BLOCK

                        {

                            $panda="BLOCK";
                             $sql="INSERT INTO  `fantastic_ksa_zain_infocell_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);
                                 echo json_encode(array('status'=> 1,'errorMessage' =>'Time out error'));
                                        exit();

                        }


       }
else{
             $sql="INSERT INTO  `fantastic_ksa_zain_infocell_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$reason."','".$time_india."')";
        mysql_query($sql);
       
          echo json_encode(array('status'=> 2,'errorMessage' =>$reason));
                                        exit();

}




}
?>