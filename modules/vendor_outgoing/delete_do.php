<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from vendor_outgoing where id='".$id."'",$dblink);
	doquery("delete from vendor_outgoing_items where vendor_outgoing_id='".$id."'",$dblink);
	header("Location: vendor_outgoing_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}