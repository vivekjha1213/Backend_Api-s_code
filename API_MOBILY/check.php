<?php
include("connection.php");
$MSISDN=$_GET['msisdn'];
$sql="SELECT `subscriptionResult` from `saudi_arabia_timwe_pin_verify` where `msisdn`='".$MSISDN."'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$code=$row['subscriptionResult'];


//if($code=="")
//	echo "INACTIVE";

if($code=="OPTIN_ALREADY_ACTIVE" || $code=="OPTIN_ACTIVE_WAIT_CHARGING")
{
	echo "ACTIVE";
}
else
{
	echo "INACTIVE";
}
?>
