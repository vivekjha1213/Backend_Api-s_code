<?php
if ($service == "Altruist_UAE_Etisalat_info2cell") {
	include 'connect.php';
	$msisdn = $_GET['msisdn'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$device_id = rand(0, 99999999);
	$pin = $_GET['pin'];

	date_default_timezone_set('Asia/Kolkata');
	$time_india_one = date("Y-m-d H:i:s");
	date_default_timezone_set('Asia/Dubai');
	$serviceDate = date("Y-m-d H:i:s");
	$time_india = date('Y-m-d');
	$serviceId = "ARSH0099";
	$advName = "Info2cell";
	$pubName = "OLIMOB";
	$serviceName = "Altruist_UAE_Etisalat";
	$country = "UAE";


	$sql = "SELECT `status`,`finalStatus` from `in_app_pin_verify` WHERE `msisdn`='" . $msisdn . "' order by id desc limit 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$tid = $row['status'];
	$request_id = $row['finalStatus'];


	$fp = fopen('Altruist_UAE_Game2play_pin_verify_log' . date("Y-m-d"), 'a');
	fwrite($fp, "\n[$time_india]  received msisdn $msisdn device_id $device_id \n");
	$api = "https://api.games2play.co/api/subscription";
	$raw_data = array(
		"request" => "pin_verify",
		"alias" => "9779",
		"usr" => "digi_fish",
		"pass" => "6sDtJpwC4gQ",
		"package_id" => "1591",
		"msisdn" => "$msisdn",
		"ip" => "$ip",
		"device_id" => "$device_id",
		"pin" => "$pin",
		"request_id" => "$request_id",
		"source_id" => "2",
		"transaction_id" => "$tid"
	);


	$payload = json_encode($raw_data);
	$ch = curl_init('https://api.games2play.co/api/subscription');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

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

	//JUGAAD 
	$result = str_replace(':010,', ':"010",', $result);

	//var_dump($result);
	$check = json_decode($result, true);
	//$chk= json_decode('{"result":{"status":false,"status_code":"010","response":"Invalid PIN"}}', true);

	// var_dump( $chk);
	// die("end");
	$code = $check['result']['status'];
	$reason = $check['result']['response'];


	fwrite($fp, "\n[$time_india_one]  after hitting the URL $api with payload $payload and got the response $result \n");

	if ($code == "1") {


		$results = file_get_contents('http://beyondhealth.info/Services/Filter/chk.php?id=339&op=Altruist_UAE_Etisalat_olimob_Info2cell');
		if ($results == 0) //MEANS PASS
		{


			$panda = "PASSED";
			$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$tid','$serviceDate','$time_india_one')";
			mysql_query($sql);
			echo json_encode(array('response' => 'SUCCESS', 'errorMessage' => 'OK'));
			exit();
		}

		if ($results == 1) //MEANS BLOCK

		{
			$panda = "BLOCK";
			$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$tid','$serviceDate','$time_india_one')";
			mysql_query($sql);

			echo json_encode(array('response' => 'Fail', 'errorMessage' => 'Multi Hits on Msisdn'));
			exit();
		}
	} else {
	
	$sql = "INSERT INTO  `in_app_pin_verify` (`serviceId`,`advName`,`pubName`,`serviceName`,`country`,`msisdn`,`otp`,`result`,`status`,`finalStatus`,`serviceDate`,`date`)  VALUES('$serviceId','$advName','$pubName','$serviceName','$country','$msisdn','$pin','$result','$panda','$reason','$serviceDate','$time_india_one')";
		mysql_query($sql);
        echo json_encode(array('response' => 'Fail', 'errorMessage' => 'invalidpin'));
		exit();
	}
}
