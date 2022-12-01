<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<?php
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
?>
<?php
include("Connection.php");
$from=$_POST['excelfrom'];
$to=$_POST['excelto'];

$sql3="SELECT status,count(status) as counter From in_app_pin_verify where DATE(date) between '$from' and '$to' and serviceId='ARSH0042' group by status";
$result3 = $mysqli->query($sql3);
//exit();
?>
<div class="container" >
<table class="table table-striped">
    <thead>
    <tr>
  <th>STATUS</th>
  <th>COUNT</th>
    </tr>
    </thead>
    <tbody>
<?php
while($row3=$result3->fetch_array())
{ ?>
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['counter']; ?></td>
    </tbody>
<?php

} ?>
</table>
</div>
<br>
<?php
 $sql1="SELECT * From in_app_pin_verify where DATE(date) between '$from' and '$to' and serviceId='ARSH0042' order by date desc";
//exit();
//exit();
$result = $mysqli->query($sql1);
// SELECT distinct msisdn,status From mis_listener where time_india
// -> between '2020-04-01' and '2020-04-16'and country='th' group by msisdn;
//echo $result->num_rows;
//exit();
if ($result->num_rows > 0) {
 ?>
  <div class="container">
  <h2>Datewise Numbers Successfully Done.</h2>
<table class="table table-striped">
    <thead>
    <tr>
   <th>S.NO</th>
  <th>MSISDN</th>
  <th>serviceId</th>
  <th>advName</th>
  <th>pubName</th>
  <th>serviceName</th>
  <th>country</th>
  <th>otp</th>
  <th>result</th>
  <th>status</th>
  <th>finalStatus</th>
  <th>serviceDate</th>
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
    <td><?php echo $row3['msisdn']; ?></td>
    <td><?php echo $row3['serviceId']; ?></td>
    <td><?php echo $row3['advName']; ?></td>
    <td><?php echo $row3['pubName'];?></td>
    <td><?php echo $row3['serviceName'];?></td>
    <td><?php echo $row3['country'];?></td>
    <td><?php echo $row3['otp'];?></td>
    <td><?php echo $row3['result'];?></td>
    <td><?php echo $row3['status'];?></td>
    <td><?php echo $row3['serviceDate'];?></td>
    <td><?php echo $row3['date'];?></td>
    </tr>
    <?php }?>
   </tbody>

   </table>
   </div>


<?php }
else
{
    echo "0 results";
}


?>
