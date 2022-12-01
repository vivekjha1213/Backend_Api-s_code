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

    $("#subscribeyes").click(function() {
        $('#newgen').append('<div class="spinner-border"></div>');
        var msisdn=$('#msisdn').val();
		var cid=$('#cid').val();
	
        var selecOprator=$('#mySelect').val();
        // ops=op;
        if(selecOprator=="Mobily"){
        	var oprator="Mobily"
        	var url_page="pin_request.php"
        }
        else if(selecOprator=="STC"){
        	var oprator="STC"
        	var url_page="stc_pin_request.php"
        }
        else{
        	var oprator="Zain"
            var url_page="zain_pin_request.php"
        }
		//opraort close

        console.log(url_page);
        console.log(oprator);
        // return false;
        console.log(cid);
        var n = msisdn.length;
        if(n==9 || n==12)
        {	
			if(n==9)
			{ 
				var msisdn=966+msisdn;
				//alert(msisdn);
				//return false;
			}
		}
		else
		{
			//alert("ENTER A VALID MOBILE NUMBER");
			alert("أدخل رقم هاتف صالح  (ENTER A VALID MOBILE NUMBER)");
			$('#newgen').hide();
			return false;
		}
	
            $.ajax({
                type:"POST",
                url:url_page,
                data:{msisdn:msisdn,cid:cid,oprator:oprator},
                success:function(result)
                {
                   $('#newgen').hide();
				   var jsonData = JSON.parse(result);
                  	//var jsonData=JSON.stringify(result);
                  	//alert(jsonData.status);
                  	//return false;
                    if (jsonData.status == "0")
                    {   
                        location.href = 'otp_page.php?msisdn='+msisdn+'&cid='+cid+'&oprator='+oprator+'';
                        return false;
                    }
                    else if(jsonData.status =="1")
                    {
                        location.href = 'http://beyondhealth.mobi/saudi_arabia/?msisdn='+msisdn+'';
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
	            		<a href="index0.php?cid="<?=$_GET['cid'];?>" class="btn btn-1">
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
            <form method="get" class="">

            	<!-- dropdow oprator -->
				<!-- <form id="ok"> -->
				  <!-- Select your favorite fruit: -->
				  <div class="select">
				  	  <select id="mySelect">
				    <option value="Mobily">Mobily</option>
				    <option value="STC">STC</option>
				    <option value="Zain">Zain</option>
				  </select>
				  </div>
				
				<br>
            	<!-- dropdow oprator -->


            	<label>3 أيام مجانية بعد ذلك 1 ريال / اليوم</label>
            	<input type="hidden" name="cid" id="cid" value="<?php echo $_GET['cid'];?>">
				<input type="text" name="msisdn" id="msisdn"class="form-input" placeholder="أدخل رقم هاتفك  " value="<?=$_GET['msisdn'];?>">
				<!--<div class="chiller_cb tick-box">
					<label><input value="I have been informed of the terms of use and I accept to activate the BEYOND HEALTH subscription service. 6.20 € / Week" type="checkbox" name="Checkbox"><i>(Tick the box)</i></label>
				</div>
				<div class="chiller_cb">
				  <label> I have been informed of the terms of use and I accept to activate the BEYOND HEALTH subscription service. 6.20 € / Week</label>
				</div>-->
            	
            
            	<!--<input class="btn-door" type="button" name="subscribe" id="submityes" value="الإشتراك">-->
            	<button type="button" name="subscribe" id="subscribeyes" class="btn-door">إرسال</button>
            	<br>
            	<div id="newgen"></div>
          </form>
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

    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        -ms-appearance: none;
        appearance: none;
        outline: 0;
        background: #75a501;
        background-image: none;
        border: 1px solid black;
    }
     
    .select {
        position: relative;
        display: block;
        width: 10em;
        margin: auto;

        height: 3em;
        line-height: 3;
        background: #2C3E50;
        overflow: hidden;
        border-radius: .25em;
    }
     
    select {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0 0 0 .5em;
        color: #fff;
        cursor: pointer;
    }
     
    select::-ms-expand {
        display: none;
    }
     
    .select::after {
        content: '\25BC';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        padding: 0 1em;
        background: #34495E;
        pointer-events: none;
    }
     
    .select:hover::after {
        color: #F39C12;
    }
    .condition-box {
    background-color: #e5e5e5;
    padding: 8px 20px 1px 31px;
}x
</style>