<?php
include("connect.php");
$MSISDN=$_GET['msisdn'];
$sql="SELECT `subscriptionResult` from `saudi_arabia_timwe_zain_pin_verify` where `msisdn`='".$MSISDN."' order by id desc";
$result=mysql_query($sql);
$row = mysql_fetch_array($result); $code=$row['subscriptionResult'];

if($code=="OPTIN_WAIT_FOR_ACTIVE_AND_CHARGING" ||  $code=="OPTIN_ALREADY_ACTIVE")
{
	echo "ACTIVE";
}
else
{
	echo "INACTIVE";
}	




?>
