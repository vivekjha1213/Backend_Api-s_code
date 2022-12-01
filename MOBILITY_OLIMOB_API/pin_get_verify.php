<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Dubai');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$serviceId = "ARSH0050";
$advName = "MOBILITY";
$pubName = "OLIMOB";
$serviceName = "GAMEBAR";
$country = "UAE";

$fp = fopen("GAMEBAR_MOBILITY_pin_verify" . $time_india_one, "a");
fwrite($fp, "\n $time_india_one Received $msisdn with $pin \n");


$url = "http://103.247.149.59/int/AEmob/verifyPIN?cid=26&pin=$pin&msisdn=$msisdn";
$result = file_get_contents($url);

$check = json_decode($result, true);
$code = $check['response'];
$reason = $check['errorMessage'];

// $reason = preg_replace('/[0-9]+/', '', $reason);


fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $url and Received $result \n");
if ($code == "SUCCESS") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=286&op=GAMEBAR_MOBILITY_olimob_uae');

    if ($results == 0) //MEANS PASS
    {


        $panda = "PASSED";



        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
        mysql_query($sql);


        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));

        exit();
    }
    if ($results == 1) //MEANS BLOCK

    {

        $panda = "BLOCK";



        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
        mysql_query($sql);


        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
        exit();
    }
} else {


    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$result','$serviceDate','$time_india_one')";
    mysql_query($sql);


    echo json_encode(array('response' => 'Fail', 'errorMessage' => "$reason"));
}
