<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["vendor_id"])){
	$vendor_id=slash($_GET["vendor_id"]);
	$_SESSION["vendor_payment"]["list"]["vendor_id"]=$vendor_id;
}
if(isset($_SESSION["vendor_payment"]["list"]["vendor_id"]))
	$vendor_id=$_SESSION["vendor_payment"]["list"]["vendor_id"];
else
	$vendor_id="";
if($vendor_id!=""){
	$extra.=" and vendor_id='".$vendor_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
    $machine_id=slash($_GET["machine_id"]);
    $_SESSION["vendor_payment"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["vendor_payment"]["list"]["machine_id"]))
    $machine_id=$_SESSION["vendor_payment"]["list"]["machine_id"];
else
    $machine_id="";
if($machine_id!=""){
    $extra.=" and a.machine_id='".$machine_id."'";
    $is_search=true;
}
if(isset($_GET["account_id"])){
	$account_id=slash($_GET["account_id"]);
	$_SESSION["vendor_payment"]["list"]["account_id"]=$account_id;
}
if(isset($_SESSION["vendor_payment"]["list"]["account_id"]))
	$account_id=$_SESSION["vendor_payment"]["list"]["account_id"];
else
	$account_id="";
if($account_id!=""){
	$extra.=" and account_id='".$account_id."'";
	$is_search=true;
}
if( isset($_GET["date_from"]) ){
	$_SESSION["vendor_payment"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["vendor_payment"]["list"]["date_from"]) && !empty($_SESSION["vendor_payment"]["list"]["date_from"])){
	$date_from = $_SESSION["vendor_payment"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and datetime_added>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["vendor_payment"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["vendor_payment"]["list"]["date_to"]) && !empty($_SESSION["vendor_payment"]["list"]["date_to"])){
	$date_to = $_SESSION["vendor_payment"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and datetime_added<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["vendor_payment"]["list"]["q"]=$q;
}
if(isset($_SESSION["vendor_payment"]["list"]["q"]))
	$q=$_SESSION["vendor_payment"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and b.vendor_name like '%".$q."%'";
	$is_search=true;
}
$sql="select a.*, b.vendor_name_urdu, b.vendor_name from vendor_payment a inner join vendor b on a.vendor_id = b.id where 1 ".$extra." order by vendor_name, datetime_added desc";
switch($tab){
	case 'add':
		include("modules/vendor_payment/add_do.php");
	break;
	case 'edit':
		include("modules/vendor_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/vendor_payment/delete_do.php");
	break;
	case 'status':
		include("modules/vendor_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/vendor_payment/bulkactions.php");
	break;
	case 'report':
		include("modules/vendor_payment/report.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/vendor_payment/list.php");
			break;
			case 'add':
				include("modules/vendor_payment/add.php");
			break;
			case 'edit':
				include("modules/vendor_payment/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>