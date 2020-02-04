<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["color_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO color (title, title_urdu) VALUES ('".slash($title)."', '".slash($title_urdu)."')";
		doquery($sql,$dblink);
		unset($_SESSION["color_manage"]["add"]);
		header('Location: color_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["color_manage"]["add"][$key]=$value;
		header('Location: color_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}