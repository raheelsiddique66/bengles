<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["vendor_add"])){
	extract($_POST);
	$err="";
	if(empty($vendor_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO vendor (vendor_name, phone, address, vendor_name_urdu, balance, machine_id, sortorder) VALUES ('".slash($vendor_name)."', '".slash($phone)."', '".slash($address)."', '".slash($vendor_name_urdu)."', '".slash($balance)."', '".slash($machine_id)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["vendor_manage"]["add"]);
		header('Location: vendor_manage.php?tab=list&msg='.url_encode("Successfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["vendor_manage"]["add"][$key]=$value;
		header('Location: vendor_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}