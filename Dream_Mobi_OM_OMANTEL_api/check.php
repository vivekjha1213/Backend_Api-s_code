<?php
        include("connect.php");
        $sql="SELECT * from `in_app_pin_verify` where `msisdn`='".$msisdn."' and pubName='Dream Mobi' and serviceName='Dream_Mobi_MOBILECAFE'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
        $code=$row['status'];
        if($code=="PASSED")
        {
                              echo "ACTIVE";

        }
        else
        {
               echo "INACTIVE";
        }       
?>