<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	doquery("update production set status='".$status." where id='".$id."'",$dblink);
	header("Location: production_manage.php");
	die;
}