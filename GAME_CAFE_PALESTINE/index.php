<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("Connection.php"); ?>
  <title>Beyond Lifestyle -Latvia  PROMOTIONS</title>
  <meta charset="utf-8">
  <style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
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
            $('#newgen').append('<div class="spinner-border"></div>');
         var from = $("#from").val();
         var to= $("#to").val();
         $.ajax({
            type        : 'POST',
            url         : 'receive_bl_latvia.php',
            data        : {from:from,to:to},
            dataType    : 'text',

             beforeSend:function()
                                  {
                                      $('.loader').show();
                                  },
             success: function(data){
              $('#newgen').hide();
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
  <h2>REPORT(Beyond Lifestyle -Latvia conversion tracker)</h2>
  <p>Promotions Details of Beyond Lifestyle -Latvia.</p>
   <div class="text-right">

        <a href="http://beyondhealth.info/Report/home_reports.php" class="btn btn-info btn-lg">
          <span class="glyphicon glyphicon-home"></span> Home
        </a>
      </div>
  <div class="form-inline">
    <label for="from">From: </label>
    <input type="date" class="form-control" id="from" placeholder="Enter from" name="from">
    <label for="to">To: </label>
    <input type="date" class="form-control" id="to" placeholder="Enter to" name="to">
    <br><br>
    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
  </div>
</div>

<br>
<br>
<!-- <div class="getter">
</div> -->
<div id="newgen" align="center"></div>

<div id="table_xy">
 </div>
</body>
</html>
