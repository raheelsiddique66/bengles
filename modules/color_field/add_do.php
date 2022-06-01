<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["color_field_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO color_field (title, title_urdu, sortorder) VALUES ('".slash($title)."', '".slash($title_urdu)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["color_field_manage"]["add"]);
		header('Location: color_field_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["color_field_manage"]["add"][$key]=$value;
		header('Location: color_field_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}