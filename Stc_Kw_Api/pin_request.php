<?php

// Added by Vivek.

    include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Kuwait');
    $serviceDate = date("Y-m-d H:i:s");
    $msisdn = $_GET['msisdn'];
    $serviceId = "ARSH0087";
    $advName = "Shemaroo";
    $pubName = "MOVIPLUS";
    $serviceName = "SHEEMARO_STC_KUWAIT";
    $country = "KUWAIT-STC";
    $tid = rand();

    $fp = fopen('kuwait_stc_shemaro_request_log' . date("Y-m-d"), 'a');
    fwrite($fp, "\n[$time_india_one]  received msisdn $msisdn \n");

//Post type Api 

    $api = "http://m.shemaroo.com/intl/FCCService/GenerateOTP";
    $raw_data = array(
        "msisdn" => "$msisdn",
        "PromoId" => 29339,
        "PartnerId" => 8,
        "TransactionId" => "$tid"
    );
    $payload = json_encode($raw_data);
    $ch = curl_init('http://m.shemaroo.com/intl/FCCService/GenerateOTP');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    // Set HTTP Header for POST request
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        )
    );

    // Submit the POST request
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    //print_r($result);
    // $tid=$response['TransactionId'];        
    $reason = $response['Message'];

    //   $sql="INSERT INTO  `Shemaroo_Kuwait_stc_pin_request` (`cid`,`msisdn`,`subscriptionResult`,`status`,`date`,`tid`) VALUES('".$clickid."','".$msisdn."','".$result."','".$reason."','".$time_india."','".$tid."')";
    //   mysql_query($sql);

    
    $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$reason','$tid','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);

    fwrite($fp, "\n[$time_india]  after hitting the URL $api with payload $payload and got the response $result \n");
    // die();


    if ($reason == "Success") {

        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    } else {

        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Invaild Msisdn'));
        exit();
    }

