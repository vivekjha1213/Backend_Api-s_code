<?php

// $service=file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=58');
// if($service=="MOBITIZE_FUNZSATION_EG_INHOUSE")
// {
include 'connect.php';
$msisdn = $_GET[msisdn];
$operator = $_GET[operator];
$clickid = "N.A";
$language = "en";
//$sessionToken=$_POST[sessionToken];
//start_first($msisdn,$operator,$clickid,$language,$sessionToken);
function start_first($phone_no, $operator, $clickid, $language)
{
	include 'connect.php';
	date_default_timezone_set('Asia/Kolkata');
	$time_india_one = date("Y-m-d H:i:s");
	$time_india = date('Y-m-d');
	date_default_timezone_set('Africa/Cairo');
	$serviceDate = date("Y-m-d H:i:s");
	$serviceId = "ARSH0042";
	$advName = "INHOUSE";
	$pubName = "MOBITIZE"; 
	$serviceName = "FUNZSTATION EGYPT";
	$country = "EGYPT";
	// $TransactionId=rand();
	$date = date("Y-m-d h:i:s");
	$request = $request = rand(111, 99999);
	$fp = fopen('MOBITIZE_PIN_REQUEST' . date("Y-m-d"), 'a');
	fwrite($fp, "\n[$date] $request received MSISDN $phone_no OPERATOR $operator CLICKID $clickid LANGUAGE $language  SESSION-TOKEN $sessionToken  \n");
	//CLOSE LOGS
	$PublicKey = "NvCTEq0n62WifzPDlgRF";
	$PrivateKey = "M6JEr79mKwRd2QWOKTqn";
	$CustomerAccountNumber = "01";
	$MSISDN = $phone_no;
	$operator = $operator;
	$clickid = $clickid;
	$language = $language;
	$SubscriptionPlanId = 1827; //daily 1333 Weekly 1827
	$InitialPaymentProductId = "funzstation_egy";
	$InitialPaymentDate = date("Y-m-d H:i:s") . "Z";
	$ExecuteInitialPaymentNow = var_export(false, true);
	$RecurringPaymentProductId = "funzstation_egy";
	if ($operator == "60201") {
		//$ProductCatalogName ="funzstation_orange_egy";
		$ProductCatalogName = "funzstation_orange_egy_weekly";
		$OperatorCode = "60201";
	}
	if ($operator == "60202") {
		//$ProductCatalogName ="funzstation_vodafone_egy";
		$ProductCatalogName = "funzstation_vodafone_egy_weekly";
		$OperatorCode = "60202";
	}
	if ($operator == "60203") {
		//$ProductCatalogName ="funzstation_etisalat_egy";
		$ProductCatalogName = "funzstation_etisalat_egy_weekly";
		$OperatorCode = "60203";
	}
	if ($operator == "60204") {
		//$ProductCatalogName ="funzstation_we_egy";
		$ProductCatalogName = "funzstation_we_egy_weekly";
		$OperatorCode = "60204";
	}
	$ExecuteRecurringPaymentNow = var_export(false, true);
	$ContractStartDate = date("Y-m-d H:i:s") . "Z";
	$ContractEndDate = "2022-08-01 07:20:00Z";
	$AutoRenewContract = var_export(true, true);
	if ($language == "en") {
		$Language = 1;
	}
	if ($language == "ar") {
		$Language = 2;
	}
	$SendVerificationSMS = var_export(true, true);
	$allowMultipleFreeStartPeriods = var_export(true, true);
	$HeaderEnrichmentReferenceCode = "";
	$SmsId = "";
	$Message = $CustomerAccountNumber . $MSISDN . $OperatorCode . $SubscriptionPlanId . $InitialPaymentProductId . $InitialPaymentDate . $ExecuteInitialPaymentNow . $RecurringPaymentProductId . $ProductCatalogName . $ExecuteRecurringPaymentNow . $ContractStartDate . $ContractEndDate . $AutoRenewContract . $Language . $SendVerificationSMS . $allowMultipleFreeStartPeriods . $HeaderEnrichmentReferenceCode . $SmsId;
	$Digest = $PublicKey . ":" . hash_hmac("sha256", $Message, $PrivateKey);
	$sessionToken = "";
	//$api_old="http://staging.tpay.me/api/TPAYSubscription.svc/Json/AddSubscriptionContractRequest";
	$api = "http://live.tpay.me/api/TPAYSubscription.svc/Json/AddSubscriptionContractRequest";
	$raw_data = array(
		"signature" => $Digest,
		"customerAccountNumber" => $CustomerAccountNumber,
		"msisdn" => $MSISDN,
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

	// $sql="INSERT INTO  `tpay_funzstation_pin_request` (`cid`,`Operator`,`msisdn`,`response`,`contract_id`) VALUES ('".$clickid."','".$operator."','".$MSISDN."','".$final_response."','".$contractID."')";
	// mysql_query($sql);
	$sql = "INSERT INTO `in_app_pin_request` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$MSISDN','$result','$operator','$contractID','$serviceDate','$time_india_one')";

	mysql_query($sql);

	fwrite($fp, "\n[$date] $request final response is $final_response and contractID $contractID \n");
	fclose($fp);
	if ($final_response == "null" || $final_response == "") {
		echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
		exit();
	} else {
		// echo json_encode(array('success' => "Try again"));
		echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong msisdn or operator'));
	}
}
start_first($msisdn, $operator, $clickid, $language);



							// }
