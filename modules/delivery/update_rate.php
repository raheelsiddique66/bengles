<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$delivery = doquery( "select * from delivery where id = '".$id."' ", $dblink );
	if( numrows( $delivery ) > 0 ) {
		$delivery = dofetch( $delivery );
		doquery("delete from delivery_items where delivery_id='".$id."'",$dblink);
		if( $delivery[ "customer_payment_id" ] > 0 ) {
			doquery( "delete from customer_payment where id = '".$delivery[ "customer_payment_id" ]."'", $dblink );
		}
		doquery("delete from delivery where id='".$id."'",$dblink);
	}
	header("Location: delivery_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}