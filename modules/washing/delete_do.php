<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from washing where id='".$id."'",$dblink);
	doquery("delete from washing_items where washing_id='".$id."'",$dblink);
	header("Location: washing_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}