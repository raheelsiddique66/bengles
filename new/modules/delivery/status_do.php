<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	$rec = doquery( "select * from delivery where id='".$id."'", $dblink );
	if( numrows( $rec ) > 0 ) {
		$rec = dofetch( $rec );
		if( $rec[ "customer_payment_id" ] > 0 ) {
			doquery( "update customer_payment set status='".$status."' where id = '".$rec[ "customer_payment_id" ]."'", $dblink );
		}
	}
	doquery("update delivery set status='".$status."' where id='".$id."' ",$dblink);
	header("Location: delivery_manage.php");
	die;
}