<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
$clickid="OLIMOB";
$msisdn=$_GET['msisdn'];
$pin=$_GET['pin'];
$pswd='$!gIfi$h_14';
$pswd1=urlencode($pswd);
$server =  $_SERVER['HTTP_USER_AGENT'];
$server1=urlencode($server);
$ip = $_SERVER['REMOTE_ADDR'];


$sql="SELECT `tid` from `Viva_Kuwait_NZ_Pin_Request` where `msisdn`='".$msisdn."' order by id desc limit 1";
          $result=mysql_query($sql);
          $row = mysql_fetch_array($result);
          $TransactionID=$row['tid'];  




$fp=fopen("viva_Kuwait_NZ_Pin_verify_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with cid $cid server $server\n");
 

   $url="http://54.173.65.220/nz-apis/unified/inapp/clientactivity?doaction=CONFIRMPIN&opco=vivakw&service=GC&msisdn=$msisdn&channel=INAPP&source=digifish&amount=750&ip=$ip&useragent=$server1&OTP=$pin&transid=$TransactionID&nzuname=digifish&nzpwd=$pswd";

//    print_r($url);
//    die();


$result=file_get_contents($url);

// print_r($result);
//   die();

 $str = $result;
//  print_r($str);
//  die();
$str1 = substr($str,1,2);
// print_r($str1);
//    die();

fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");
//Zain_Iraq_NZ_Pin_verify

if($str1=="OK") {
      
  $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=232&op=Viva_Kuwait_NZ');

  
       if($results==0)//MEANS PASS
                     {

                         $panda="PASSED";
                                $sql="INSERT INTO  `Viva_Kuwait_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$str."','".$panda."','".$time_india."')";
                              mysql_query($sql);

                               echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                           
                         exit();
                     }

                     if($results==1)//MEANS BLOCK

                     {

                         $panda="BLOCK";
                          $sql="INSERT INTO  `Viva_Kuwait_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$str."','".$panda."','".$time_india."')";
                              mysql_query($sql);
                               echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                              
                         exit();

                     }


    }
else{
          $sql="INSERT INTO  `Viva_Kuwait_NZ_Pin_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$str."','".$str1."','".$time_india."')";
     mysql_query($sql);
     echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
    
}


?>