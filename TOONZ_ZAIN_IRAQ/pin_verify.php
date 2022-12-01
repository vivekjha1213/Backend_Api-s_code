<?php

include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Baghdad');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0121";
$advName = "U2OPIA";
$pubName = "OLIMOB";
$serviceName = "TOONZ";
$country = "IRAQ-ZAIN";
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];


$sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' serviceId='ARSH0121'order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$TransactionId = $row['finalStatus'];

$TransactionId = $TransactionId;

$fp = fopen("TOONZ_Verify_" . $time_india_one, "a");

$url = "http://3.109.105.30:8181/InappZainIraq/validateOtp?msisdn=$msisdn&serviceName=INMTNZ&operatorId=ZIQ&transactionId=$TransactionId&vendorId=ZainIraq&serviceId=270&shortCode=4089&pincode=$pin";



$result = file_get_contents($url);

$check = json_decode($result, true);

$code = $check['success'];
$reason = $check['message'];

fwrite($fp, "\n[$time_india_one] Received $msisdn  With OTP $pin OR  $url and with payload $payload result $result'\n");
fclose($fp);

if ($code == "true") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=358&op=TOONZ_U2OPIA_OLIMOB_IRAQ_ZAIN');

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
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$send','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);

    // fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");

    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
