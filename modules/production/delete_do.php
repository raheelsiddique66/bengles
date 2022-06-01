<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from production where id='".$id."'",$dblink);
	doquery("delete from production_items where production_id='".$id."'",$dblink);
	header("Location: production_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}