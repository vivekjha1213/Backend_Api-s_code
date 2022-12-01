<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$clickid="OLIMOB";
$msisdn=$_GET['msisdn'];
$pin=$_GET['pin'];
$fp = fopen('Game_Omantel_daily_pin_verify_log'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn \n");
$api="http://203.122.59.25:8080/otpHandler/omanOmantel/validateOtp";

$raw_data=array(  
        "userIdentifier"=>"$msisdn",
        "transactionAuthCode"=>"$pin",
        "entryChannel"=>"WEB",
        "packName"=>"Game_Omantel_daily",
        "agency"=>"clickzmedia"

);  
$payload=json_encode($raw_data);

$ch = curl_init('http://203.122.59.25:8080/otpHandler/omanOmantel/validateOtp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);

$result = curl_exec($ch);


fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result pin $pin\n");
$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);       
$code=$check['responseData']['subscriptionResult'];
$reason=$check['responseData']['subscriptionError'];


 fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result pin $pin\n");

       if ($code=="OPTIN_PREACTIVE_WAIT_CONF") {
      

     $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=228&op=Game_Omantel_daily');

     
          if($results==0)//MEANS PASS
                        {
   
                            
                           
                            $panda="PASSED";
                                   $sql="INSERT INTO  `cm_om_omentel_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);

                                  echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                              
                            exit();
                        }

                        if($results==1)//MEANS BLOCK

                        {

                            $panda="BLOCK";
                             $sql="INSERT INTO  `cm_om_omentel_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);
                                  echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                                 
                            exit();

                        }


       }
else{
             $sql="INSERT INTO  `cm_om_omentel_pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$reason."','".$time_india."')";
        mysql_query($sql);
        echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
       
}


?>
