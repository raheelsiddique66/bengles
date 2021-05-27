<?php
if(!defined("APP_START")) die("No Direct Access");
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from customer where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$customer=dofetch($rs);		
	}
	else{
		header("Location: customer_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: customer_manage.php?tab=list");
	die;
}