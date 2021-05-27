<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($employee_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO employee_payment (employee_id, date, amount, account_id) VALUES ('".slash($employee_id)."', '".slash(date_dbconvert($date))."', '".slash($amount)."', '".slash($account_id)."')";
		doquery($sql,$dblink);
		unset($_SESSION["employee_payment_manage"]["add"]);
		header('Location: employee_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_payment_manage"]["add"][$key]=$value;
		header('Location: employee_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}