<?php

    $service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=53');

    if($service=="GameCafe_OOREEDO")
{

  include 'connect.php';
 
    date_default_timezone_set('Asia/Kolkata');
     $date=date("Y-m-d");
    $request=$request=rand(111,99999);
    


        date_default_timezone_set("UTC");
        $serviceId=2656;
        $time=time()*1000;
        $data = $serviceId.'#'.$time;
        // $enc_key = "lURAbFuvPJHNMmuI";  
          $enc_key = "uEMIRkPECkeJNdx1"; 

        $token = base64_encode( openssl_encrypt( $data, 'AES-128-ECB', $enc_key, OPENSSL_RAW_DATA));
        $sequenceNo=rand();
        $msisdn=$_GET['msisdn'];


        
        $clickId="Dream Mobi".rand();
        $advName="ARSHIYA"; 
        $pubName="DREAM_MOBI";
        $serviceName="GameCafe_OOREEDO";
        $country="OMAN";


      
        $fp = fopen('gamecafe_oredo_mcomviva_pin_request'.$date, 'a');
    fwrite($fp,"\n[$date] $request received with MSISDN $msisdn and cid $click_id \n");
   // echo "0";
        $xml_data ='<?xml version="1.0" encoding="UTF-8"?><ocsRequest>'.
         '<serviceNode>ARSHIYA</serviceNode>'.
         '<serviceId>10406</serviceId>'.
         '<planId>46667</planId>'.
         '<sequenceNo>'.$sequenceNo.'</sequenceNo>'.
         '<callingParty>'.$msisdn.'</callingParty>'.
         '<bearerId>WEB</bearerId>'.
         '<shortcode>91239</shortcode>'.
         '<keyword>SUB GP</keyword>'.
         '</ocsRequest>';
         // echo $xml_data;
        $URL="http://vascld-afl.mcomviva.com:8112/AFL/OOSubPin";
        $ch = curl_init($URL);
        // $apikey='393357da5a7a4bfb9ce9312020d40a2f'; 
        $apikey="a4ee63e3a7434c8d8df3a616e4dad6b9"; 

        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml',"apiKey: $apikey","authentication: $token"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xmlResponse=curl_exec($ch);
        curl_close($ch);
        $xml=simplexml_load_string($xmlResponse);
        // print_r($xml);
        $payload = json_encode($xml); 
        // $final_result=array();
        $final_result = json_decode($payload, true);
         // print_r($payload);
         // exit();
        $final_response=$final_result[errorCode];
        
        $request=$request=rand(111,99999);
        $apikey="a4ee63e3a7434c8d8df3a616e4dad6b9"; 
        
        // $fp = fopen('mcomviva_pin_request'.date("Ymd"), 'a');
        fwrite($fp,"\n[$date] $request received with MSISDN $msisdn  and url $URL and paylaod $xml_data output of this is $xmlResponse and other format is $xml  using apikey $apikey and enc_key $enc_key  and token   $token   \n");
        // ec/ho "string";
        // print_r($final_result);
        //exit();
        if($final_response=='OPTIN_PREACTIVE_WAIT_CONF')
        {   
            $statuscode=1;
            $statusdetails="OTP SENT";

            

            $mysql_insert="INSERT INTO  `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$message','$serviceDate','$time_india_one')";

            mysql_query($mysql_insert);
            echo json_encode(array('response' =>'SUCCESS','errorMessage' =>'OK'));
        }
        elseif ($final_response=='OPTIN_ALREADY_ACTIVE') 
        {
            $statuscode=10;
            $statusdetails="ALREADY ACTIVE USER";
            $mysql_insert="INSERT INTO  `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$message','$serviceDate','$time_india_one')";
            mysql_query($mysql_insert);
            echo json_encode(array('response' =>'Fail' ,'errorMessage' =>'ALREADY ACTIVE USER'));
        }
        else
        {   
            $statuscode=0;
            $statusdetails="WRONG PHONE NUMBER OR TRY AGAIN";
            $mysql_insert="INSERT INTO  `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$json_response','$subscriptionResult','$message','$serviceDate','$time_india_one')";

            mysql_query($mysql_insert);
            echo json_encode(array('response' =>'Fail','errorMessage' =>'INVALID_MSISDN'));
        }


}


if ($service=="INTELLISENSE_OM_Oredo_two"){

   
     include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india=date("Y-m-d H:i:s");

    $msisdn=$_GET['msisdn'];
     
    $clickId="Dream Mobi".rand();
    $advName="INTELLISENSE"; 
    $pubName="DREAM_MOBI";
    $serviceName="INTELLISENSE_OOREEDOO _TWO";
    $country="OMAN";
    // $pin=$_GET['pin'];
     $fp = fopen('two_INTELLISENSE_OM_Oredo_omanoredo_pin_request_log'.date("Y-m-d"), 'a');
    fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
    $api="http://45.114.143.51/eapi/digi/pingenerate";
    $raw_data=array(
        "MSISDN"=>"$msisdn",
        "Key"=>"xbsnvmpc"
      );




      $payload=json_encode($raw_data);
      $ch = curl_init('http://45.114.143.51/eapi/digi/pingenerate');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
        );
 $result = curl_exec($ch);

      
        $response=json_decode($result,true);
        
        $code=$response['response'];        
      $reason=$response['errorMessage']; 
      
       
        $a="";
   
          fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
       if ($code=="SUCCESS") {

        

         $sql="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
        mysql_query($sql);
         // print_r($sql);
        echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));
       }
else{
      $sql="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
      // print_r($sql);
        mysql_query($sql);
        echo json_encode(array('response'=> 'Fail','errorMessage' =>$reason));
}


        
    }



if($service=="MOVI_TIMES_INTELLISENSE_OM_OOREDOO")
{



     include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india=date("Y-m-d H:i:s");
  
    $msisdn=$_GET['msisdn'];

    $clickId="Dream Mobi".rand();
    $advName="INTELLISENSE"; 
    $pubName="DREAM_MOBI";
    $serviceName="MOVI_TIMES_OOREDOO";
    $country="OMAN";
     $fp = fopen('Movi_time_OM_Oredo_oman_oredo_pin_request_log'.date("Y-m-d"), 'a');
    fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
    $api="http://45.114.143.51/eapi/d3som/pingenerate";
    $raw_data=array(
        "MSISDN"=>"$msisdn",
        "Key"=>"89bjfrqy"
      );

      $payload=json_encode($raw_data);

    //  print_r($payload);
      //die();
      $ch = curl_init('http://45.114.143.51/eapi/d3som/pingenerate');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
        );
 $result = curl_exec($ch);

        $response=json_decode($result,true);
        
        $code=$response['response'];        
      $reason=$response['errorMessage']; 
      
       
   $a="";
          fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
       if ($code=="SUCCESS") {
   
        

         $sql="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$status','$TransactionID','$serviceDate','$time_india_one')";
        mysql_query($sql);
         // print_r($sql);
        echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin generated Successfully'));
       }
else{
      $sql="INSERT INTO `in_app_pin_request` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$status','$TransactionID','$serviceDate','$time_india_one')";
      // print_r($sql);
        mysql_query($sql);
        echo json_encode(array('response'=> 'Fail','errorMessage' =>$reason));
     }



}
