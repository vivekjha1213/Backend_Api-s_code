<?php
$msisdn = $_GET['msisdn'];
$clickid = "";
start_first($msisdn, $clickid);

// for Encrypt Pass
function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('sha256', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}
// For pin_request  
function start_first($phone_no, $clickid)
{
    include 'connect.php';
    date_default_timezone_set('Asia/Kolkata');
    $time_india_one = date("Y-m-d H:i:s");
    date_default_timezone_set('Asia/Dubai');
    $serviceDate = date("Y-m-d H:i:s");
    $serviceId = "ARSH0100";
    $advName = "IN-HOUSE";
    $pubName = "Adbird";
    $serviceName = "BEYOND_LIFESTYLE";
    $country = "UAE";
    $ip = $_SERVER['REMOTE_ADDR'];
    $phone = $phone_no;
    $msisdn = $phone_no;
    $request = $request = rand(111, 99999);

    $fp = fopen('bls_pin_request_' . date("Y-m-d"), 'a');

    // //DND CHEKUP ADDED BY vivek  18/8/2022
    // $dndCheck = file_get_contents("http://df3tracker.com/dnd/check.php?msisdn=$msisdn&productName=BeyondLifestyle&country=Uae");
    // $dndCheck = json_decode($dndCheck, true);
    // $response = $dndCheck['response'];
    // if ($response == "FAIL") {
    //     $error = "MSISDN IS BLACKLISTED FOR THIS SERVICE";
    //     // echo json_encode(array('success' => $error));
    //     echo json_encode(array('response' => 'Fail', 'errorMessage' => $error));
    //     exit();
    // }
    //DND CHECKUP CLOSED
    $cid = $clickid;
    //fwrite($fp,"\n [$date_india] $request  MSISDN $phone and cid $cid \n");
    $key1 = "DHDUFYlinsGDDSSs";
    // $username1 = 'DigiFish';
    // $password1 = 'dbdAur87353nsjqa';
    $username1 = 'Arshiya';
    $password1 = 'o@jiGIm@0IIfA8N';
    $packageid1 = '1928';
    $username = aes128Encrypt($key1, $username1);
    $password = aes128Encrypt($key1, $password1);
    $mobile12 = aes128Encrypt($key1, $phone);
    $mobile = urlencode($mobile12);
    //for daily
    $packageid = aes128Encrypt($key1, $packageid1);
    fwrite($fp, "\n[$date_india] $request  MSISDN $phone \n");
    //$packageid=urlencode($packageid12);
    //$username=urlencode('Arshiya');
    $requestArray = array();
    $requestArray['user'] = $username;
    $requestArray['password'] = $password;
    $requestArray['msisdn'] = $mobile12;
    $requestArray['packageId'] = $packageid;
    //$TransactionID=rand(111111133,999999999999);
    $requestArray['txnid'] = $cid;
    $requestArray['channel'] = 'web';
    $requestArray['sourceIP'] = $ip;
    $requestArray['adPartnerName'] = '';
    $requestArray['pubId'] = '';

    $data_string = json_encode($requestArray);
    fwrite($fp, "\n[$date_india] $request  MSISDN $phone and  generated encoded string $data_string\n");

    //$api_url = 'http://pt5.etisalat.ae/Moneta/pushPIN.htm?usr=' . $username . '&pwd=' . $password . '&msisdn=' . $mobile . '&packageid=' . $packageid;
    $api_url = 'https://pt5.etisalat.ae/Moneta/pushPOSTPin.htm';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        )
    );
    $result = curl_exec($ch);
    curl_close($ch);
    $data = explode('|', $result);
    $response = $data[0];
    $token = $data[1];

    $sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$api_url','$response','$token','$serviceDate','$time_india_one')";
    mysql_query($sql_subscription);


    fwrite($fp, "\n[$date_india] $request  MSISDN $phone hitting Url $api_url with payload $data_string and received result $response and $token Query   $sql_subscription\n");
    fclose($fp);

    if ($response == "pin_sent") {
        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
    } else {
        echo json_encode(array('response' => 'Fail', 'errorMessage' => $response));
        exit();
    }
}
