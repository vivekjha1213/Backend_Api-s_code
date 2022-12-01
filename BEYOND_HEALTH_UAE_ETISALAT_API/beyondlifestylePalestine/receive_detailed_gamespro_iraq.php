<?php
include("Connection.php");
header('Content-Type:text/xls');
header('Content-Disposition:attachment;filename=Overall_bl_latvia.xls');
$from=$_POST['excelfrom'];
$to=$_POST['excelto'];
$sql="SELECT count(msisdn) as sub From PalestineGameCafeBase where DATE(indianDate)<='$to' and actionType='1'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$subBase=$row3['sub'];

$sql="SELECT count(msisdn) as sub From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$to' and actionType='1'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$sub=$row3['sub'];


$sql="SELECT count(msisdn) as unsub From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$to' and actionType='0'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$unsub=$row3['unsub'];

$sql="SELECT count(msisdn) as renewal From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$to' and actionType='2'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$renewal=$row3['renewal'];

$bill=($renewal*1.16)."shekels";
 
?>
<div class="container">
  <h3>Overall Summary</h3>
<table class="table table-striped container">    
    <thead>
    <tr>
        <th>BASE</th>
        <th>SUB</th>
        <th>UNSUB</th>
        <th>RENEWAL SUCCESS</th>
        <th>BILLING</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td><?php echo $subBase;?></td>
    <td><?php echo $sub;?></td>
    <td><?php echo $unsub;?></td>
    <td><?php echo $renewal;?></td>
    <td><?php echo $bill;?></td>
    </tr>
    </tbody>
</table>
</div>
<br>

<?php
$sql1="SELECT * From PalestineGameCafeCallBack where DATE(indianDate) between '$from' and '$to'";
$result = $mysqli->query($sql1);
?>
  <div class="container">
  <h2>OVERALL CALLBACK RECEIVED</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
    <th>CID</th>
    <th>PUBNAME</th>
    <th>MSISDN</th>
    <th>ACTION TYPE</th>
    <th>ACTION VALUE</th>
    <th>SERVICE ID</th>
    <th>DATE</th>
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
    <td><?php echo $row3['pubName']; ?></td>
    <td><?php echo $row3['msisdn'];?></td>
    <td><?php echo $row3['actionType']; ?></td>
    <td><?php echo $row3['actionValue']; ?></td>
    <td><?php echo $row3['serviceId'];?></td>
    <td><?php echo $row3['indianDate'];?></td>
    
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>






