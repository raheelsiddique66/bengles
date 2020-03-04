<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["invoice_edit"])){
	extract($_POST);
	$err="";
	if(empty($customer_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update invoice set `customer_id`='".slash($customer_id)."',`datetime_added`='".slash(datetime_dbconvert(unslash($datetime_added)))."', `date_from`='".slash(date_dbconvert($date_from))."',`date_to`='".slash(date_dbconvert($date_to))."',`notes`='".slash($notes)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["invoice_manage"]["edit"]);
		header('Location: invoice_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["invoice_manage"]["edit"][$key]=$value;
		header("Location: invoice_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from invoice where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added = datetime_convert( $datetime_added );
			$date_from = date_convert( $date_from );
			$date_to = date_convert( $date_to );
		if(isset($_SESSION["invoice_manage"]["edit"]))
			extract($_SESSION["invoice_manage"]["edit"]);
	}
	else{
		header("Location: invoice_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: invoice_manage.php?tab=list");
	die;
}