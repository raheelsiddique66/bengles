<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from color_field where id='".slash($_GET["id"])."'",$dblink);
	header("Location: color_field_manage.php");
	die;
}