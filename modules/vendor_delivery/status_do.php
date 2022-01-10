<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	$rec = doquery( "select * from vendor_delivery where id='".$id."'", $dblink );
	if( numrows( $rec ) > 0 ) {
		$rec = dofetch( $rec );
		if( $rec[ "vendor_payment_id" ] > 0 ) {
			doquery( "update vendor_payment set status='".$status."' where id = '".$rec[ "vendor_payment_id" ]."'", $dblink );
		}
	}
	doquery("update vendor_delivery set status='".$status."' where id='".$id."' ",$dblink);
	header("Location: vendor_delivery_manage.php");
	die;
}