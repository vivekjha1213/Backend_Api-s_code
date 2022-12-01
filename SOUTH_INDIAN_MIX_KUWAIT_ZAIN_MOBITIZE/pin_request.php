<?php
include("connect.php");
$msisdn = $_GET['msisdn'];
if ($msisdn == "") {
    echo json_encode(array('Status' => "Enter a valid Msisdn"));
    exit();
} else {
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Kuwait');
    $serviceDate = date("Y-m-d H:i:s");
    $serviceId = "ARSH0075";
    $advName = "IN-HOUSE";
    $pubName = "MOBITIZE";
    $serviceName = "SOUTH-INDIAN-MIX";
    $country = "KUWAIT";
    $msisdn = $_GET['msisdn'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $transID = rand(1000000, 9999999);
    $ssid = uniqid(rand(1000000000, 9999999999));

    $fp = fopen("S.I.M_pin_request" . $time_india_one, "a");

    /* FOR WEEKLY $pin_genrator="http://vascld-cgw.mcomviva.com:8093/API/CGRequest?MSISDN=$msisdn&productID=Zain-SouthIndia&pName=SouthIndia%20Zone&pPrice=600&pVal=7&CpId=ARSHD-01&CpPwd=ARSHD01&CpName=ARSHIYA-ZK&reqMode=APP&reqType=Subscription&ismID=17&transID=$transID&sRenewalPrice=0&sRenewalValidity=0&request_locale=en&srcIP=$ip&serviceType=default&planId=default&opId=101&contentId=default&optionalParameter1=$ssid&optionalParameter2=1002"; */
    //Daily
    //$pin_genrator="http://vascld-cgw.mcomviva.com:8093/API/CGRequest?MSISDN=$msisdn&productID=Zain-SouthIndia&pName=SouthIndia%20Zone&pPrice=100&pVal=1&CpId=ARSHD-01&CpPwd=ARSHD01&CpName=ARSHIYA-ZK&reqMode=APP&reqType=Subscription&ismID=17&transID=$transID&sRenewalPrice=0&sRenewalValidity=0&request_locale=en&srcIP=$ip&serviceType=default&planId=default&opId=101&contentId=default&optionalParameter1=$ssid&optionalParameter2=1002";
    //Weekly
    // $pin_genrator="http://vascld-cgw.mcomviva.com:8093/API/CGRequest?MSISDN=$msisdn&productID=Zain-SouthIndia&pName=SouthIndia%20Zone&pPrice=600&pVal=7&CpId=ARSHD-01&CpPwd=ARSHD01&CpName=ARSHIYA-ZK&reqMode=APP&reqType=Subscription&ismID=17&transID=$transID&sRenewalPrice=0&sRenewalValidity=0&request_locale=en&srcIP=$ip&serviceType=default&planId=default&opId=101&contentId=default&optionalParameter1=$ssid&optionalParameter2=1002";
    $pin_genrator = "http://vascld-cgw.mcomviva.com:8093/API/CGRequest?MSISDN=$msisdn&productID=Zain-SouthIndia&pName=SouthIndia%20Zone&pPrice=2000&pVal=30&CpId=ARSHD-01&CpPwd=ARSHD01&CpName=ARSHIYA-ZK&reqMode=APP&reqType=Subscription&ismID=17&transID=$transID&sRenewalPrice=0&sRenewalValidity=0&request_locale=en&srcIP=$ip&serviceType=default&planId=default&opId=101&contentId=default&optionalParameter1=$ssid&optionalParameter2=1002";
    $url = $pin_genrator;
    //echo $url;
    //exit();

    // pPrice=600&pVal=7 weekly
    // pPrice=2000&pVal=30 mothly
    // pPrice=100&pVal=1 daily
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

    // $sql = "INSERT INTO `southindianmix_pin_request` (`cid`,`msisdn`,`response`,`trans_id`,`date_india`,`date_kuwait`) VALUES('" . $cid . "','" . $msisdn . "','" . $errorDesc . "','" . $transID . "','" . $date_india . "','" . $date_kuwait . "')";
    // mysql_query($sql, $conn2);
    fwrite($fp, "\n[$time_india_one] Received $msisdn hit the api $pin_genrator and Received $result \n");

    $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$ssid','$transID','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);

    fwrite($fp, "\n[$time_india_one] Query $sql_subscription  \n");

    if ($error_code == 0) {
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    } else {
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn'));
        exit();
    }
}
