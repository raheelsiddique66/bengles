<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_add"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO employees (name, father_name, phone_number, salary_type, salary, over_time_per_hour) VALUES ('".slash($name)."', '".slash($father_name)."', '".slash($phone_number)."', '".slash($salary_type)."', '".slash($salary)."', '".slash($over_time_per_hour)."')";
		doquery($sql,$dblink);
		unset($_SESSION["employee_manage"]["add"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["add"][$key]=$value;
		header('Location: employee_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}