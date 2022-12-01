<?php
       $service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=53');
 

    if($service=="GameCafe_OOREEDO")
{

       date_default_timezone_set('Asia/Kolkata');
    $dtime=date("Y-m-d H:i:s");
    $date=date("Y-m-d h:i:s");
    $request=$request=rand(111,99999);
    $fp = fopen('gamecafe_oredo_mcomviva_pin_verify'.date("Y-m-d"), 'a');

        include 'connect.php';
        date_default_timezone_set("UTC");
        $msisdn=$_GET['msisdn'];
        $pin=$_GET['pin'];
        
        $clickId="Dream Mobi".rand();
        $advName="ARSHIYA"; 
        $pubName="DREAM_MOBI";
        $serviceName="GameCafe_OOREEDO";
        $country="OMAN";
        
            $sql="SELECT `sequenceNo` from `in_app_pin_request` where `msisdn`='".$msisdn."' and pubName='DREAM_MOBI' and serviceName='GameCafe_OOREEDO' order by id desc limit 1";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $sequenceNo=$row['sequenceNo'];
        $serviceId=2656;
        $time=time()*1000;
        $data = $serviceId.'#'.$time;
        // $enc_key = "lURAbFuvPJHNMmuI"; 
          $enc_key = "uEMIRkPECkeJNdx1"; 

        $token = base64_encode( openssl_encrypt( $data, 'AES-128-ECB', $enc_key, OPENSSL_RAW_DATA));
       
  
    fwrite($fp,"\n[$dtime] $request received with MSISDN $msisdn  sequenceNo $sequenceNo otp $pin cid $cid\n");
      //  $SubscriptionContractId=$code;
        // $apiKey="393357da5a7a4bfb9ce9312020d40a2f"; old
    
        $apiKey="a4ee63e3a7434c8d8df3a616e4dad6b9"; 

        $xml_data ="<?xml version=\"1.0\" encoding=\"UTF-8\"?><ocsRequest>
          <serviceNode>ARSHIYA</serviceNode>
         <serviceId>10406</serviceId>
         <planId>46667</planId>
         <sequenceNo>".$sequenceNo."</sequenceNo>
         <callingParty>".$msisdn."</callingParty>
         <bearerId>WEB</bearerId>
         <otp>".$pin."</otp>
         </ocsRequest>";
         $URL = "http://vascld-afl.mcomviva.com:8112/AFL/OOSubConfirm";
         $ch = curl_init($URL);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml',"apiKey: $apiKey","authentication:$token"));
         curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $xmlResponse = curl_exec($ch) or die('NO RESPONSE');
         curl_close($ch);
         $xml=simplexml_load_string($xmlResponse);
        // print_r($xml);
        $payload = json_encode($xml); 
        $final_result = json_decode($payload, true);
        // print_r($final_result);
        //exit();
        $final_response=$final_result['errorCode'];
        $final_response2=$final_result['message'];
        //$date=date("Y-m-d h:i:s");
        $request=$request=rand(111,99999);
        //$fp = fopen('mcomviva_pin_verify'.date("Ymd"), 'a');
        fwrite($fp,"\n[$date] $request received with MSISDN $msisdn  sequenceNo $sequenceNo otp $pin cid $cid and hitting the url $URL with payload  $xml_data gives output $xmlResponse and errorcode $final_response using apikey $apiKey and enc_key $enc_key  and token   $token  \n");
        //echo $final_response2;//echo json_encode(array('success' =>4 ,'message'=>$final_response));
          if($final_response=="OPTIN_ACTIVE_WAIT_CHARGING")
              {
                    $result_shell=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=249&op=Dream_Mobi_GameCafe_OOREEDO');
                            if($result_shell==0)//MEANS PASS
                            {
                                 
            $panda="PASSED";

            
            $mysql_insert="INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$panda','$subscriptionResult','$serviceDate','$time_india_one')";
            mysql_query($mysql_insert);
            echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));

                                         
                            }
                            if($result_shell==1)//MEANS BLOCK
                            {
 
            $panda="BLOCK";
            $mysql_insert="INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$panda','$subscriptionResult','$serviceDate','$time_india_one')";
            mysql_query($mysql_insert);
            echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));

                                           
                            }
                      
                     
              }
        else
        {  
            $statusinfo="";    //mcomviva_ooredoo_users_sub_unsub
            $mysql_insert="INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$json_response','$panda','$subscriptionResult','$serviceDate','$time_india_one')";
            mysql_query($mysql_insert);
                
           echo json_encode(array('response'=> 'Fail','errorMessage' =>'Wrong Pin'));
        }
             


    }

    if ($service=="INTELLISENSE_OM_Oredo_two"){


   
     include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india=date("Y-m-d H:i:s");
    $clickId="Dream Mobi".rand();
    $advName="INTELLISENSE"; 
    $pubName="DREAM_MOBI";
    $serviceName="INTELLISENSE_OOREEDOO_TWO";
    $country="OMAN";
    $msisdn=$_GET['msisdn'];
    $pin=$_GET['pin'];
    
    


    $fp = fopen('two_INTELLISENSE_OM_Oredo_pin_verify_log'.date("Y-m-d"), 'a');
    fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
    $api="http://45.114.143.51/eapi/digi/PinVerify";

    $raw_data=array(
        "MSISDN"=>"$msisdn",
        "Key"=>"xbsnvmpc",
        "pinCode"=>"$pin"
      );
      $payload=json_encode($raw_data);
      $ch = curl_init('http://45.114.143.51/eapi/digi/PinVerify');
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
         $subscriptionResult=json_encode($result,true);
        $check=json_decode($result,true);
        
        $code=$check['response'];        
      $reason=$check['errorMessage']; 
       fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
       
       if ($code=="SUCCESS") {
      

     $resultsfile_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=250&op=Dream_Mobi_INTELLISENSE_OM_Oredo_two');
          if($results==0)//MEANS PASS
                        {


                            $panda="PASSED";


                            

                                   $sql= "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$TransactionID','$serviceDate','$time_india_one')";
                                 mysql_query($sql);
                                  echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                            
                            exit();
                        }

                        if($results==1)//MEANS BLOCK

                        {

                            $panda="BLOCK";
                             $sql="INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$TransactionID','$serviceDate','$time_india_one')";
                                 mysql_query($sql);
                                  echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                            exit();

                        }


       }
else{
             $sql="INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$reason','$TransactionID','$serviceDate','$time_india_one')";
        mysql_query($sql);
        echo json_encode(array('response'=> 'Fail','errorMessage' =>$reason));
}




}

if($service=="MOVI_TIMES_INTELLISENSE_OM_OOREDOO")
{


include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$clickId="Dream Mobi".rand();
    $advName="INTELLISENSE"; 
    $pubName="DREAM_MOBI";
    $serviceName="MOVI_TIMES_OOREDOO";
    $country="OMAN";
$msisdn=$_GET['msisdn'];
$pin=$_GET['pin'];

$fp = fopen('MOVI_TIMES_INTELLISENSE_OM_OOREDOO_log'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
$api="http://45.114.143.51/eapi/d3som/PinVerify";

$raw_data=array(
    "MSISDN"=>"$msisdn",
    "Key"=>"89bjfrqy",
    "pinCode"=>"$pin"
);
$payload=json_encode($raw_data);
$ch = curl_init('http://45.114.143.51/eapi/d3som/PinVerify');
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
        // print_r($result);
        // die();
$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);

$code=$check['response'];        
$reason=$check['errorMessage']; 
fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");

if ($code=="SUCCESS") {


   $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=251&op=Dream_Mobi_MOVI_TIMES_INTELLISENSE_OM_OOREDOO');
          if($results==0)//MEANS PASS
          {


            $panda="PASSED";

            
            $sql= "INSERT INTO  `in_app_pin_verify` (`clickId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$clickId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$subscriptionResult','$panda','$transactionId','$serviceDate','$time_india_one')";
            mysql_query($sql);
            echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));

            exit();
        }

                        if($results==1)//MEANS BLOCK

                        {

                            $panda="BLOCK";
                            $sql="INSERT INTO  `MOVI_TIMES_INTELLISENSE_OM_OOREDOO` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                            mysql_query($sql);
                            echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                            exit();

                        }


                    }
                    else{
                       $sql="INSERT INTO  `MOVI_TIMES_INTELLISENSE_OM_OOREDOO` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$reason."','".$time_india."')";
                       mysql_query($sql);
        // print_r($sql);
                       echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
                   }




               

}


?>
