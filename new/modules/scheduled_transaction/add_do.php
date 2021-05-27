<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["scheduled_transaction_add"])){
	extract($_POST);
	$err="";
	if($account_id == "")
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$nextrun = get_nextrun( $schedule, $day_number, strtotime(datetime_dbconvert($datetime_added))); 
		$sql="INSERT INTO scheduled_transaction ( type, schedule, day_number, project_id, account_id, reference_id, datetime_added, amount, details,nextrun) VALUES ( '".$type."', '".slash($schedule)."','".slash($day_number)."','".(isset($_SESSION["scheduled_transaction"]["list"]["project_id"])?$_SESSION["scheduled_transaction"]["list"]["project_id"]:0)."', '".slash($account_id)."','".slash($reference_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash($amount)."','".slash($details)."','".$nextrun."')";
		doquery($sql,$dblink);
		unset($_SESSION["scheduled_transaction_manage"]["add"]);
		header('Location: scheduled_transaction_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["scheduled_transaction_manage"]["add"][$key]=$value;
		header('Location: scheduled_transaction_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}