<?php

include("connect.php");
$sql = "SELECT `finalStatus`,`status` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' order by id desc limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$TransactionID = $row['finalStatus'];
$ssidID = $row['status'];
$msisdn = $_GET['msisdn'];
$transID = $TransactionID;
$code = $_GET['pin'];
if ($msisdn == "" || $transID == "" || $code = "") {
    echo json_encode(array('Status' => "Some credentials are missing."));
    exit();
} else {
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Kuwait');
    $serviceDate = date("Y-m-d H:i:s");
    $code = $_GET['pin'];
    $msisdn = $_GET['msisdn'];
    $transID = $TransactionID;
    $ssid = $ssidID;
    $serviceId = "ARSH0075";
    $advName = "IN-HOUSE";
    $pubName = "MOBITIZE";
    $serviceName = "SOUTH-INDIAN-MIX";
    $country = "KUWAIT";
    $ip = $_SERVER['REMOTE_ADDR'];

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
    // $cid = "ELIZABETH PROMOTION";

    //$sql = "INSERT INTO `kuwait_Zain_pin_validate`  (`msisdn`,`response`,`trans_id`) VALUES('".$msisdn."','".$errorDesc."','".$transID."')";
    //mysql_query($sql,$conn2);

    if ($errorDesc == "Verified") {
        $result = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=11&op=SOUTHINDIAN_MIX_KW');
        if ($result == 0) //MEANS PASS
        {
            $panda = "PASSED";
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$result','$panda','$error_code','$serviceDate','$time_india_one')";
            mysql_query($sql);

            fwrite($fp, "\n $time_india_one Received $msisdn  Query $sql \n");
            fclose($fp);
            echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
            exit();
        }
        if ($result == 1) //MEANS BLOCK
        {
            $panda = "PASSED";
            $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$result','$panda','$error_code','$serviceDate','$time_india_one')";
            mysql_query($sql);

            fwrite($fp, "\n $time_india_one Received $msisdn  Inside  blocked(Some internal error) block trigger $sql \n");
            fclose($fp);
            echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Some Internal Error.'));
            exit();

         
        }
    } else {

        // $sql = "INSERT INTO `southindianmix_pin_verify` (`cid`,`msisdn`,`response`,`trans_id`,`date_india`,`date_kuwait`) VALUES('" . $cid . "','" . $msisdn . "','" . $errorDesc . "','" . $transID . "','" . $date_india . "','" . $date_kuwait . "')";
        // mysql_query($sql, $conn2);


        $sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$code','$result','$errorDesc','$error_code','$serviceDate','$time_india_one')";
        mysql_query($sql);

        fwrite($fp, "\n[$time_india_one] Inside  failure block with error $errorDesc trigger $sql\n");
        fclose($fp);
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'wrong pin'));
        exit();
    }
}
