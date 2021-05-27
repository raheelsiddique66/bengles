<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from incoming where id='".$id."'",$dblink);
	doquery("delete from incoming_items where incoming_id='".$id."'",$dblink);
	header("Location: incoming_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}