<?php
include("Connection.php");
header('Content-Type:text/xls');
header('Content-Disposition:attachment;filename=Overall_bl_latvia.xls');
$from=$_POST['excelfrom'];
$to=$_POST['excelto'];

$sql="SELECT count(distinct msisdn) as sub From netsmart_BL_lat_pin_verify where DATE(date) between '$from' and '$to' and status='0'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$sub1=$row3['sub'];
//LATVIA API PROMOTION
$sql="SELECT count(distinct msisdn) as sub From in_app_pin_verify  where DATE(serviceDate) between '$from' and '$to' and serviceId IN ('ARSH0065','ARSH0066') and status in ('PASSED','BLOCK')";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$sub2=$row3['sub'];
$sub=$sub1+$sub2;
$sql="SELECT count(distinct msisdn) as unsub From netsmart_beyondhealth_unsubscribe_greece where DATE(time) between '$from' and '$to' and body='STOP BL'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$unsub=$row3['unsub'];
$sql="SELECT sum(tariff) as bill,count(msisdn) as renewal FROM netsmart_beyondhealth_billing_greece WHERE DATE(ts) between '$from' and '$to' and account='5' and status='1' and tariff!='0'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$bill=$row3['bill'];
$renewal=$row3['renewal']; 
?>
<div class="container">
  <h3>Overall Summary</h3>
<table class="table table-striped container">    
    <thead>
    <tr>
        <th>TOTAL SUBSCRIBER</th>
        <th>TOTAL UNSUB</th>
        <th>RENEWAL</th>
        <th>TOTAL BILLING</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td><?php echo $sub;?></td>
    <td><?php echo $unsub;?></td>
    <td><?php echo $renewal;?></td>
    <td><?php echo ($bill/100)." EURO";?></td>
    </tr>
    </tbody>
</table>
</div>
<br>

<?php
$sql1="SELECT * From netsmart_BL_lat_pin_verify where DATE(date) between '$from' and '$to' and status='0'";
$result = $mysqli->query($sql1);
?>
  <div class="container">
  <h2>LISTING OF SUBSCRIBERS FROM LP</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
    <th>CID</th>
    <th>MSISDN</th>
    <th>OTP</th>
    <th>STATUS</th>
    <th>carrier</th>
    <th>date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sno=0;
    while($row3=$result->fetch_array())
    { $sno++;
      ?>
    <tr>
    <td><?php echo $sno; ?></td>
    <td><?php echo $row3['cid']; ?></td>
    <td><?php echo $row3['msisdn'];?></td>
    <td><?php echo $row3['otp']; ?></td>
    <td><?php echo $row3['status'];?></td>
    <td><?php echo $row3['carrier'];?></td>
    <td><?php echo $row3['date'];?></td>
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>
  <!-- Listing of subcribers through INAPP-API Promotion--------------------->
  <?php
$sql1="SELECT * From in_app_pin_verify where DATE(serviceDate) between '$from' and '$to' and serviceId IN ('ARSH0065','ARSH0066') and status in ('PASSED','BLOCK')";
$result = $mysqli->query($sql1);
?>
  <div class="container">
  <h2>LISTING OF SUBSCRIBERS FROM INAPP</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
    <th>CID</th>
    <th>MSISDN</th>
    <th>OTP</th>
    <th>STATUS</th>
    <th>serviceDate</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sno=0;
    while($row3=$result->fetch_array())
    { $sno++;
      ?>
    <tr>
    <td><?php echo $sno; ?></td>
    <td><?php echo $row3['cid']; ?></td>
    <td><?php echo $row3['msisdn'];?></td>
    <td><?php echo $row3['otp']; ?></td>
    <td><?php echo $row3['result'];?></td>
    <td><?php echo $row3['serviceDate'];?></td>
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>
     <?php
$sql1="SELECT * From  netsmart_beyondhealth_unsubscribe_greece where DATE(time) between '$from' and '$to' and body='STOP BL'";
$result = $mysqli->query($sql1);
?>
  <div class="container">
  <h2>LISTING OF UNSUBSCRIBE MSISDN </h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
    <!-- <th>CID</th> -->
    <th>MSISDN</th>
    <th>CARRIER</th>
    <th>BODY</th>
    <th>date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sno=0;
    while($row3=$result->fetch_array())
    { $sno++;
      ?>
    <tr>
    <td><?php echo $sno; ?></td>
    <td><?php echo $row3['MSISDN'];?></td>
    <td><?php echo $row3['carrier']; ?></td>
    <td><?php echo $row3['body'];?></td>
    <td><?php echo $row3['date'];?></td>
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>
   <!----CLOSED-------------------------------------------------------------------------------------------------------------->

<?php
$sql1="SELECT * From netsmart_beyondhealth_billing_greece where DATE(ts) between '$from' and '$to' and account='5'";
$result = $mysqli->query($sql1);
?>
  <div class="container">
  <h2>LISTING OF BILLING</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
    <th>result</th>
    <th>status</th>
    <th>error</th>
    <th>msisdn</th>
    <th>mtid</th>
    <th>tariff</th>
    <th>carrier</th>
    <th>shortcode</th>
    <th>account</th>
    <th>date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sno=0;
    while($row3=$result->fetch_array())
    { $sno++;
      ?>
    <tr>
    <td><?php echo $sno; ?></td>
    <td><?php echo $row3['result']; ?></td>
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['error'];?></td>
    <td><?php echo $row3['msisdn']; ?></td>
    <td><?php echo $row3['mtid'];?></td>
    <td><?php echo $row3['tariff'];?></td>
    <td><?php echo $row3['carrier'];?></td>
    <td><?php echo $row3['shortcode'];?></td>
    <td><?php echo $row3['account'];?></td>
    <td>
      <?php 
          $date=explode(" ",$row3['ts']);
          echo $date[0];
      ?>
    </td>
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>







