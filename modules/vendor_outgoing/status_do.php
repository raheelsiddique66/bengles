<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	doquery("update vendor_outgoing set status='".$status." where id='".$id."'",$dblink);
	header("Location: vendor_outgoing_manage.php");
	die;
}