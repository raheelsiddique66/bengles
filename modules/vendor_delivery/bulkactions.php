<?php
if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			$i=0;
			while($i<count($id)){
				$vendor_delivery = doquery( "select * from vendor_delivery where id = '".$id[$i]."' ", $dblink );
				if( numrows( $vendor_delivery ) > 0 ) {
					$vendor_delivery = dofetch( $vendor_delivery );
					doquery("delete from vendor_delivery_items where vendor_delivery_id='".$id[$i]."'",$dblink);
					if( $vendor_delivery[ "vendor_payment_id" ] > 0 ) {
						doquery( "delete from vendor_payment where id = '".$vendor_delivery[ "vendor_payment_id" ]."'", $dblink );
					}
					doquery("delete from vendor_delivery where id='".$id[$i]."'",$dblink);
				}
				$i++;
			}
			header("Location: vendor_delivery_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from vendor_delivery where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "vendor_payment_id" ] > 0 ) {
						doquery( "update vendor_payment set status=1 where id = '".$rec[ "vendor_payment_id" ]."'", $dblink );
					}
				}
				doquery("update vendor_delivery set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: vendor_delivery_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from vendor_delivery where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "vendor_payment_id" ] > 0 ) {
						doquery( "update vendor_payment set status=0 where id = '".$rec[ "vendor_payment_id" ]."'", $dblink );
					}
				}
				doquery("update vendor_delivery set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: vendor_delivery_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: vendor_delivery_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}