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
$fp=fopen("batelco_bh_NZ_Pin_verify_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server\n");
                             
   $url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=CONFIRMPIN&opco=batelco&service=GC&msisdn=$msisdn&channel=INAPP&source=digifish&amount=875&ip=$ip&useragent=$server1&OTP=$pin&nzuname=digifish&nzpwd=$pswd";                            
                                             
$result=file_get_contents($url);

 $str = $result;
$str1 = substr($str, 3);
fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");



if($str1=="SUCCESS")
{

   
$result_monk=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=230&op=batelco_bh_NZ');
                 if($result_monk==0)//MEANS PASS
                        {
                              $status="PASSED";
                                $sql_subscription = "INSERT INTO `batelco_bh_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$pin','$result','PASSED','$time_india_one')";
                                mysql_query($sql_subscription);
    $output=array('status'=>0,'errorMessage'=>'Pin verified successfully');
                            //NEW OUTPUT
                            echo json_encode($output, JSON_PRETTY_PRINT);
                            exit();
                        }
                        if($result_monk==1)//MEANS BLOCK
                        {
                            $status="BLOCK";
                            $sql_subscription = "INSERT INTO `batelco_bh_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$pin','$result','BLOCK','$time_india_one')";
                           mysql_query($sql_subscription);
                              $output=array('status'=>1,'errorMessage'=>'Multiple Hits on msisdn');
                            echo json_encode($output, JSON_PRETTY_PRINT);
                            exit();
                        }
    
}
else
{
    $sql_subscription = "INSERT INTO `batelco_bh_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`)  VALUES('$cid','$msisdn','$pin','$result','$str1','$time_india_one')";
     mysql_query($sql_subscription);
       $output=array('status'=>1,'errorMessage'=>'wrong pin');              
                    echo json_encode($output, JSON_PRETTY_PRINT);
                    exit();
}

?>