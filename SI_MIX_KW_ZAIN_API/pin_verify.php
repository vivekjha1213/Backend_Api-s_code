<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kuwait');
$serviceDate = date("Y-m-d H:i:s");
$code = $_GET['pin'];
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0086";
$advName = "IN-HOUSE";
$pubName = "MOVIPLUS";
$serviceName = "SOUTH-INDIAN-MIX";
$country = "KUWAIT";
$ip = $_SERVER['REMOTE_ADDR'];

//fetching the data from pin_request.

$sql = "SELECT `status`,`finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$TransactionID = $row['status'];
$ssidID = $row['finalStatus'];

$transID = $TransactionID;
$ssid = $ssidID;

$fp = fopen("S.I.M_pin_verify" . $time_india_one, "a");
fwrite($fp, "\n[$time_india_one] Received $msisdn  and Otp $code\n");

$pin_validator = "http://vascld-cgw.mcomviva.com:8093/API/OTPValidateActionApp?msisdn=$msisdn&request_locale=en&reqMode=APP&transId=$transID&productName=SouthIndia%20Zone&pPrice=2000&pVal=30&Otp=$code&contentId=default&CpId=ARSHD-01&srcIP=$ip&optionalParameter1=$ssid&optionalParameter2=1002&opId=101";

$url = $pin_validator;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
$result = simplexml_load_string($output);
$json = json_encode($result);
$array = json_decode($json, TRUE);
//print_r($result);
$errorDesc = $array['errorDesc'];
$error_code = $array['error_code'];


fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $pin_validator and Received $result  \n");

if ($errorDesc == "Verified") {
    $result = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=325&op=SOUTH_INDIAN_MIX_KW_MOVIPLUS_INHOUSE');
    if ($result == 0) //MEANS PASS
    {
        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$output','$panda','$error_code','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");
        fclose($fp);
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Succesfully Validated'));
        exit();
    }
    if ($result == 1) //MEANS BLOCK
    {
        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$output','$panda','$error_code','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n $time_india_one Received $msisdn  Inside  blocked(Some internal error) block trigger $sql \n");
        fclose($fp);
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi hit on the msisdn'));
        exit();
    }
} else {


    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$output','$errorDesc','$error_code','$serviceDate','$time_india_one')";
    mysql_query($sql);

    fwrite($fp, "\n[$time_india_one] Inside  failure block with error $errorDesc trigger $sql\n");
    fclose($fp);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
    exit();
}
