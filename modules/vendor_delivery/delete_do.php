<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$vendor_delivery = doquery( "select * from vendor_delivery where id = '".$id."' ", $dblink );
	if( numrows( $vendor_delivery ) > 0 ) {
		$vendor_delivery = dofetch( $vendor_delivery );
		doquery("delete from vendor_delivery_items where vendor_delivery_id='".$id."'",$dblink);
		if( $vendor_delivery[ "vendor_payment_id" ] > 0 ) {
			doquery( "delete from vendor_payment where id = '".$vendor_delivery[ "vendor_payment_id" ]."'", $dblink );
		}
		doquery("delete from vendor_delivery where id='".$id."'",$dblink);
	}
	header("Location: vendor_delivery_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}