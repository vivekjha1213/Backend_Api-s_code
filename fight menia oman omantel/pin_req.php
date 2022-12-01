<?php
include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india=date('Y-m-d');
  $msisdn=$_GET['msisdn'];
  $transactionId=rand();
  $fp=fopen("FMpin_Request_".$time_india,"a");
  fwrite($fp,"\n[$time_india] Received $msisdn with  transactionId $transactionId\n");
  $time_india_one=date("Y-m-d h:i:s");
  //Added By Rajendra
  // API URL
  $url="http://45.114.143.164/adpoke/cnt/inapp/sendotp?adid=27&cmpid=864&token=$transactionId&msisdn=$msisdn";
  $result=file_get_contents($url);
  $check=json_decode($result,true);
  $code=$check['msg'];
  $reason=$check['status'];
  fwrite($fp,"\n[$time_india] Received $msisdn hit the api $url and Received $result\n");
  //die();
  if($code=="SUCCESS")
  {

          $sql_subscription = "INSERT INTO `fight_club_request` (`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$msisdn', '$code','$result','$reason','$time_india_one')";
          mysql_query($sql_subscription);
          $status=0;
          $message="Pin generated Successfully";
          $output = array('status'=>$status,
                          'errorMessage'=>$message,
                          );
          echo json_encode($output, JSON_PRETTY_PRINT);
          exit();
  }
  else
  {
          $sql_subscription = "INSERT INTO `fight_club_request` (`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$msisdn', '$code','$result','$reason','$time_india_one')";
          mysql_query($sql_subscription);
          $status=1;
          $message=$reason;
          $output = array('status'=>$status,
                          'errorMessage'=>$message,
                          );
          echo json_encode($output, JSON_PRETTY_PRINT);
          exit();
  }

?>