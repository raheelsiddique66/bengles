<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from delivery where id='".$id."'",$dblink);
	doquery("delete from delivery_items where delivery_id='".$id."'",$dblink);
	header("Location: delivery_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}