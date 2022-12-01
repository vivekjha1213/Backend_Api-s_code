<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one=date("Y-m-d H:i:s");
$time_india=date('Y-m-d');
date_default_timezone_set('Africa/Cairo');
$serviceDate=date("Y-m-d H:i:s");
$msisdn=$_GET['msisdn'];
$serviceId="ARSH0023";
$advName="CLICKANDGET"; 
$pubName="OLIMOB";
$serviceName="GAMZFUN";
$country="EGYPT";
$pin=$_GET['pin'];

 $sql="SELECT `status` from `in_app_pin_request` where `msisdn`='".$msisdn."' order by id desc limit 1";
 $result=mysql_query($sql);
 $row = mysql_fetch_array($result);
 $op=$row['status'];
        



$fp=fopen("Egypt_All_Op_Pin_Verify_log".$time_india,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with $op \n");
 
   $url="http://3.111.156.11/inapp/pinval.php?pubid=3&co=eg&op=$op&msisdn=$msisdn&pin=$pin";

  //  print_r($url);
  // die();

$result=file_get_contents($url);

$string="$result";
$a='""';
$b = str_replace("'",$a,$string);
$check=json_decode($b,true);
$code=$check['response'];
$reason=$check['errorMessage'];


fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result \n");
if($code=="SUCCESS") {
      
  $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=256&op=GAMZFUN_Egypt_all_Op');

  
       if($results==0)//MEANS PASS
                     {

                         $panda="PASSED";

                         
                         $sql="INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$op','$serviceDate','$time_india_one')";
                         mysql_query($sql);
              

                               echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                           
                         exit();
                     }

                     if($results==1)//MEANS BLOCK

                     {

                         $panda="BLOCK";
                         $sql="INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$op','$serviceDate','$time_india_one')";
                         mysql_query($sql);
              
                               echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                              
                         exit();

                     }


                    }

else

{
              $sql="INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$op','$serviceDate','$time_india_one')";

               mysql_query($sql);

     echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
    
}    

                

 ?>
