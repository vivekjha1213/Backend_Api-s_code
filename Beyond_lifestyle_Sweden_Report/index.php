<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("Connection.php"); ?>
  <title></title>
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
            url         : 'receive_data.php',
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
  <H3>Beyond lifestyle Sweden  PORTAL </H3> 
  <form class="form-inline">
    <label for="from">From: </label>
    <input type="date" class="form-control" id="from" placeholder="Enter from" name="from" value="<?php echo $_GET['date']?>">
    <label for="to">To: </label>
    <input type="date" class="form-control" id="to" placeholder="Enter to" name="to" value="<?php echo $_GET['date']?>">
    <br><br>
    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
  </form>
  <div class="loader" ></div>
  <div id="table_xy">
  </div>
</div>
</body>
</html>
