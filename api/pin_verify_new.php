<?php
    

include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india=date("Y-m-d H:i:s");
$clickid="OLIMOB";
$msisdn=$_GET['msisdn'];
$pin=$_GET['pin'];




//$tid=rand();

$fp = fopen('two_qatar_oreedoo_pin_verify'.date("Y-m-d"), 'a');
fwrite($fp,"\n[$time_india]  received msisdn $msisdn key $key \n");
$api="http://45.114.143.51/eapi/d3s/PinVerify";

$raw_data=array(
        "MSISDN"=>"$msisdn",
        "Key"=>"b7ruarry",
        "pinCode"=>"$pin"
    
);
$payload=json_encode($raw_data);
$ch = curl_init('http://45.114.143.51/eapi/d3s/PinVerify');
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
 //print_r($result);
 //die();
 fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result pin $pin\n");

$subscriptionResult=json_encode($result,true);
$check=json_decode($result,true);

    // Submit the POST request
        $result = curl_exec($ch);
        
    //  print_r($result);
    //  die();
         $subscriptionResult=json_encode($result,true);
        $check=json_decode($result,true);
        
      $code=$check['response'];        
      $reason=$check['errorMessage']; 
      fwrite($fp,"\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
       
       if ($code=="SUCCESS") {
      

     $results=file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=218&op=Two_intellence_qatar_ooredoo');

     
          if($results==0)//MEANS PASS
                        {


                            $panda="PASSED";
                                   $sql="INSERT INTO  `Two_qatar_oreedoo_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);
                                  echo json_encode(array('response'=> 'SUCCESS','errorMessage' =>'Pin verified successfully'));
                            
                            exit();
                        }

                        if($results==1)//MEANS BLOCK

                        {

                            $panda="BLOCK";
                             $sql="INSERT INTO  `Two_qatar_oreedoo_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$panda."','".$time_india."')";
                                 mysql_query($sql);
                                  echo json_encode(array('response'=> 'Fail','errorMessage' =>'Multiple Hits on msisdn'));
                            exit();

                        }


       }
else{
             $sql="INSERT INTO  `Two_qatar_oreedoo_verify` (`cid`,`msisdn`,`pin`,`subscriptionResult`,`status`,`date`) VALUES('".$clickid."','".$msisdn."','".$pin."','".$result."','".$reason."','".$time_india."')";
        mysql_query($sql);
        echo json_encode(array('response'=> 'Fail','errorMessage' =>'wrong pin'));
        // print_r($sql);
        // die();
}


?>

