<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["invoice_add"])){
	extract($_POST);
	$err="";
	if($err==""){
        $customer_ids = [];
        if(!empty($customer_id)){
           $customer_ids[] = $customer_id;
        }
        else{
            $rs = doquery("select * from customer where status = 1 order by customer_name", $dblink);
            if(numrows($rs)>0){
                while($r = dofetch($rs)){
                    $customer_ids[] = $r["id"];
                }
            }
        }
        foreach($customer_ids as $customer_id){
            $sql="INSERT INTO invoice (customer_id, machine_id, datetime_added, date_from, date_to, notes) VALUES ('".slash($customer_id)."','".slash($machine_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash(date_dbconvert($date_from))."','".slash(date_dbconvert($date_to))."','".slash($notes)."')";
            doquery($sql,$dblink);
        }
		unset($_SESSION["invoice_manage"]["add"]);
		header('Location: invoice_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["invoice_manage"]["add"][$key]=$value;
		header('Location: invoice_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}
