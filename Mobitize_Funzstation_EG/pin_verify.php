<?php
// $service = file_get_contents('http://beyondhealth.info/Services/Switch/api.php?id=55');
// if ($service == "MOBITIZE_FUNZSATION_EG_INHOUSE") {
	include("connect.php");
	$pin = $_GET['pin'];
	$msisdn = $_GET['msisdn'];
	$cid = "ELIZABETH-PROMOTION";
	$sql = "SELECT `status`,`finalStatus` from `in_app_pin_request` where `msisdn`='" . $msisdn . "' order by id desc limit 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$operator = $row['status'];
	$contractID = $row['finalStatus'];
	$language = "en";
	function verify_otp($pin, $msisdn, $cid, $contractID, $operator, $language)
	{
		include 'connect.php';
		$PublicKey = "NvCTEq0n62WifzPDlgRF";
		$PrivateKey = "M6JEr79mKwRd2QWOKTqn";
		$SubscriptionContractId = $contractID;
		$PinCode = $pin;
		$msisdn = $msisdn;
		$clickid = $cid;
		$operator = $operator;
		$language = $language;
		date_default_timezone_set('Asia/Kolkata');
		$time_india_one = date("Y-m-d H:i:s");
		date_default_timezone_set('Africa/Cairo');
		$serviceDate = date("Y-m-d H:i:s");
		$serviceId = "ARSH0042";
		$advName = "INHOUSE";
		$pubName = "MOBITIZE ";
		$serviceName = "FUNZSTATION EGYPT";
		$country = "EGYPT";

		$fp = fopen('MOBITIZE_PIN_VERIFY' . date("Y-m-d"), 'a');
		fwrite($fp, "\n  received MSISDN $msisdn OPERATOR $operator CLICKID $clickid LANGUAGE $language  pin $pin  \n");

		$Message = $SubscriptionContractId . $PinCode;
		$Digest = $PublicKey . ":" . hash_hmac("sha256", $Message, $PrivateKey);
		$verify_data = array("signature" => $Digest, "pinCode" => $PinCode, "subscriptionContractId" => $SubscriptionContractId);
		$payload = json_encode($verify_data);
		//$api_staging="http://staging.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract";
		$api = "http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract";
		// Prepare new cURL resource
		$ch = curl_init('http://live.TPAY.me/api/TPAYSubscription.svc/Json/VerifySubscriptionContract');
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
		$final_response = $response[errorMessage];
		date_default_timezone_set('Asia/Kolkata');
		$time_india_one = date('Y-m-d H:i:s');
		fwrite($fp, "\n  After hiting url $api with payload $payload and received result $result \n");
		if ($final_response == 'null' || $final_response == "") {

			$ans = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=277&op=MOBITIZE_FUNZSATION_EG_INHOUSE');
			if ($ans == 0) {
				$status = "PASSED";
				$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$status','$contractID','$serviceDate','$time_india_one')";
				mysql_query($sql);

				echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'Pin verified successfully'));
				exit();
			}
			if ($ans == 1) {
				$status = "BLOCK";

				$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$status','$contractID','$serviceDate','$time_india_one')";
				mysql_query($sql);


				echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multiple Hits on msisdn'));
				exit();
			}
		} else {
			$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$final_response','$contractID','$serviceDate','$time_india_one')";
			mysql_query($sql);
			echo json_encode(array('response' => 'Fail', 'errorMessage' => 'wrong pin'));
		}
	}
	verify_otp($pin, $msisdn, $cid, $contractID, $operator, $language);
// }
?>