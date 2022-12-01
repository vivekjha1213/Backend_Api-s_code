<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <title>Home | Beyond Health</title>  
    <?php include("header-js.php");?>
  </head>
<body>

<!--SCRIPT ADDED BY RAJENDRA-->
<script type="text/javascript">
console.log("start ajax");
$(document).ready(function(){    

    $("#submityes").click(function() {
        $('#newgen').append('<div class="spinner-border"></div>');
        var msisdn=$('#msisdn').val();
        var cid=$('#cid').val();
		    var oprator=$('#oprator').val();
        var pin=$('#pin').val();
        console.log(msisdn);
        // console.log(cid);
        console.log(oprator);
        //return false;
          if(oprator=="Mobily"){
          oprator=="Mobily"
          var url_page="pin_verify.php"
        }
        else if(oprator=="STC"){
          oprator=="STC"
          var url_page="stc_pin_verify.php"
        }
        else{
          oprator=="Zain"
            var url_page="zain_pin_verify.php"
        }
    //opraort close
        console.log(url_page);
        // return false;
        
            $.ajax({
                type:"POST",
                url:url_page,
                data:{msisdn:msisdn,cid:cid,pin:pin},
                success:function(result)
                {
                       $('#newgen').hide();
            				   var jsonData = JSON.parse(result);
                              	//var jsonData=JSON.stringify(result);
            					//alert(jsonData);
            					//return false;
                    if (jsonData.status == "0")
                    {   
                        location.href = 'thank-you.php?msisdn='+msisdn+'&cid='+cid+'';
                        return false;
                    }
					         else
          					{
          						  alert(jsonData.errorMessage);
                        location.reload();

          					}
        
				}
			});
	});
});
</script>
<!--SCRIPT CLOSED BY RAJENDRA-->

<div class="main-con main-box">
   <div class="container">
      <div class="fun-inner">
        <!--<div class="head-box">
           <p>AUTHORIZATION SERVICE BEFORE HEALTH: Service charge 6.20 € / Week (You will receive at least 11SMS / month on mobile), Delete: send STOP BEYOND TO 54422, Telebox: Tach. Box 24547, 1301, Cyprus. Support: 2155002934.</p>
        </div>-->

        <div class="main-box main-inner">
          <div class="fig-box">
            <div class="logo">
              <a href="#">
                <img class="img-fluid" src="images/logo.png">
                <span>Videos and Articles</span>
              </a>
	            <div class="upper-list">
	            	<!--<div class="language">
	            		<a href="otp_page_en.php?msisdn=$_GET['msisdn']&cid=$_GET['cid']" class="btn btn-1">
			              <svg><rect x="0" y="0" fill="none" width="100%" height="100%" rx="15"></rect></svg>Switch to English
			            </a>
	            	</div>-->
	            </div> 
            </div>
            <video onended="videoEnded(this)" id="v1" preload="auto" autoplay loop="loop" muted="muted" style="">
		            <source src="images/video.mp4" title="Instatalk" type="video/mp4">
		        </video>
		    </div>
          </div>


          <div class="doorgaan">
            <!--<form method="get" class="">-->
            	<label>3 أيام مجانية بعد ذلك 1 ريال / اليوم</label>
            	<input type="hidden" name="cid" id="cid" value="<?php echo $_GET['cid'];?>">
              <input type="hidden" name="oprator" id="oprator" value="<?php echo $_GET['oprator'];?>">

              <input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_GET['msisdn'];?>">
				      <input type="text" name="pin" id="pin"class="form-input" placeholder="أدخل OTP المستلم" >

				<!--<div class="chiller_cb tick-box">
					<label><input value="I have been informed of the terms of use and I accept to activate the BEYOND HEALTH subscription service. 6.20 € / Week" type="checkbox" name="Checkbox"><i>(Tick the box)</i></label>
				</div>
				<div class="chiller_cb">
				  <label> I have been informed of the terms of use and I accept to activate the BEYOND HEALTH subscription service. 6.20 € / Week</label>
				</div>-->
            	
            
            	<!--<input class="btn-door" type="button" name="subscribe" id="submityes" value="الإشتراك">-->
            	<button type="button" name="submit" id="submityes" class="btn-door">إرسال</button>
            	<br>
            	<div id="newgen"></div>
          <!--</form>-->
          </div>
 <div class="condition-box">
          <h4><b>Terms and conditions Mobily</b></h4>  

            <ul>
        <li><p> <span dir="rtl">من خلال الاشتراك في الخدمة ، فإنك تقبل جميع شروط وأحكام الخدمة وتفوض مشاركة رقم هاتفك المحمول مع شريكنا ArshiyaInfosolutions ، الذي يدير خدمة الاشتراك هذه. </span></p></li>
        <li><p> <span dir="rtl">يتم تطبيق رسوم البيانات على تصفح المحتويات على هذه البوابة. </span></p></li>
        <li><p><span dir="rtl"> الخدمة مدعومة فقط للهواتف الذكية إذا كان جهازك يدعم البث ، فيمكنك بث مقاطع فيديو غير محدودة أثناء كونك مشتركًا نشطًا في الخدمة. </span></p></li>
        <li><p><span dir="rtl"> للاستفادة من هذه الخدمة ، يجب أن يكون عمر الشخص أكثر من 18 عامًا أو حصل على إذن من والديك أو الشخص المخول بدفع فاتورة هاتفك المحمول. </span></p></li>
        <li><p><span dir="rtl">لإلغاء الاشتراك في الخدمة أرسل U61 إلى 606068</span></p></li>
      </ul>
          </div>



               <div class="condition-box ">
          <h4><b>Terms and conditions STC</b></h4>  
          <ul>
        <li><p> <span dir="rtl">هذه الخدمة متاحة لعملاء شركة الاتصالات السعودية مقابل 1 ريال لعملاء الدفع المسبق ، تجدد يوميا ، و 34.5 ريال لعملاء الدفع الآجل متجددة شهريا (شاملة ضريبة القيمة المضافة). </span></p></li>
        <li><p> <span dir="rtl">لإلغاء الاشتراك ، برجاء إرسال U13 إلى 801471. </span></p></li>
        <li><p> <span dir="rtl"> </span>تم تحصيل مبلغ الضريبة المضافة لعملاء الدفع المسبق عند تحصيل الرصيد.</p></li>
        
          </div>

          <div class="condition-box">
          <h4><b>Terms and conditions Zain</b></h4>  

            <ul>
        <li><p> <span dir="rtl">من خلال الاشتراك في الخدمة ، فإنك تقبل جميع شروط وأحكام الخدمة وتفوض مشاركة رقم هاتفك المحمول مع شريكنا ArshiyaInfosolutions ، الذي يدير خدمة الاشتراك هذه. </span></p></li>
        <li><p> <span dir="rtl">يتم تطبيق رسوم البيانات على تصفح المحتويات على هذه البوابة. </span></p></li>
        <li><p><span dir="rtl"> الخدمة مدعومة فقط للهواتف الذكية إذا كان جهازك يدعم البث ، فيمكنك بث مقاطع فيديو غير محدودة أثناء كونك مشتركًا نشطًا في الخدمة. </span></p></li>
        <li><p><span dir="rtl"> للاستفادة من هذه الخدمة ، يجب أن يكون عمر الشخص أكثر من 18 عامًا أو حصل على إذن من والديك أو الشخص المخول بدفع فاتورة هاتفك المحمول. </span></p></li>
            <li><p><span dir="rtl">للإلغاء الاشتراك أرسل U32 إلى 709222 </span></p></li>
      </ul>
          </div>
          
        </div>
      </div>
   </div>
</div>










</body>
</html>

<style>
  
  .condition-box {
    background-color: #e5e5e5;
    padding: 8px 20px 1px 31px;
}
</style>