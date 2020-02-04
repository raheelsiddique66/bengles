<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["scheduled_transaction_edit"])){
	extract($_POST);
	$err="";
	if($account_id == "" || $reference_id == "")
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$ts = strtotime(datetime_dbconvert($datetime_added));
		if( $lastrun < $ts ) {
			$lastrun = $ts;
		}
		$nextrun = get_nextrun( $schedule, $day_number, $lastrun); 
		$sql="Update scheduled_transaction set type='".$type."', schedule = '".slash($schedule)."', day_number='".slash($day_number)."', `account_id`='".slash($account_id)."',`reference_id`='".slash($reference_id)."',`datetime_added`='".slash(datetime_dbconvert($datetime_added))."',`amount`='".slash($amount)."',`details`='".slash($details)."', nextrun='".$nextrun."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["scheduled_transaction_manage"]["edit"]);
		header('Location: scheduled_transaction_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["scheduled_transaction_manage"]["edit"][$key]=$value;
		header("Location: scheduled_transaction_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from scheduled_transaction where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added=datetime_convert($datetime_added);
		if(isset($_SESSION["scheduled_transaction_manage"]["edit"]))
			extract($_SESSION["scheduled_transaction_manage"]["edit"]);
	}
	else{
		header("Location: scheduled_transaction_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: scheduled_transaction_manage.php?tab=list");
	die;
}