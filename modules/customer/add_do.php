<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_add"])){
	extract($_POST);
	$err="";
	if(empty($customer_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO customer (customer_name, phone, address, customer_name_urdu, balance, machine_id, sortorder) VALUES ('".slash($customer_name)."', '".slash($phone)."', '".slash($address)."', '".slash($customer_name_urdu)."', '".slash($balance)."', '".slash($machine_id)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["customer_manage"]["add"]);
		header('Location: customer_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_manage"]["add"][$key]=$value;
		header('Location: customer_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}