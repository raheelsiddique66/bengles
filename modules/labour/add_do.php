<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["labour_add"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO labour (name) VALUES ('".slash($name)."')";
		doquery($sql,$dblink);
		unset($_SESSION["labour_manage"]["add"]);
		header('Location: labour_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["labour_manage"]["add"][$key]=$value;
		header('Location: labour_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}