<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["vendor_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($vendor_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO vendor_payment (vendor_id, machine_id, datetime_added, amount, discount, account_id, details, claim) VALUES ('".slash($vendor_id)."','".slash($machine_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash($amount)."','".slash($discount)."','".slash($account_id)."','".slash($details)."','".slash($claim)."')";
		doquery($sql,$dblink);
		unset($_SESSION["vendor_payment_manage"]["add"]);
		header('Location: vendor_payment_manage.php?tab=list&msg='.url_encode("Successfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["vendor_payment_manage"]["add"][$key]=$value;
		header('Location: vendor_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}