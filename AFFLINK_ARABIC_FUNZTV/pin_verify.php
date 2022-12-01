<?php
include("connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
$time_india = date('Y-m-d');
date_default_timezone_set('Asia/Baghdad');
$serviceDate = date("Y-m-d H:i:s");
$serviceId = "ARSH0067";
$advName = "ALPHA MOVIL";
$pubName = "AFFLINK";
$serviceName = "ARABIC_FUNTV";
$country = "IEAQ_ZAIN";
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$sql = "SELECT `finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$tid = $row['finalStatus'];

$fp = fopen("Arabic_FunTV_pin" . $time_india, "a");
fwrite($fp, "\n $time_india_one Received $msisdn with $op \n");

$ch = curl_init();
$url = "http://202.143.97.40/adpokeinapp/cnt/inapi/pin/validation?msisdn=$msisdn&cmpid=105&txid=$tid&pin=$pin";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
$check = json_decode($result, true);
$code = $check['response'];
$reason = $check['errorMessage'];

$reason = preg_replace('/[0-9]+/', '', $reason);


fwrite($fp, "\n[$time_india] Received $msisdn hit the api $url and Received $result \n");
if ($code == "SUCCESS") {

    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=302&op=Arabic_FunTV_IRAQ_ZAIN_AFFLINK');

    if ($results == 0) //MEANS PASS
    {
        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$tid','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");

        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
        exit();
    }

    if ($results == 1) //MEANS BLOCK

    {
        $panda = "BLOCK";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$tid','$serviceDate','$time_india_one')";
        mysql_query($sql);
        fwrite($fp, "\n $time_india_one Received $msisdn Query $sql \n");

        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));

        exit();
    }
} else {
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$tid','$serviceDate','$time_india_one')";
    mysql_query($sql);

    fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
?>