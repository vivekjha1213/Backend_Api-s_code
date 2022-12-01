<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
$cid="OLIMOB";
$msisdn=$_GET['msisdn'];
$pin=$_GET['pin'];
$pswd='$!gIfi$h_14';
$pswd1=urlencode($pswd);
$server =  $_SERVER['HTTP_USER_AGENT'];
$server1=urlencode($server);
$ip = $_SERVER['REMOTE_ADDR'];
$fp=fopen("Zain_Iraq_NZ_Pin_verify_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server\n");
                             
   $url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=CONFIRMPIN&opco=zainiraq&service=GC&msisdn=$msisdn&channel=INAPP&source=digifish&amount=250&ip=$ip&useragent=$server1&OTP=$pin&nzuname=digifish&nzpwd=$pswd";                            
                                             
$result=file_get_contents($url);
// print_r($result);
//  die();

 $str = $result;
$str1 = substr($str,0,2);
// print_r($str1);
//  die();
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");


//Zain_Iraq_NZ_Pin_verify

if ($str1=="SUCCESS") {
      
  $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=231&op=Zain_iraq_NZ');

  
       if($results==0)//MEANS PASS
                     {

                         $panda="PASSED";
                                $sql="INSERT INTO  `Zain_Iraq_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                              mysql_query($sql);

                               echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                           
                         exit();
                     }

                     if($results==1)//MEANS BLOCK

                     {

                         $panda="BLOCK";
                          $sql="INSERT INTO  `Zain_Iraq_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                              mysql_query($sql);
                               echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                              
                         exit();

                     }


    }
else{
          $sql="INSERT INTO  `Zain_Iraq_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$reason."','".$time_india."')";
     mysql_query($sql);
     echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
    
}


?>