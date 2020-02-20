<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_edit"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update employees set `name`='".slash($name)."', `father_name`='".slash($father_name)."', `phone_number`='".slash($phone_number)."', `salary_type`='".slash($salary_type)."', `salary`='".slash($salary)."', `over_time_per_hour`='".slash($over_time_per_hour)."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["employee_manage"]["edit"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["edit"][$key]=$value;
		header("Location: employee_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from employees where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["employee_manage"]["edit"]))
			extract($_SESSION["employee_manage"]["edit"]);
	}
	else{
		header("Location: employee_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: employee_manage.php?tab=list");
	die;
}