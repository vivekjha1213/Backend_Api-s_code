<head>
  <title> GAMECAFE PALESTINE PROMOTIONS</title>
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
          <form method="post" action="receive_detailed_bl_palestine.php" >
            <input type="hidden" name="excelfrom" value="<?php echo $from;?>" readonly/>
            <input type="hidden" name="excelto" value="<?php echo $modified_to;?>" readonly/>
            <input type="submit" name="export" class="btn btn-success" value="Downlaod Detailed Report" />
          </form>
        </div>
    </div>
</div>
<br>
<?php
include("Connection.php");
$sql="SELECT count(msisdn) as sub From PalestineGameCafeBase where DATE(indianDate)<='$modified_to' and actionType='1'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$subBase=$row3['sub'];

$sql="SELECT count(msisdn) as sub From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$modified_to' and actionType='1'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$sub=$row3['sub'];


$sql="SELECT count(msisdn) as unsub From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$modified_to' and actionType='0'";
$result3 = $mysqli->query($sql);
$row3=$result3->fetch_array();
$unsub=$row3['unsub'];

$sql="SELECT count(msisdn) as renewal From PalestineGameCafeCallBack  where DATE(indianDate) between '$from' and '$modified_to' and actionType='2'";
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
$sql1="SELECT * From PalestineGameCafeCallBack where DATE(indianDate) between '$from' and '$modified_to'";
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
  


