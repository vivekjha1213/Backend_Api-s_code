<!DOCTYPE html>
<html lang="en">
<head>

  <?php include("Connection.php"); ?>
  <title>BEYONDLYFESTYLE_ALT_UAE_REPORT </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script>
$(document).ready(function(){
    $("#submitBtn").click(function(){
        $("#myForm").submit();
         var from = $("#from").val();
         var to= $("#to").val();
         $.ajax({
            type        : 'POST',
            url         : 'receive_mazaa_tv.php',
            data        : {from:from,to:to},
            dataType    : 'text',

             beforesend:function()
                                  {
                                      $('.loader').show();
                                  },
             success: function(data){
                                     $('#today_report').hide();
                                     $('#table_xy').html(data);
                                     $('.loader').hide()
                                },
              error: function(){
                                    alert('failure');
                                }
                }) ;
    });
});
</script>
</head>
<body>
<div class="container">
  <h2>REPORT(DREAM_MOBI_FUNZSATION_UAE conversion tracker)</h2>
  <p>DREAM_MOBI_FUNZSATION_UAE</p>
   <div class="text-right">

        <a href="http://beyondhealth.info/INAPP" class="btn btn-info btn-lg">
          <span class="glyphicon glyphicon-home"></span> Home
        </a>
      </div>
      <div class="black_box">
<a class="gold_text" href="http://beyondhealth.info/INAPP_API_REPORT/DREAM_MOBI_FUNZSATION_UAE?date=<?=date("Y-m-d")?>">Refresh</a>
</div>

<?php
date_default_timezone_set("Asia/Calcutta");
$pre=date('Y-m-d');
$today_date=$_GET['date'];
// echo $today_date;
if ($today_date<$pre) {
  ?>
  <form class="form-inline" action="index.php">
    <label for="from">From: </label>
    <input type="date" class="form-control" id="from" placeholder="Enter from" name="from" value="<?php echo $_GET['date']?>">
    <label for="to">To: </label>
    <input type="date" class="form-control" id="to" placeholder="Enter to" name="to" value="<?php echo $_GET['date']?>">
    <br><br>
    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
  </form>
</div>



<br>
<div class="container"><a class="btnk" style="float:right;" href="index.php?date=<?= date('Y-m-d', strtotime('+1 day', strtotime($today_date))) ?>" class="next_day" title="Next Day" ><span>NEXT</span></a></div>
  <?php
}
?>
<div class="container"><a class="btnk" href="index.php?date=<?= date('Y-m-d', strtotime('-1 day', strtotime($today_date))) ?>" class="prev_day" title="Previous Day" ><span>PRE</span></a> </div>


<br>
<div id="today_report">
<?php   
$sql3="SELECT status,count(status) as counter From in_app_pin_verify where DATE(date) between '$today_date' and '$today_date' and serviceId='ARSHOO43' group by status";
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
    <td><?php echo $row3['counter']; ?></td>
    </tr>
  </tbody>
<?php
} ?>
    <?php
    
    $sql="SELECT  count(distinct msisdn) as counter  FROM `in_app_pin_verify`  where DATE(date) between '$today_date' and '$today_date' and serviceId='ARSHOO43'";
    $sql2="SELECT  count(distinct msisdn) as counter  FROM in_app_pin_verify  where `status` IN ('PASSED', 'BLOCK') AND DATE(date) between '$today_date' and '$today_date' and serviceId='ARSHOO43'";
    $result3 = $mysqli->query($sql);
    $row3=$result3->fetch_array();
    $x=$row3['counter'];
    $result4 = $mysqli->query($sql2);
    $row4=$result4->fetch_array();
    $y=$row4['counter'];
    $z=($y*100)/$x;
    ?><tr>
    <td style="color: black;">CR</td>
    <td><?php echo intval($z);?></td>
    </tr>
</table>
</div>
<br>
<?php
$sql1="SELECT * From in_app_pin_verify where date(date) between '$today_date' and '$today_date' and serviceId='ARSHOO43' order by id desc";
//exit();
//exit();
$result = $mysqli->query($sql1);
if ($result->num_rows > 0) {
 ?>
  <div class="container">
  <h2>Datewise Numbers Successfully Done.</h2>
<table class="table table-striped">
    <thead>
    <tr>
    <th>S.NO</th>
  <th>MSISDN</th>
  <th>Service_ID</th>
  <th>advName</th>
  <th>pubName</th>
  <th>serviceName</th>
  <th>country</th>
  <th>otp</th>
  <th>result</th>
  <th>status</th>
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
</div>
<div id="table_xy">
 </div>
</body>
</html>
<style>


a {
  font-family: "Indie Flower", cursive;

  font-weight: normal;
  text-align: center;
}

.black_box {
  background-color: #1e1f26;
  max-width: 87px;
  padding: 1rem;
  border-radius: 100px;
  /*position: fixed;*/
  left: 0;
  right: 0;
  box-shadow: 0px 0px 0px 10px #e8b447;
  /*margin: 0 auto;*/
  cursor: pointer;
  top: 50%;
  transition: all 0.3s;
  transform: translatey(-50%);
}
.black_box:hover,.black_box a:hover {
  box-shadow: 0px 0px 0px #e8b447;
  color: white;
}

.gold_text {
  color: #e8b447;
  margin: 0;
}

.btnk {
  min-width: 160px;
  width: fit-content;
  width: -moz-fit-content;
  background-color: white;
  padding: 0 30px;
  height: 50px;
  font-family: "Open Sans", sans-serif;
  text-transform: uppercase;
  font-size: 14px;
  color: #ffc964;
  letter-spacing: 2.8px;
  font-weight: 700;
  line-height: 1.6;
  box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.3);
  position: relative;
  transition: all 0.4s ease;
  cursor: pointer;
  display: -webkit-flex;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  border: 6px solid;
  / btnk text /
}
.btnk span {
  z-index: 1;
  text-align: center;
}
.btnk:before, .btnk:after {
  content: "";
  position: absolute;
  width: 4px;
  height: 100%;
  top: 0;
  transition: all 0.4s ease;
  background-color: #ffc964;
}
.btnk:before {
  left: 0;
}
.btnk:after {
  right: 0;
}
.btnk:hover {
  transition: all 0.4s ease;
  box-shadow: 0 8px 20px -12px rgba(0, 0, 0, 0.2);
  letter-spacing: 2px;
  color: white;
}
.btnk:hover:before, .btnk:hover:after {
  width: 51%;
}

</style>