<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from vendor where id='".slash($_GET["id"])."'",$dblink);
	header("Location: vendor_manage.php");
	die;
}