<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($customer_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update customer_payment set `customer_id`='".slash($customer_id)."',`machine_id`='".slash($machine_id)."',`datetime_added`='".slash(datetime_dbconvert(unslash($datetime_added)))."', `amount`='".slash($amount)."',`discount`='".slash($discount)."',`account_id`='".slash($account_id)."',`details`='".slash($details)."',`claim`='".slash($claim)."',`extra_discount`='".slash($extra_discount)."',`virus`='".slash($virus)."',`package`='".slash($package)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["customer_payment_manage"]["edit"]);
		header('Location: customer_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_payment_manage"]["edit"][$key]=$value;
		header("Location: customer_payment_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from customer_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added = datetime_convert( $datetime_added );
		if(isset($_SESSION["customer_payment_manage"]["edit"]))
			extract($_SESSION["customer_payment_manage"]["edit"]);
	}
	else{
		header("Location: customer_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: customer_payment_manage.php?tab=list");
	die;
}