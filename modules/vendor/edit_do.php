<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["vendor_edit"])){
	extract($_POST);
	$err="";
	if(empty($vendor_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update vendor set `vendor_name`='".slash($vendor_name)."', `phone`='".slash($phone)."', `address`='".slash($address)."', `vendor_name_urdu`='".slash($vendor_name_urdu)."', `balance`='".slash($balance)."', `machine_id`='".slash($machine_id)."', `sortorder`='".slash($sortorder)."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["vendor_manage"]["edit"]);
		header('Location: vendor_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["vendor_manage"]["edit"][$key]=$value;
		header("Location: vendor_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from vendor where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["vendor_manage"]["edit"]))
			extract($_SESSION["vendor_manage"]["edit"]);
	}
	else{
		header("Location: vendor_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: vendor_manage.php?tab=list");
	die;
}