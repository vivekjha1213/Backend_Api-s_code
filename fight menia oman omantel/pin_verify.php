  <?php
  include("connect.php");
  date_default_timezone_set('Asia/Kolkata');
  $time_india=date('Y-m-d');
  //$serviceId=$_GET['serviceId'];
  //$countryCode=$_GET['countryCode'];
  //$operatorName=$_GET['operatorName'];
  //$contractid=$_GET['contractid'];
  $msisdn=$_GET['msisdn'];
  //GETTING TRANSACTION ID FROM HERE
  $sql="SELECT `pin` from `fight_club_request` where `msisdn`='".$msisdn."' order by id desc limit 1";
  $result=mysql_query($sql);
  $row = mysql_fetch_array($result);
  $transactionId=$row['pin'];
  //GETTING TRANSACTION ID FROM HERE
  //$transactionId=$_GET['transactionId'];
  $pin=$_GET['pin'];
  $fp=fopen("FMpin_Verify_".$time_india,"a");
  fwrite($fp,"\n[$time_india] Received $msisdn with transactionId $transactionId pin $pin\n");
  //Added By Rajendra
  // API URL
  $time_india_one=date("Y-m-d h:i:s");
  $url="http://45.114.143.164/adpoke/cnt/inapp/validateotp?adid=27&cmpid=864&token=$transactionId&msisdn=$msisdn&param1=$pin";

  $result=file_get_contents($url);

  fwrite($fp,"\n[$time_india] Received $msisdn hitted $url  and received result $result\n");

  /*{"msg":"FAILED","status":"false"}

  {"msg":"SUCCESS","status":"true"}*/

  $check=json_decode($result,true);
  $code=$check['msg'];
  $reason=$check['status'];
  //echo $code;
  //echo $message;
  if($code=="SUCCESS" || $code=="SUBSCRIBED")
                  {
                          $fp=fopen("pin_Verify_".$time_india,"a");

                          $time_india_one=date('Y-m-d H:i:s');

                          $service=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=29&op=FIGHT_MANIA');

                          if($service==0)//MEANS PASS
                          {

                              $status=0;
                              $panda="PASSED";
                              $sql_subscription = "INSERT INTO `fight_club_verify` (`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$msisdn', '$pin','$result','$panda','$time_india_one')";
                              mysql_query($sql_subscription);
                              fwrite($fp,"\n[$time_india] Inside Passed $msisdn , pin $pin and triggered  query $sql_subscription\n");
                              fclose($fp);
                              $message="Pin verified Successfully";
                              $output = array('status'=>$status,
                                              'errorMessage'=>$message,
                                              );
                              //NEW OUTPUT
                              echo json_encode($output, JSON_PRETTY_PRINT);
                              exit();
                          }

                          if($service==1)//MEANS BLOCK

                          {
                              $status=1;
                              $panda="BLOCK";
                              $sql_subscription = "INSERT INTO `fight_club_verify` (`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$msisdn', '$pin','$result','$panda','$time_india_one')";
                              mysql_query($sql_subscription);
                              fwrite($fp,"\n[$time_india] Inside blocked $msisdn , pin $pin and triggered  query $sql_subscription\n");
                              fclose($fp);
                              $message='Failure';
                              $output = array('status'=>$status,
                                              'errorMessage'=>$message,
                                              );
                              echo json_encode($output, JSON_PRETTY_PRINT);
                              exit();

                          }


                  }
                  else
                  {
                      $status=1;
                      $panda=$reason;
                      //$result=trim($OUTPUT);
                      $time_india_one=date('Y-m-d H:i:s');
                      $sql_subscription = "INSERT INTO `fight_club_verify` (`msisdn`,`pin`,`subscriptionResult`, `status`,`date`) VALUES ('$msisdn', '$pin','$subscriptionResult','$panda','$time_india_one')";
                      mysql_query($sql_subscription);
                      $output = array('status'=>$status,
                                      'errorMessage'=>$panda,
                                      );
                      echo json_encode($output, JSON_PRETTY_PRINT);
                      exit();
                  }

?>