<?php


include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Gaza');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0095";
$advName = "INHOUSE";
$pubName = "AFFLINK";
$serviceName = "BEYOND_HEALTH";
$country = "PS-Jawal";
$msisdn = $_GET['msisdn'];
$pin=$_GET['pin'];



    $sql="SELECT `status`,`finalStatus` from `in_app_pin_request` WHERE `msisdn`='$msisdn' and serviceId='ARSH0095' order by id desc limit 1";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $ID=$row['status'];
    $token=$row['finalStatus'];
	

    $transactionId=rand();

    

$fp=fopen("DCB_Jawwal_Palestine_Pin_Verify_".$time_india_one,"a");
fwrite($fp,"\n $time_india_one Received $msisdn with transactionId $transactionId \n");

$url="http://dcb.universe-telecom.com/api/v2/Subscription/Confirmation?ID=$ID";
$ch = curl_init($url);

$raw_data=array(
            "PinCode"=>$pin
              );
$payload=json_encode($raw_data);


//curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);

$headers = array();
$headers[] ="Authentication: Bearer ZGlnaWZpc2gzOmRpZ2lmaXNoM0B1dA==";
$headers[] ="Authorization:Bearer $token";
$headers[] ="Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$APIResult=curl_exec($ch);
curl_close($ch);
//////////WORKING TILL HERE

$result=json_decode($APIResult,true);
$response=$result['IsSubscribed'];
$reason=$result['ErrorCode'];
$reason2=$result['MessageEn'];


fwrite($fp,"\n[$time_india_one] Received $msisdn hit the api $url and Received $APIResult\n");

if($response=="true")
{ $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=335&op=BEYOND_HEALTH_PS_JAWAL');

    if ($results == 0) //MEANS PASS
    {
      $panda = "PASSED";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin ','$APIResult','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);


      echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
      exit();
    }
    if ($results == 1) //MEANS BLOCK
    {
      $panda = "BLOCK";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$APIResult','$panda','$reason','$serviceDate','$time_india_one')";
      mysql_query($sql);
      echo json_encode(array('response' => 'Fail', 'errorMessage' => 'user already subscribed'));
      exit();
    }
  } else {

    $reason1="wrong pin";

    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$APIResult','$reason1','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin code'));

    exit();
  }
