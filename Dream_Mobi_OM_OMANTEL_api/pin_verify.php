<?php
//Added By Rajedra
//$headers = print_r($_SERVER,true);
$service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=52');
if($service=="MOBILECAFE OMANTEL")
{
      $request =  print_r($_REQUEST,true);
      $ftime = date("Y-m-d H:i:s");
      $logline = "[$ftime]: request values are $request  \n";
      $fo = fopen("MC_pin_Verify_".date("Ymd"),"a");
     

      fwrite($fo,$logline);
      fclose($fo);
     
      $msisdn=$_REQUEST['msisdn'];
      $pin=$_REQUEST['pin'];
      //Closing Parameters of MoviPlus
      function aes128Encrypt($key, $data)
      {
          if (16 !== strlen($key)) $key = hash('MD5', $key, true);
          $padding = 16 - (strlen($data) % 16);
          $data .= str_repeat(chr($padding), $padding);
          return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
      }

      function launcher($msisdn,$pin) {
          include("connect.php");
      	//$gclid = $_GET['gclid'];
      	//$cid = $_GET['cid'];
      	$msisdn=$msisdn;
      	$otp=$pin;
          $clickId="Dream Mobi".rand();
          $advName="ARSHIYA"; 
        $pubName="DREAM_MOBI";
        $serviceName="MOBILECAFE_OMANTEL";
        $country="OMAN";
        date_default_timezone_set('Asia/Muscat');
        $serviceDate=date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
      	if ($otp != '') {
              $default_timeZone1 = date("Y-m-d H:i:s");
              $trackingid = rand(1, 234567891011121557);
              $external_id = rand(1, 23456789101112155);
      		//for authentication parameter
      		date_default_timezone_set('UTC');
      		$default_timeZone = date();
      		$unix_time = date('Ymdhis', strtotime($default_timeZone));
      		$key1 = "vzAqmmH9QCTK4DtD";
      		$timestamp = $unix_time;
      		$plaintext = '2052#' . $timestamp;
      		//for authentication closed
      		//extra dummy parameter for table
      		$token='PROMOTIONAL API OMAN';
      		$clubId='OMAN';
      		$clickId='PROMOTIONAL API OMAN';
      		//closed dummy parameter
              $authen = aes128Encrypt($key1, $plaintext);

              $headers2 = array();
              $headers2[0] = "apikey:cd493c51e0f7499f8aaf143acddbf4bd";
              $headers2[1] = "external-tx-id:" . $external_id;
              $headers2[2] = "authentication:" . $authen;
              $headers2[3] = "Content-type: application/json";

              $arrayData['userIdentifier'] = $msisdn;
              $arrayData['userIdentifierType'] = "MSISDN";
              $arrayData['productId'] ="6265";
              $arrayData['mcc'] = "422";
              $arrayData['mnc'] = "02";
              $arrayData['entryChannel'] = "WEB";
              $arrayData['clientIP'] = "";
              $arrayData['transactionAuthCode'] =$otp;
              $content = json_encode($arrayData);

              $url = "https://omantel-ma.timwe.com/om/ma/api/external/v1/subscription/optin/confirm/2026";
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
              $otp = $_GET['pin'];

              date_default_timezone_set('Asia/Kolkata');
              $time_india = date('Y-m-d H:i:s');
              date_default_timezone_set('Asia/Muscat');
              $time_oman = date('Y-m-d H:i:s');





              $json_response1 = json_decode($json_response, TRUE);
              $message = $json_response1['code'];
              $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
              $subscriptionError = $json_response1['responseData']['subscriptionError'];
              $date=date('Y-m-d h:i:s');
              $fp=fopen("MC_PIN_VERIFY_".date("Y-m-d"),"a");
      		fwrite($fp,"\n[$date] Inside Passed $msisdn , pin $otp and json response is $json_response\n");
      		//TABLE ADDED BY RAJENDRA
      			//$sql_subscription = "INSERT INTO `MoviPlus_omantel_pin_verify`(`token`, `clubId`, `clickId`, `msisdn`,`pin`,`subscriptionResult`,`message`) VALUES ('$token', '$clubId', '$clickId', '$msisdn','$otp','$subscriptionResult','$message')";
                  //mysql_query($sql_subscription);
                  //$sql_subscription = "INSERT INTO `promotiona_omatel_timwe_verify`(`msisdn`,`pin`,`subscriptionResult`, `status`) VALUES ('$msisdn', '$otp','$subscriptionResult','$status')";
                  //mysql_query($sql_subscription);
      		//TABLE CLOSED BY RAJENDRA
      		if ($subscriptionResult == "OPTIN_ACTIVE_WAIT_CHARGING") {

                              include("connect.php");
                              $date=date('Y-m-d h:i:s');
                              //$fp=fopen("PIN_VERIFY_".date("Y-m-d"),"a");
                              fwrite($fp,"\n[$date] Inside  OPTIN_ACTIVE_WAIT_CHARGING $msisdn and pin $otp\n");
                              $url="http://beyondhealth.info/Services/Filter/chk.php?id=235&op=Dream_Mobi_mc_om_omantel";
                              $start=curl_init();
                              curl_setopt($start, CURLOPT_URL,$url);
                              curl_setopt($start, CURLOPT_RETURNTRANSFER, 1);
                              $result=curl_exec($start);
                              curl_close($start);
                              //$output;
                              if($result==0)
                              {
                                
                                  $status="Fail";
                                  $panda="PASSED";
                                  $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$panda','$subscriptionResult','$serviceDate','$time_india_one')";
                                  mysql_query($sql_subscription);
                                  $output=array('response'=>'SUCCESS','errorMessage'=>'ok');
        echo json_encode($output, JSON_PRETTY_PRINT);
                              }
                              if($result==1)
                              {
                                  $status="Fail";
                                  $panda="BLOCKED4";
                                  $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$panda','$subscriptionResult','$serviceDate','$time_india_one')";
                                  mysql_query($sql_subscription);
                                  $output=array('response'=>'Fail','errorMessage'=>'Multiple hits on   msisdn');
        echo json_encode($output, JSON_PRETTY_PRINT);
                                  exit();
                              }

              } else if ($subscriptionResult == "OPTIN_CONF_WRONG_PIN") {
      				$status="Fail";
      				$message='Pin verification Failed';
                      $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$subscriptionResult','$subscriptionResult','$serviceDate','$time_india_one')";
                      mysql_query($sql_subscription);
                      $output=array('response'=>'Fail','errorMessage'=>'wrong  pin');
        echo json_encode($output, JSON_PRETTY_PRINT);
      				exit();
              } else {
      				$status=2;
      				$message='';
                      $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$subscriptionResult','$subscriptionResult','$serviceDate','$time_india_one')";
                      mysql_query($sql_subscription);
                       $output=array('response'=>'Fail','errorMessage'=>'Pin verification Failed');
        echo json_encode($output, JSON_PRETTY_PRINT);
              }

          }
      }
      launcher($msisdn,$pin);
}

if($service=="SHEMAROO OMANTEL")
{
          include("connect.php");
          date_default_timezone_set('Asia/Kolkata');
          $time_india=date('Y-m-d');
         $clickId="Dream Mobi".rand();
         $advName="SHEMAROO"; 
         $pubName="DREAM_MOBI";
         $serviceName="SHEMAROO_OMANTEL";
         $country="OMAN";
                  date_default_timezone_set('Asia/Muscat');
                  $serviceDate=date("Y-m-d H:i:s");
                  date_default_timezone_set('Asia/Kolkata');
                  $time_india_one=date("Y-m-d H:i:s");
          $msisdn=$_GET['msisdn'];
          //TO GET TRANSACTION
          $sql="SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='".$msisdn."' and pubName='DREAM_MOBI' order by id desc limit 1";
          $result=mysql_query($sql);
          $row = mysql_fetch_array($result);
          $TransactionID=$row['finalStatus'];
          
          //TO GOT TRANSACTION ID
          $PromoID=28800;
          $OTP=$_GET['pin'];
          $PartnerID=8;
          $fp=fopen("Shpin_Verify_".$time_india,"a");
          fwrite($fp,"\n[$time_india] Received $msisdn with ProductId $PromoID OTP $OTP IDService $PartnerID TpId $TpId\n");
          //Added By Rajendra
          // API URL
          $url="http://m.shemaroo.com/intl/TimweService/ValidateOTP";

          // Create a new cURL resource
          $ch=curl_init($url);
          //echo "hello";
          //exit();
          $data=array("MSISDN"=> $msisdn,
            "PromoID"=> $PromoID,
            "OTP"=> $OTP,
            "PartnerID"=> $PartnerID,
            "TransactionID"=> $TransactionID,
             );
          $payload=json_encode($data);
          //echo $payload;
          //exit();

          // Attach encoded JSON string to the POST fields
          curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);

          // Set the content type to application/json
          curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));

          // Return response instead of outputting
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

          // Execute the POST request
          $result=curl_exec($ch);

          // Close cURL resource
          curl_close($ch);
          //echo $result;
          $subscriptionResult=json_encode($result);
          $check=json_decode($result,true);
          //echo "<pre>";
          //print_r($check);
          //die();
          $code=$check['Code'];
          $reason=$check['Message'];
          fwrite($fp,"\n[$time_india] Received $msisdn with $url and with payload $payload result $subscriptionResult'\n");
          //fclose($fp);
          //echo $code;
          //echo $message;
          if($code==0)
                          {
                                  $time_india_one=date('Y-m-d H:i:s');
                                  $result=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=236&op=Dream_Mobi_SHEMAROO_OMANTEL');
                                  if($result==0)//MEANS PASS
                                  {
                                      $fp = fopen("Dream_Mobi_she_omantel_verify_log".$time_india,"a");
                                     
                                      $panda="PASSED";
                                      
                                      $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$TransactionID','$serviceDate','$time_india_one')";
                                     $output=array('response'=>'SUCCESS','errorMessage'=>'ok');
                              echo json_encode($output, JSON_PRETTY_PRINT);
                                  }
                                  if($result==1)//MEANS BLOCK
                                  {
                                      $status="Fail";
                                      $panda="BLOCK";
                                      $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$TransactionID','$serviceDate','$time_india_one')";
                                    $output=array('response'=>'Fail','errorMessage'=>'Multiple hits on this  msisdn');
        echo json_encode($output, JSON_PRETTY_PRINT);

                                  }


                          }
                          else
                          {
                              $status='Fail';
                              $panda=$reason;
                              $time_india_one=date('Y-m-d H:i:s');
                              //$result=trim($OUTPUT);
                              $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
                              mysql_query($sql_subscription);
                              $output=array('response'=>'Fail','errorMessage'=>'wrong pin');
        echo json_encode($output, JSON_PRETTY_PRINT);
                          }


}

if($service=="OMANTAL_SME_PRO1")
{

include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india=date('Y-m-d');

$msisdn=$_GET['msisdn'];
$clickId="Dream Mobi".rand();
$advName="SHEMAROO"; 
$pubName="DREAM_MOBI";
$serviceName="SHEMAROO_OMANTEL_SME";
$country="OMAN";
date_default_timezone_set('Asia/Muscat');
$serviceDate=date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$sql="SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='".$msisdn."' and pubName='DREAM_MOBI' and serviceName='SHEMAROO_OMANTEL_SME' order by id desc limit 1";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$transactionId=$row['finalStatus'];
$pin=$_GET['pin'];
$PromoID=29360;
$PartnerID=8;// for daily 1100 for weekly 1099
$fp=fopen("Dream_Mobi_sme_pin_Verify_".$time_india,"a");
fwrite($fp,"\n[$time_india] Received $msisdn with transactionId $transactionId pin $pin PromoID $PromoID PartnerID $PartnerID \n");
//Added By Rajendra
// API URL
$url="http://m.shemaroo.com/intl/TimweService/ValidateOTP";

// Create a new cURL resource
$ch=curl_init($url);
//echo "hello";
//exit();
$data=array("MSISDN"=> $msisdn,
  "PromoID"=> $PromoID,
  "OTP"=> $pin,
  "PartnerID"=> $PartnerID,
   "TransactionID"=> $transactionId
    );
$payload=json_encode($data);
//echo $payload;
//exit();

// Attach encoded JSON string to the POST fields
curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);

// Set the content type to application/json
curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));

// Return response instead of outputting
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

// Execute the POST request
$result=curl_exec($ch);

// Close cURL resource
curl_close($ch);
//echo $result;
// die();
$subscriptionResult=json_encode($result);
$check=json_decode($result,true);
//print_r($check);
$code=$check['Code'];
$status=$check['Message'];

//echo $code;
//echo $message;
fwrite($fp,"\n[$time_india] Received $msisdn hitted $url with paylaod $payload and the output we got $subscriptionResult and reason $status\n");
if($code==0)
                {
                        $fp=fopen("Dream_Mobi_omantel_sme_pin_verify_log".$time_india,"a");

                        $time_india_one=date('Y-m-d H:i:s');

                        $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=238&op=Dream_Mobi_OMANTAL_SME_PRO1');

                        if($results==0)//MEANS PASS
                        {

                           
                            $panda="PASSED";

                            
                            $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$subscriptionResult','$panda','$transactionId','$serviceDate','$time_india_one')";
                            mysql_query($sql_subscription);
                            $output=array('response'=>'SUCCESS','errorMessage'=>'ok');
        echo json_encode($output, JSON_PRETTY_PRINT);
                        }

                        if($results==1)//MEANS BLOCK

                        {
                            $status="Fail";
                            $panda="BLOCK";
                            $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$subscriptionResult','$panda','$transactionId','$serviceDate','$time_india_one')";
                            mysql_query($sql_subscription);
                           $output=array('response'=>'Fail','errorMessage'=>'Multiple hits on this  msisdn');
        echo json_encode($output, JSON_PRETTY_PRINT);

                        }


                }
                else
                {
                    $panda=$status;
                    //$result=trim($OUTPUT);
                    $time_india_one=date('Y-m-d H:i:s');
                    $sql_subscription = "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$subscriptionResult','$status','$transactionId','$serviceDate','$time_india_one')";
                    mysql_query($sql_subscription);
                  $output=array('response'=>'Fail','errorMessage'=>'wrong pin');
        echo json_encode($output, JSON_PRETTY_PRINT);
                }

}

?>



