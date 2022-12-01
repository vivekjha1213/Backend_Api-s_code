<?php
 // echo json_encode(array('success' => 1));
 // exit();
	include 'connect.php';
	$msisdn=$_GET['msisdn'];
	$otp=$_GET['pin'];
	$clickid=$_REQUEST['cid'];
    $sql="SELECT `finalStatus` from `in_app_pin_request` WHERE `msisdn`='".$msisdn."' order by id desc limit 1";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $token=$row['finalStatus'];
	start_first($msisdn,$otp,$clickid,$token);


function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('sha256', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

    function start_first($msisdn,$otp,$clickid,$token)
    {   
       
        include 'connect.php';
        //NEW PARAMETERS ADDED BY RAJENDRA
        
        
        /*$key1 = "DHDUFYlinsGDDSSs";
        //$username1 = 'Arshiya';
        //$password1 = 'ARshiy9865';
        $username1 = 'Arshiya';//Added By Rajendra
        $password1 = 'o@jiGIm@0IIfA8N';//Added By Rajendra
        $packageid1 = '1323'; // weekly offer, daily offer is 1322
        if($pack=='daily'){
                $packageid1 = '1322'; // daily offer, weekly offer is 1323^M
        }else{
                $packageid1 = '1323'; // weekly offer, daily offer is 1322^M
        }*/
        date_default_timezone_set('Asia/Kolkata');
        $date_india=date("Y-m-d H:i:s");
        $request=$request=rand(111,99999);


        date_default_timezone_set('Asia/Kolkata');
        $time_india_one = date("Y-m-d H:i:s");
        date_default_timezone_set('Asia/Dubai');
        $serviceDate = date("Y-m-d H:i:s");
        $serviceId = "ARSH0080";
        $advName = "IN-HOUSE";
        $pubName = "Afflink";
        $serviceName = "BEYOND_LIFESTYLE"; 
        $country = "UAE";

        
        $ip=$_SERVER['REMOTE_ADDR'];
        $phone=$msisdn;
        $pin1=$otp;
        $clickid=$clickid;
        $received_token=$token;
        $key1="DHDUFYlinsGDDSSs";
        // $username1='DigiFish';
        // $password1='dbdAur87353nsjqa';
        $username1='Arshiya';
        $password1='o@jiGIm@0IIfA8N';
        //for Daily
        $packageid1='1928';
        // $correlatorId=$extra;
        $fp = fopen('bls_pin_verify_'.date("Y-m-d"), 'a');
        fwrite($fp,"\n[$date_india] Request for pin verify $request  MSISDN $phone and otp $pin1 and token $received_token\n");
        $username = aes128Encrypt($key1, $username1);
        $password = aes128Encrypt($key1, $password1);
        $mobile12 = aes128Encrypt($key1, $phone);
        $mobile = urlencode($mobile12);
        $packageid = aes128Encrypt($key1, $packageid1);
        $pin2 = aes128Encrypt($key1, $pin1);
        
        $requestArray = array();
        $requestArray['user'] = $username;
        $requestArray['password'] = $password;
        $requestArray['msisdn'] = $mobile12;
        $requestArray['packageId'] = $packageid;
        $requestArray['txnId'] =$clickid;
        $requestArray['channel'] ='web';
        $requestArray['token'] = $received_token;
        $requestArray['pin'] = $pin2;
        $requestArray['sourceIP'] = $ip;
        $requestArray['adPartnerName'] = '';
        $requestArray['pubId'] = '';
        
        $data_string = json_encode($requestArray);
        
        //http://pt5.etisalat.ae/Moneta/confirmPinSubscription.htm?usr=<USERNAME>&pwd=<PASSWORD>& msis dn=<     MSISDN>&packageid=<PACKAGEID>&pin=<PIN>&token=<TOKEN>   
        //$api_url = 'http://pt5.etisalat.ae/Moneta/confirmPinSubscription.htm?usr=' . $username . '&pwd=' . $password . '&msisdn=' . $mobile . '&packageid=' . $packageid . '&pin=' . $pin2 . '&token=' . $correlatorId;

        $api_url = 'https://pt5.etisalat.ae/Moneta/confirmPOSTPin.htm';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        $data = explode('|', $result);
        $response = $data[0];
        $token = $data[1];
        

        
        fwrite($fp,"\n[$date_india] $request  MSISDN $phone hitting Url $api_url with payload $data_string and received result $result\n");
        
        fclose($fp);

        if($response=='success')

    

{
    $results =file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=309&op=UAE_ETISALAT_BEYONDLIFE_STYLE_DIGIPII');
  
    if ($results == 0) //MEANS PASS
    {
  
      $panda="PASSED";

      
 // $sql_subscription = "INSERT INTO `beyondlifestyle_uae_alt_pin_verify`(`cid`,`msisdn`,`response`, `status`,`date_india`,`otp`) VALUES ('$clickid', '$msisdn','$response','$status','$date_india','$otp')";
            // mysql_query($sql_subscription);

      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$panda','$response','$serviceDate','$time_india_one')";
      mysql_query($sql);
  
  
      echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
  
      exit();
    }
    if ($results == 1) //MEANS BLOCK
  
    {
  
      $panda="BLOCK";
      $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$result','$panda','$response','$serviceDate','$time_india_one')";
      mysql_query($sql);
  
      echo json_encode(array('response' => 'Fail', 'errorMessage' => 'server error'));
      exit();
    }
   
  }
  else {

    $status="Failed";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin1','$api_url','$response','$status','$serviceDate','$time_india_one')";
    mysql_query($sql);
  
    echo json_encode(array('response' => 'Fail', 'errorMessage' => $response));
  
  }
  
    }

?>


