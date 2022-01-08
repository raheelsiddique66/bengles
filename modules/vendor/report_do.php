<?php
if(!defined("APP_START")) die("No Direct Access");
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from vendor where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$vendor=dofetch($rs);
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