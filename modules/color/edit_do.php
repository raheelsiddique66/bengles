<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["color_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update color set `title`='".slash($title)."', `title_urdu`='".slash($title_urdu)."', `sortorder`='".slash($sortorder)."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["color_manage"]["edit"]);
		header('Location: color_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["color_manage"]["edit"][$key]=$value;
		header("Location: color_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from color where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["color_manage"]["edit"]))
			extract($_SESSION["color_manage"]["edit"]);
	}
	else{
		header("Location: color_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: color_manage.php?tab=list");
	die;
}