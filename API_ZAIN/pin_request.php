<?php

          $service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=23');
if($service=="ZAIN_API_PRO")
{
if(isset($_REQUEST['msisdn']))
{
    $msisdn=$_REQUEST['msisdn'];
    $cid="ELIZABETH PROMOTIONS";
    launcher($msisdn,$cid);
}
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
			$arrayData['userIdentifier']=$msisdn1;
            $arrayData['userIdentifierType'] = "MSISDN";
            $arrayData['catalogId'] = "12";
            $arrayData['mcc'] = "420";
            $arrayData['mnc'] = "07";
            $arrayData['subSource'] = "WEB";
            //$arrayData['largeAccount'] = "709222";
            //$arrayData['subKeyword'] = "32";
            $arrayData['trackingId'] = $trackingid;
            $arrayData['clientIP'] = "127.0.0.1";
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
			//echo $json_response;
			//exit();


            $json_response1 = json_decode($json_response, TRUE);
            $message = $json_response1['code'];
            $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
            //echo $subscriptionResult;
            //exit();
			//TABLE ADDED BY RAJENDRA
			//$sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_request`(`msisdn`, `status`) VALUES ( '$msisdn1','$message')";
			//mysql_query($sql_subscription);
			//TABLE CLOSED BY RAJENDRA
            date_default_timezone_set('Asia/Kolkata');
            $date=date('Y-m-d h:i:s');
            $fp=fopen("Pin_Request_".date("Y-m-d"),"a");
            fwrite($fp,"\n[$date] Inside  msisdn $msisdn1 hitting url $url with $content and result $json_response\n");
			if($subscriptionResult == 'OPTIN_ALREADY_ACTIVE') {
                
				$status=1;
				$message='Already Subscribed msisdn';
                date_default_timezone_set('Asia/Kolkata');
                $date_india=date('Y-m-d h:i:s');
                date_default_timezone_set('Asia/Riyadh');
                $date_saudi_arabia=date('Y-m-d h:i:s');
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
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
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
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
                $sql_subscription = "INSERT INTO `saudi_arabia_timwe_zain_pin_request`(`cid`,`msisdn`,`subscriptionResult`,`message`,`date_india`, `date_saudi_arabia`) VALUES ( '$cid','$msisdn1','$subscriptionResult','$message','$date_india','$date_saudi_arabia')";
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
    $fp = fopen('fantastic_infocell_ksa_zain_pin_request_log'.date("Y-m-d"), 'a');
    fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
    $api="https://api.funtastic.mobi/InAppAPI/api/subscription";
    $raw_data=array(
        "request"=>"pin_gen",
        "alias"=>"9779",
        "usr"=>"digi_fish",
        "pass"=>"6sDtJpwC4gQ",
        "package_id"=>"966002002",
        "msisdn"=>$msisdn,
        "ip"=>$ip,
        "device_id"=>"$device_id",
        "source_id"=>"2"
      );
      $payload=json_encode($raw_data);
      $ch = curl_init('https://api.funtastic.mobi/InAppAPI/api/subscription');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
        $response=json_decode($result,true);
        
        $code=$response['result']['status'];        
      $reason=$response['result']['response']; 
      //$tid=$response['result']['transaction_id'];  
      $request_id=$response['result']['data'];  
      
       fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
      
       
       if ($code=="1") {
         $sql="INSERT INTO  `fantastic_ksa_zain_infocell_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`,`request_id`) VALUES('".$clickid."','".$msisdn."','".$result."','".$reason."','".$time_india."','".$device_id."','".$request_id."')";
         
        mysql_query($sql);
         echo json_encode(array('status'=> 0,'errorMessage' =>'Pin genrated Successfully'));

       }
else{
      $sql="INSERT INTO  `fantastic_ksa_zain_infocell_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`,`request_id`) VALUES('".$clickid."','".$msisdn."','".$result."','".$reason."','".$time_india."','".$device_id."','".$request_id."')";
        mysql_query($sql);
          echo json_encode(array('status'=> 1,'errorMessage' =>$reason));

}

    }
?>
