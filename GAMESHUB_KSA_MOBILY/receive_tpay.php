<head>
  <title>DIGIFISH3 GAMEHUB KSA MOBILY</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
</head>
<?php
include("Connection.php");
//print_r($_POST);
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
  $modified_to=$_POST['excelto'];;
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
          <form method="post" action="receive_detailed_tpay.php" >
            <input type="hidden" name="excelfrom" value="<?php echo $from;?>" readonly/>
            <input type="hidden" name="excelto" value="<?php echo $modified_to;?>" readonly/>
            <input type="submit" name="export" class="btn btn-success" value="Downlaod Detailed Report" />
          </form>
        </div>
    </div>      
</div>

<!--FORM CENTER CLOSE---->
<!--FORM CENTER CLOSE---->
<?php
$sql3="SELECT  count(msisdn) from  gamecafe_mobily_ksa_optin where status='Subscribe'  AND DATE(time_india) between '$from' and '$to' ";
$result3 = $mysqli->query($sql3);
$row3=$result3->fetch_array();
?>
<div class="container" >
  <h2>OVERALL REPORT OF SUBSCRIBERS/UNSUBSCRIBERS</h2>
<table class="table table-striped">
    <thead>  
    <tr>
  
<!--  <th>CLICK TIME</th> -->
  <th>status</th>
  <th>count</th>
    </tr>
    </thead>
    <tbody>
    
    <?php
    while($row3=$result3->fetch_array())
    { ?>
    <tr>
    <td><?php echo $row3['req_type']; ?></td>
    <td><?php echo $row3['count(msisdn)']; ?></td>
    </tr>
    <?php }?>


<?php
$sql3="SELECT  count(msisdn) from  gamecafe_mobily_ksa_renew where status='REN'  AND DATE(time_india) between '$from' and '$to' ";
$result3 = $mysqli->query($sql3);
$row3=$result3->fetch_array();

    ?><tr>
    <td style="color: black;">REN CHARGE</td>
    <td><?php echo  $row3['ren'];?></td>
    </tr>
   
<!-- TOTAL REV -->
      <tr>
    <td style="color: black;">TOTAL REV</td>
    <td><?php echo  ($row3['ren']+$row3['first_charge'])*31.8 ."KSH";?></td>
    </tr>

   </tbody>
   
   </table>
   </div>
   <br>
<br>
<?php
$sql3="SELECT * from gamecafe_mobily_ksa_optout where status='unSubscribe' DATE(time_india) between '$from' and '$to'";

// echo $sql3;
// die();

$result3 = $mysqli->query($sql3);
?>
<div class="container" >
  <h2>OVERALL REPORT OF FIRST CHARGE</h2>
<table class="table table-striped">
    <thead>  
    <tr>
    
<!--  <th>CLICK TIME</th> -->
  <!-- <th>clickId</th> -->
  <th>msisdn</th>
  <th>sms_id</th>
  <th>status</th>
  <th>reason</th>
  <th>product_id</th>
  <th>date_india</th>
    </tr>
    </thead>
    <tbody>
    
    <?php
    while($row3=$result3->fetch_array())
    { ?>
    <tr>
    <td><?php echo $row3['msisdn']; ?></td>
    <td><?php echo $row3['sms_id']; ?></td>
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['reason']; ?></td>
    <td><?php echo $row3['product_id']; ?></td>
    <td><?php echo $row3['date_india']; ?></td>
      </tr>
    <?php }?>
   </tbody>
   
   </table>
   </div>
   <br>
   <br>
   

    </tbody>
  </table>

</div>


<br>
<br>
<?php
$sql3="SELECT * from gamecafe_mobily_ksa_base where DATE(time_india) between '$from' and '$to'";

// echo $sql3;
// die();

$result3 = $mysqli->query($sql3);
?>
<div class="container" >
  <h2>OVERALL REPORT OF RENEWAL NOTIFICATION</h2>
<table class="table table-striped">
    <thead>  
    <tr>
    
<!--  <th>CLICK TIME</th> -->
  <!-- <th>clickId</th> -->
  <th>msisdn</th>
  <th>sms_id</th>
  <th>status</th>
  <th>reason</th>
  <th>product_id</th>
  <th>date_india</th>
    </tr>
    </thead>
    <tbody>
    
    <?php
    while($row3=$result3->fetch_array())
    { ?>
    <tr>
    <td><?php echo $row3['msisdn']; ?></td>
    <td><?php echo $row3['sms_id']; ?></td>
    <td><?php echo $row3['status']; ?></td>
    <td><?php echo $row3['reason']; ?></td>
    <td><?php echo $row3['product_id']; ?></td>
    <td><?php echo $row3['date_india']; ?></td>
      </tr>
    <?php }?>
   </tbody>
   
   </table>
   </div>
   <br>
   <br>
   

    </tbody>
  </table>

</div>


     
