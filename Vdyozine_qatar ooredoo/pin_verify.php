<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Qatar');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0120";
$advName = "ASCENSO"; 
$pubName = "OLIMOB";
$serviceName = "VDYOZINE"; 
$country = "QATAR-OOREDOO";

$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];


$sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' and serviceId='ARSH0120' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$TransactionID = $row['finalStatus'];

$TransactionID = $TransactionID;

$fp = fopen("Verify_" . $time_india_one, "a");
// API URL
$url = "http://vodqtr.newstor.net/ascenso-vas-services/2024/validateOTP";
$ch = curl_init($url);
$data = array(
    "TransactionID" => "$TransactionID",
    "IDService" => "2041",
    "MSISDN" => "$msisdn",
    "pinCode" => "$pin",
    "promoMode" => "digifish"
);
$payload = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$url");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$payload");

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Base ZGlnaWZpc2hAJEMxMg==';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close($ch);

$check = json_decode($result, true);
$code = $check['response'];
$reason = $check['errorMessage'];


fwrite($fp, "\n[$time_india_one] Received $msisdn  With OTP $pin OR  $url and with payload $payload result $result'\n");
fclose($fp);

if ($code == "SUCCESS") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=356&op=POWERPLAY_QATAR_VODA_ASCENSO_OLIMOB');

    if ($results == 0) //MEANS PASS
    {
        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$code','$serviceDate','$time_india_one')";
        mysql_query($sql);

        //  fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");

        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    }
    if ($results == 1) //MEANS BLOCK

    {
        $panda = "BLOCK";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$code','$serviceDate','$time_india_one')";
        mysql_query($sql);

        //  fwrite($fp, "\n $time_india_one Received $msisdn Query $sql \n");

        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi Hits on the Msisdn'));
        exit();
    }
} else {
    $send = "Invalid Otp";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$send','$code','$serviceDate','$time_india_one')";
    mysql_query($sql);

    // fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
