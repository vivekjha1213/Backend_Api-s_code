
<?php
$service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=52');
if($service=="MOBILECAFE OMANTEL")
{
$fp=fopen("MC_pin_Request_".date("Y-m-d"),"a");
$msisdn=$_GET['msisdn'];

fwrite($fp,"\n[$date] Inside  msisdn $msisdn\n");
$plan_id = '6265';
  function aes128Encrypt($key, $data)
  {
      if (16 !== strlen($key)) $key = hash('MD5', $key, true);
      $padding = 16 - (strlen($data) % 16);
      $data .= str_repeat(chr($padding), $padding);
      return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
  }



  function launcher($msisdn)
  {


        $fp=fopen("Dream_Mobi_omatel_Request_log".date("Y-m-d"),"a");

        fwrite($fp,"\n[$date] Inside  launcher with  $msisdn\n");


        include("connect.php");
        $msisdn1=$msisdn;
        $clickId="DREAM MOBI".rand();
        $advName="ARSHIYA"; 
        $pubName="DREAM_MOBI";
        $serviceName="MOBILECAFE_OMANTEL";
        $country="OMAN";
        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Muscat');
        $serviceDate=date("Y-m-d H:i:s");
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
        $authen = aes128Encrypt($key1, $plaintext);
        fwrite($fp,"\n[$date] Inside  launcher with  $msisdn and auth is $authen \n");

              $headers2 = array();
              $headers2[0] = "apikey:cd493c51e0f7499f8aaf143acddbf4bd";
              $headers2[1] = "external-tx-id:" . $external_id;
              $headers2[2] = "authentication:" . $authen;
              $headers2[3] = "Content-type: application/json";

              //$arrayData['userIdentifier'] = $msisdn;
              $arrayData['userIdentifier']=$msisdn1;
              $arrayData['userIdentifierType'] = "MSISDN";
              $arrayData['productId'] = "6265";
              $arrayData['mcc'] = "422";
              $arrayData['mnc'] = "02";
              $arrayData['entryChannel'] = "WAP";
              $arrayData['largeAccount'] = "91415";
              $arrayData['subKeyword'] = "OTP";
              $arrayData['trackingId'] = $trackingid;
              $arrayData['clientIP'] = "127.0.0.1";
              $arrayData['campaignUrl'] = "";
              $content = json_encode($arrayData);

              $url = "https://omantel-ma.timwe.com/om/ma/api/external/v1/subscription/optin/2026";
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
              fwrite($fp,"\n[$date] Inside  launcher with  $msisdn hitting url $url with payload $content and received $json_response\n");
        //echo $json_response;
        //exit();


              $json_response1 = json_decode($json_response, TRUE);
              $message = $json_response1['code'];
              $subscriptionResult = $json_response1['responseData']['subscriptionResult'];
        
        if ($subscriptionResult == "OPTIN_PREACTIVE_WAIT_CONF")
        {
                  $status=0;
          $message='Pin genrated Successfully';
         

                  $sql_subscription = "INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$message','$serviceDate','$time_india_one')";
                  mysql_query($sql_subscription);
                  fwrite($fp,"\n[$date]Inserting data in   $sql_subscription\n");

                //   print_r($sql_subscription);
                //   die();
         echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
                 
        }
        else  {
                  $status=2;
          //$message='Invalid msisdn';
                  $sql_subscription = "INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$message','$serviceDate','$time_india_one')";

                  fwrite($fp,"\n[$date]Inserting data in  fail Section $sql_subscription\n");
                  mysql_query($sql_subscription);
                         echo json_encode(array('response'=> 'Fail','errorMessage' =>$subscriptionResult));

          exit();
              }


    }
    launcher($msisdn);
}

if($service=="SHEMAROO OMANTEL")
{
                  include("connect.php");
                  date_default_timezone_set('Asia/Kolkata');
                  $time_india=date('Y-m-d');
                  $msisdn=$_GET['msisdn'];
                  $PromoID=28800;
                  $PartnerID=8;
                  $TransactionID=rand();
                  $clickId="DREAM MOBI".rand();
                  $advName="SHEMAROO"; 
                  $pubName="DREAM_MOBI";
                  $serviceName="SHEMAROO_OMANTEL";
                  $country="OMAN";
                  date_default_timezone_set('Asia/Muscat');
                  $serviceDate=date("Y-m-d H:i:s");
                  date_default_timezone_set('Asia/Kolkata');
                  $time_india_one=date("Y-m-d H:i:s");
                  $fp=fopen("Dream_Mobi_she_omantel_request_log".$time_india,"a");
                  fwrite($fp,"\n[$time_india] Received $msisdn with ProductId $PromoID  IDService $PartnerID TpId $TransactionID\n");
                  //Added By Rajendra
                  // API URL
                  $url="http://m.shemaroo.com/intl/TimweService/GenerateOTP";

                  // Create a new cURL resource
                  $ch=curl_init($url);
                  //echo "hello";
                  //exit();
                  $data=array("MSISDN"=> $msisdn,
                    "PromoID"=> $PromoID,
                    "PartnerID"=> $PartnerID,
                    "TransactionID"=> $TransactionID );
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
                  $code=$check['Code'];
                  $reason=$check['Message'];
                  $TransactionID=$check['TransactionID'];
                  $time_india_one=date('Y-m-d H:i:s');
                  fwrite($fp,"\n[$time_india] Received $msisdn hitting the url $url with payload $payload Received $subscriptionResult\n");
                  fclose($fp);
                  if($code==0)
                  {       
                        
                        
                          $sql_subscription = "INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
                          mysql_query($sql_subscription);
                          $message='Pin genrated Successfully';
                          $output = array('response'=>'SUCCESS',
                                          'errorMessage'=>$message,
                                             );
                          echo json_encode($output, JSON_PRETTY_PRINT);
                          exit();
                  }
                  else
                  {
                          $sql_subscription ="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
                          mysql_query($sql_subscription);
                          $output = array('response'=>'Fail',
                                          'errorMessage'=>$reason,
                                          );
                          echo json_encode($output, JSON_PRETTY_PRINT);
                          exit();
                  }
}

if($service=="OMANTAL_SME_PRO1")
{

include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india=date('Y-m-d');
$msisdn=$_GET['msisdn'];
$PromoID=29360;
$PartnerID=8;
$transactionId=rand();
$clickId="DREAM MOBI".rand();
$advName="SHEMAROO"; 
$pubName="DREAM_MOBI";
$serviceName="SHEMAROO_OMANTEL_SME";
$country="OMAN";
date_default_timezone_set('Asia/Muscat');
$serviceDate=date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$fp=fopen("Dream_Mobi_sme_pin_request_log".$time_india,"a");
fwrite($fp,"\n[$time_india] Received $msisdn with PromoID $PromoID  PartnerID $PartnerID transactionId $transactionId\n");
//Added By Rajendra
// API URL
$url="http://m.shemaroo.com/intl/TimweService/GenerateOTP";

// Create a new cURL resource
$ch=curl_init($url);
//echo "hello";
//exit();
$data=array("msisdn"=> $msisdn,
  "PromoID"=> $PromoID,
  "PartnerID"=> $PartnerID,
  "transactionId"=> $transactionId );
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
//echo $subscriptionResult;
$check=json_decode($result,true);
//echo "<pre>";
//print_r($check);
//exit();
$status=$check['Message'];
$code=$check['Code'];
$TransactionID=$check['TransactionID'];

$time_india_one=date('Y-m-d H:i:s');
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url with payload $payload and Received $subscriptionResult\n");
if($code==0)
{  
        
        

        $sql_subscription = "INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$status','$TransactionID','$serviceDate','$time_india_one')";
        mysql_query($sql_subscription);
        $message='Successfull Pin generation';
        /*$output = array('status'=>0,
                        'errorMessage'=> $message,
                        'transactionId'=> $code,
                      ); */
        $output=array('response'=>'SUCCESS','errorMessage'=>'Pin generated successfully');
        echo json_encode($output, JSON_PRETTY_PRINT);
        exit();
}
else
{
        $sql_subscription ="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$status','$TransactionID','$serviceDate','$time_india_one')";
        mysql_query($sql_subscription);
        /*$output = array('status'=>1,
                        'errorMessage'=>$reason,
                      );*/
        $output=array('response'=>'Fail','errorMessage'=>'Invalid  msisdn');
        echo json_encode($output, JSON_PRETTY_PRINT);
        exit();
}

}




?>


