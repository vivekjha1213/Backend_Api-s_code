<?php
include("../connect.php");
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Gaza');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0125";
$advName = "IN-HOUSE";
$pubName = "OLIMOB";
$serviceName = "BEYOND_LIFE_STYLE";
$country = "PALESTINE";
$msisdn = $_GET['msisdn'];
$OTP = $_GET['pin'];

$fp = fopen("Verify_" . $time_india_one, "a");
fwrite($fp, "\n[$time_india] Received $msisdn with OTP $OTP  and CID $cid\n");

// API URL
$url = "http://jawwal.mediaworldiq.com/dcb/API/VMS-DCBSubscription/actions/verifyPincode?user=arshiya&password=@rsh!y@22&msisdn=$msisdn&shortcode=37788&serviceId=11161&spId=5249&pincode=$OTP";


// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$url");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);


$check = json_decode($result, true);
$finalResult = json_encode($check);
$code = $check['status'];
$reason = $check['msg'];




fwrite($fp, "\n[$time_india_one] Received $msisdn with $url and result $result'\n");

if ($code == "Success") {


    //Fileter for P,B
    $results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=362&op=BEYOND_LIFE_STYLE_Palestine_Inhouse_Olimob');

    if ($results == 0) //MEANS PASS
    {
        $panda = "PASSED";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$reason','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");

        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    }
    if ($results == 1) //MEANS BLOCK

    {
        $panda = "BLOCK";
        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$panda','$reason','$serviceDate','$time_india_one')";
        mysql_query($sql);
        fwrite($fp, "\n $time_india_one Received $msisdn Query $sql \n");

        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi Hits on Msisdn '));

        exit();
    }
} else {
    $send = "wrong pin";
    $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$OTP','$result','$send','$reason','$serviceDate','$time_india_one')";
    mysql_query($sql);

    fwrite($fp, "\n $time_india_one Received $msisdn  Query  $sql \n");
    echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
}
