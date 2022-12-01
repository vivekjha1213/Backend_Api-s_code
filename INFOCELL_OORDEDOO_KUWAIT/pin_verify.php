<?php
include 'connect.php';
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Kuwait');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$pin = $_GET['pin'];
$serviceId = "ARSH0034";
$advName = "ALTRUSIT";
$pubName = "OLIMOB";
$serviceName = "MYINFO2CELL";
$country = "OOREDOO KUWAIT";
$user_ip = $_SERVER['REMOTE_ADDR'];
$usr = "rvYIZZmCa+dKqH0g4y2R3A==";
$pass = "StQsUnH9CraHlj3FJkbSbg==";
$deviceID = $_SERVER['HTTP_USER_AGENT'];
$request = "pin_verify";

$sql = "SELECT `status` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$DATA = $row['status'];

$String_Split = explode("|", $DATA);
$request_id =$String_Split[0];
$token = $String_Split[1];

$fp = fopen('MYINFO2CELL_OOREDOO_KUWAIT_VERIFY_LOG' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn server $user_ip with deviceID $deviceID \n");

$api = "http://webapi.myinfo2cell.com/mobile_app_api.php";

$raw_data = array(
    "request" => "$request",
    "alias" => "9779",
    "usr" => "$usr",
    "pass" => "$pass",
    "pin" => "$pin",
    "package_id" => "9455",
    "msisdn" => "$msisdn",
    "ip" => "$user_ip",
    "request_id" => "$request_id",
    "token" => "$token",
    "device_id" => "$deviceID"
);
$payload = json_encode($raw_data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://webapi.myinfo2cell.com/mobile_app_api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$payload");
$headers = array();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close($ch);

$subscriptionResult = json_encode($result, true);
$check = json_decode($result, true);
$code = $check['result']['status'];
$reason = $check['result']['response'];

fwrite($fp, "\n[$time_india_one]  after hitting the URL $api with payload $payload and got the response $result pin $pin\n");

if ($code == "1") {


    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=267&op=MYINFO2CELL_OOREDOO_KUWAIT');
    if ($results == 0) //MEANS PASS
    {

        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$code','$serviceDate','$time_india_one')";
        mysql_query($sql);
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));

        exit();
    }
    if ($results == 1) //MEANS BLOCK

    {
        $panda = "BLOCK";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$code','$serviceDate','$time_india_one')";
        mysql_query($sql);
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
        exit();
    }
} else {
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$reason','$result','$serviceDate','$time_india_one')";
    mysql_query($sql);
    echo json_encode(array('response' => 'Fail', 'errorMessage' => "$reason"));
}
?>