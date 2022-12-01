<?php
include 'connect.php';
$msisdn = $_GET['msisdn'];
$operator = "42402";
$language = "en";
date_default_timezone_set('Asia/Kolkata');
$time_india_one = date("Y-m-d H:i:s");
date_default_timezone_set('Asia/Dubai');
$serviceDate = date("Y-m-d H:i:s");
$msisdn = $_GET['msisdn'];
$serviceId = "ARSH0105";
$advName = "IN-HOUSE";
$pubName = "DREAM_MOBI";
$serviceName = "BEYOND_HEALTH";
$country = "UAE";


$request = $request = rand(111, 99999);
$fp = fopen('beyondh_pin_request_log' . date("Y-m-d"), 'a');
fwrite($fp, "\n[$date] $request received MSISDN $msisdn OPERATOR $operator  LANGUAGE $language  \n");


$PublicKey = "xOyCvw0OLxhz5UGo3t4B";
$PrivateKey = "EQHB82KCv4vX9nn5K7KR";
$CustomerAccountNumber = "01";
$SubscriptionPlanId = 1697;
$InitialPaymentProductId = "Beyond_health_uae";
//echo "CODE NOT PRESENT";
$dummy_date = date("Y-m-d H:i:s") . "Z";
$InitialPaymentDate = date('Y-m-d H:i:s', strtotime($dummy_date . ' + 1 days')) . "Z";
$ExecuteRecurringPaymentNow = "false";
$ExecuteInitialPaymentNow = "false";
fwrite($fp, "\n [$date] $request checking $sql and code $code InitialPaymentDate $InitialPaymentDate and ExecuteInitialPaymentNow $ExecuteRecurringPaymentNow and ExecuteRecurringPaymentNow $ExecuteRecurringPaymentNow \n");
$RecurringPaymentProductId = "Beyond_health_uae";
$ProductCatalogName = "Beyond_health_uae";
$OperatorCode = $operator;
$ContractStartDate = date("Y-m-d H:i:s") . "Z";
//$ContractEndDate = "2022-08-01 07:20:00Z";
$ContractEndDate = date('Y-m-d H:i:s', strtotime($ContractStartDate . ' + 3650 days')) . "Z";
$AutoRenewContract = var_export(true, true);

$SendVerificationSMS = var_export(true, true);
$allowMultipleFreeStartPeriods = var_export(true, true);
$HeaderEnrichmentReferenceCode = "";
$SmsId = "";
$Message = $CustomerAccountNumber . $msisdn . $OperatorCode . $SubscriptionPlanId . $InitialPaymentProductId . $InitialPaymentDate . $ExecuteInitialPaymentNow . $RecurringPaymentProductId . $ProductCatalogName . $ExecuteRecurringPaymentNow . $ContractStartDate . $ContractEndDate . $AutoRenewContract . $Language . $SendVerificationSMS . $allowMultipleFreeStartPeriods . $HeaderEnrichmentReferenceCode . $SmsId;
$Digest = $PublicKey . ":" . hash_hmac("sha256", $Message, $PrivateKey);
$sessionToken = $sessionToken;
//$api_old="http://staging.tpay.me/api/TPAYSubscription.svc/Json/AddSubscriptionContractRequest";
$api = "http://live.tpay.me/api/TPAYSubscription.svc/Json/AddSubscriptionContractRequest";
$raw_data = array(
        "signature" => $Digest,
        "customerAccountNumber" => $CustomerAccountNumber,
        "msisdn" => $msisdn,
        "operatorCode" => $OperatorCode,
        "subscriptionPlanId" => $SubscriptionPlanId,
        "initialPaymentproductId" => $InitialPaymentProductId,
        "initialPaymentDate" => $InitialPaymentDate,
        "executeInitialPaymentNow" => $ExecuteInitialPaymentNow,
        "executeRecurringPaymentNow" => $ExecuteRecurringPaymentNow,
        "recurringPaymentproductId" => $RecurringPaymentProductId,
        "productCatalogName" => $ProductCatalogName,
        "autoRenewContract" => $AutoRenewContract,
        "sendVerificationSMS" => $SendVerificationSMS,
        "allowMultipleFreeStartPeriods" => $allowMultipleFreeStartPeriods,
        "contractStartDate" => $ContractStartDate,
        "contractEndDate" => $ContractEndDate,
        "language" => $Language,
        "headerEnrichmentReferenceCode" => $HeaderEnrichmentReferenceCode,
        "smsId" => $SmsId,
        "sessionToken" => $sessionToken
);
$payload = json_encode($raw_data);
$ch = curl_init('http://live.tpay.me/api/TPAYSubscription.svc/Json/AddSubscriptionContractRequest');
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

// Close cURL session handle
curl_close($ch);
fwrite($fp, "\n [$date] $request send with payload $payload hitting URL $api  and received $result \n");

$final_response = $response[errorMessage]; // here blank $final_response means Success....
$contractID = $response[subscriptionContractId]; //in black response case contarct Id is genrated....
$operationStatusCode = $response[operationStatusCode];
$transactionId = $response[transactionId];


// $sql_subscription = "INSERT INTO  `tpay_bh_pin_request` (`cid`,`Operator`,`msisdn`,`response`,`contract_id`) VALUES('$clickid','$operator','$msisdn','$final_response','$contractID')";
// mysql_query($sql_subscription);




$sql_subscription = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$result','$operationStatusCode','$contractID','$serviceDate','$time_india_one')";
mysql_query($sql_subscription);

if ($operationStatusCode == "0") {


        echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
        exit();
} else {

        echo json_encode(array('response' => 'Fail', 'errorMessage' => $final_response));
        exit();
}
