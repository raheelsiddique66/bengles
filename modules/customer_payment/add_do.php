<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($customer_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO customer_payment (customer_id, machine_id, datetime_added, amount, discount, account_id, details, claim, extra_discount, virus, package) VALUES ('".slash($customer_id)."','".slash($machine_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash($amount)."','".slash($discount)."','".slash($account_id)."','".slash($details)."','".slash($claim)."','".slash($extra_discount)."','".slash($virus)."','".slash($package)."')";
		doquery($sql,$dblink);
		unset($_SESSION["customer_payment_manage"]["add"]);
		header('Location: customer_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_payment_manage"]["add"][$key]=$value;
		header('Location: customer_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}