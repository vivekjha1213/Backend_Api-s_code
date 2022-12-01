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
$tid=rand();
$ip = $_SERVER['REMOTE_ADDR'];

$appurl=$_SERVER['HTTP_HOST'];


$fp=fopen("nazara_dc_qa_voda_pin_verify_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server $pswd\n");

$url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=CONFIRMPIN&opco=dcqavodafone&service=DC&msisdn=$msisdn&channel=INAPP&source=nazara&amount=30&ip=$ip&useragent=$server1&OTP=$pin&transid=$tid&nzuname=digifish&nzpwd=$pswd";



$result=file_get_contents($url);

//  print_r($result);
//  die();

$str = $result;
$str1 = substr($str,3);



fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result subscriptionResult $subscriptionResult\n");

   if($result=="OK|SUCCESS")
              {
     $result_shell=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=227&op=D_club_nazara_qa_voda');

                            if($result_shell==0)//MEANS PASS
                            {
                                 
            $panda="PASSED";
            $mysql_insert="INSERT INTO `nazara_dc_qa_voda_pin_verify` (`cid`,`msisdn`,`pin`,`status`,`date`,`subscriptionResult`)
                         VALUES('".$cid."','".$msisdn."','".$pin."','".$panda."','".$time_india_one."','".$str."')";
            mysql_query($mysql_insert);
            echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'OK'));

                                         
                            }
                            if($result_shell==1)//MEANS BLOCK
                            {
 
            $panda="BLOCK";
            $mysql_insert="INSERT INTO `nazara_dc_qa_voda_pin_verify` (`cid`,`msisdn`,`pin`,`status`,`date`,`subscriptionResult`)
                         VALUES('".$cid."','".$msisdn."','".$pin."','".$panda."','".$time_india_one."','".$str."')";
            mysql_query($mysql_insert);
            echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));

                                           
                            }
                      
                     
              }
        else
        {  
            $statusinfo="";    //mcomviva_ooredoo_users_sub_unsub
            $mysql_insert="INSERT INTO `nazara_dc_qa_voda_pin_verify` (`cid`,`msisdn`,`pin`,`status`,`date`,`subscriptionResult`)
                         VALUES('".$cid."','".$msisdn."','".$pin."','".$str."','".$time_india_one."','".$str."')";
            mysql_query($mysql_insert);
                
           echo json_encode(array('response'=> 'Fail','errorMessage' =>'Wrong pin'));
        }


?>


