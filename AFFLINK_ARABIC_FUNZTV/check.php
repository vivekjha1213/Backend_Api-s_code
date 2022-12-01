<?php
        include("connect.php");
        $msisdn=$_GET['msisdn'];
        $sql="SELECT status from `in_app_pin_verify` where `msisdn`='".$msisdn."'  and serviceId='ARSH0067' order by id desc limit 1";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
        $code=$row['status'];
    
        if($code=="PASSED")
        {
                               echo "ACTIVE";
                              // echo json_encode(array('errorMessage' => 'Active', 'response' => 'SUCCESS'));
                        

        }
        else
        {
                echo "INACTIVE";

            // echo json_encode(array('errorMessage' => 'Inactive', 'response' => 'FAIL'));
        }       
?>




