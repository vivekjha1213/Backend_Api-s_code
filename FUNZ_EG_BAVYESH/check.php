<?php
        include("connect.php");
        $sql="SELECT * from `in_app_pin_verify` where `msisdn`='".$msisdn."'  and serviceId='ARSH0029'";
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