<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["design_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO design (title, title_urdu, sortorder) VALUES ('".slash($title)."', '".slash($title_urdu)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["design_manage"]["add"]);
		header('Location: design_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["design_manage"]["add"][$key]=$value;
		header('Location: design_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}