<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
</head>
<!--FORM CENTER CLOSE---->
<?php
 header('Content-Type: applicaton/xls');
 header('Content-Disposition: attachment; filename=download.xls');
?>
<?php
include("Connection.php");

if(!isset($_POST['from']))
{
  $from=$_POST['excelfrom'];
 

}
else
{
  $from=$_POST['from'];

}
if(!isset($_POST['to']))

{

  $modified_to=$_POST['excelto'];

}
else
{ $to=$_POST['to'];
 $modified_to=$to." "."23:59:59";

 
}
?>
<!---FOR FORM CENTER @rajendra.singh.bisht----------->
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-10 col-md-8 col-lg-6">
          <!---FOR FORM CENTER----------->
          <form method="post" action="receive_data.php" >
            <input type="hidden" name="excelfrom" value="<?php echo $from;?>" readonly/>
            <input type="hidden" name="excelto" value="<?php echo $modified_to;?>" readonly/>
            <input type="submit" name="export" class="btn btn-success" value="Downlaod Detailed Report" />
          </form>
        </div>
    </div>
</div>

<br>
<?php
$sql3="SELECT status,count(status) as counter From netsmartSwedenDlr where DATE(date) between '$today_date' and '$today_date'";
$result3 = $mysqli->query($sql3);
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
    <tr>
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['msisdn']; ?></td>
    </tr>
  </tbody>
<?php
} ?>
<?php
$sql1="SELECT * From netsmartSwedenDlr where date(date) between '$from' and '$modified_to' order by id desc";
$result = $mysqli->query($sql1);


if ($result->num_rows > 0) {
 ?>
  <div class="container">
  <h2>Blocked Numbers List tried by PUB.</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
  <th>status</th>
  <th>error</th>
  <th>cid</th>
  <th>msisdn</th>
  <th>ts</th>
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
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['error']; ?></td>
    <td><?php echo $row3['cid']; ?></td>
    <td><?php echo $row3['msisdn']; ?></td>
    <td><?php echo $row3['ts'];?></td>
    <td><?php echo $row3['mtid'];?></td>
    <td><?php echo $row3['partnerID'];?></td>
    <td><?php echo $row3['tariff'];?></td>
    <td><?php echo $row3['carrier'];?></td>
    <td><?php echo $row3['shortcode'];?></td>
    <td><?php echo $row3['account'];?></td>
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

