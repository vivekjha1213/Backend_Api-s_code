<?php
date_default_timezone_set("Europe/Madrid");
$link = mysql_connect('63.142.255.67', 'root', '4cafe!@#321');
if (!$link) {
    die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db('mobileca_cafe4u', $link);
if (!$db_selected) {
    die ('Can\'t use Database : ' . mysql_error());
}
/******************* Language Settings **********************/
$jsonbase = 'http://202.0.103.211/';
/******************* Language Settings **********************/

if (!isset($_SESSION['mcafe_language3'])) {
    $_SESSION['mcafe_language3'] = 'Arabic';
}
$eng['logo'] = 'Mobile Café';
$agrbn['logo'] = 'موبايل كافي';
$eng['Home'] = 'Home';
$agrbn['Home'] = 'الصفحة الرئيسية';
$eng['My_Account'] = 'My Account';
$agrbn['My_Account'] = 'حسابي';
$eng['faq'] = 'FAQ';
$agrbn['faq'] = 'التعليمات';
$eng['tc'] = 'T&C';
$agrbn['tc'] = 'الشروط و الاحكام';
$eng['Search'] = 'Search';
$agrbn['Search'] = 'بحث';
$eng['English'] = 'English';
$agrbn['English'] = 'English';
$eng['SA'] = 'Spanish/Azerbaijani/Arabic';
$agrbn['SA'] = 'عربى';
$eng['Hollywood'] = 'Hollywood';
$agrbn['Hollywood'] = 'هوليوود';
$eng['bollywood'] = 'Bollywood';
$agrbn['bollywood'] = 'بوليوود';
$eng['Kids'] = 'Kids';
$agrbn['Kids'] = 'أطفال';
$eng['Comedy'] = 'Comedy';
$agrbn['Comedy'] = 'كوميديا';
$eng['Sports'] = 'Sports';
$agrbn['Sports'] = 'رياضة';
$eng['Glamour'] = 'Glamour';
$agrbn['Glamour'] = 'Çəkici';
$eng['Games'] = 'Games';
$agrbn['Games'] = 'ألعاب';
$eng['Music'] = 'Music';
$agrbn['Music'] = 'موسيقى';
$eng['Dear_User'] = 'Dear User';
$agrbn['Dear_User'] = 'عزيزي المستخدم';
$eng['Dear'] = 'Dear User,';
$agrbn['Dear'] = 'عزيزي المستخدم';
$eng['content_Download'] = 'You will be subscribed to Mobile Cafe "Daily". Your account would be debited with AZN 0.59 for 1 days.';
//$agrbn['content_Download'] = 'سوف تكون مشترك في موبايل كافيه "اليومية". سيتم خصم مبلغ أزن 0.59 من حسابك لمدة يوم واحد';
$agrbn['content_Download'] = 'سوف تكون مشترك في موبايل كافيه "اليومية"';
$eng['welcome_msg'] = 'Welcome to Mobile Cafe. Click on any content to download';
$agrbn['welcome_msg'] = 'مرحبا بكم في موبايل كافي . اضغط على أي محتوى لتحميل';
$eng['subscribed_msg'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg'] = 'أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['subscribed_msg1'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg1'] = 'أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['subscribed_msg2'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg2'] = 'أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['subscribed_msg4'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg4'] ='أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['subscribed_msg5'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg5'] ='أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['subscribed_msg3'] = 'You are currently subscribed to Mobile Cafe.To unsubscribe the service, please follow below instructions';
$agrbn['subscribed_msg3'] ='أنت مشترك حالياً في Mobile Cafe. لإلغاء الاشتراك في الخدمة ، الرجاء اتباع الإرشادات أدناه';
$eng['not_subscribed_msg'] = 'You currently not subscribed to Mobile Cafe. For subscribing the service click below';
$agrbn['not_subscribed_msg'] = 'أنت غير مشترك حاليًا في Mobile Cafe. للاشتراك في الخدمة ، انقر أدناه';
$eng['waiting_msg'] = 'Your requets is under process kindly wait for some time to acces the service';
$agrbn['waiting_msg'] = 'طلبك قيد العملية يرجى الانتظار لبعض الوقت للوصول إلى الخدمة';
$eng['enjoy_download_msg'] = 'Welcome to Mobile Cafe. Clcik on any contetn and download to enjoy the service.';
$agrbn['enjoy_download_msg'] = 'مرحبا بكم في مقهى موبايل. كلسيك على أي كونتيتن وتحميل للاستمتاع الخدمة.';
$eng['More'] = 'More';
$agrbn['More'] = 'المزيد';
$eng['Powered_by'] = 'Powered by Arshiyainfosolutions Private Limited';
$agrbn['Powered_by'] = 'مدعوم من Arshiyainfosolutions Private Limited';
$eng['Whats_new'] = 'Whats new';
$agrbn['Whats_new'] = 'ما هو الجديد';
$eng['Best_Seller'] = 'Best Seller';
$agrbn['Best_Seller'] = 'الأكثر مبيعًا';
$eng['Reccomanded'] = 'Reccomanded';
$agrbn['Reccomanded'] = 'مقترحة';
$eng['Next'] = 'Next';
$agrbn['Next'] = 'التالى';
$eng['Previous'] = 'Previous';
$agrbn['Previous'] = 'سابق';
$eng['action_games'] = 'Best Action Games';
$agrbn['action_games'] = 'أفضل ألعاب الحركة';
$eng['car_race'] = 'Car Racer Vs Death Race';
$agrbn['car_race'] = 'سيارة متسابق مقابل سباق الموت';
$eng['most_download'] = 'Most Donwload Games Click to Enjoy';
$agrbn['most_download'] = 'معظم دونولود ألعاب انقر للاستمتاع';
$eng['c_enjoy'] = 'Click and ENJOY';
$agrbn['c_enjoy'] = 'انقر والاستمتاع';
$eng['sub'] = 'subscribe';
$agrbn['sub'] = 'اشتراك';
$eng['unsub'] = 'Unsubscribe';
$agrbn['unsub'] = 'إلغاء الاشتراك';
?>