<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'delivery_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report", "addedit", "print_receipt");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if( isset($_GET["date_from"]) ){
	$_SESSION["delivery"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["delivery"]["list"]["date_from"]) && !empty($_SESSION["delivery"]["list"]["date_from"])){
	$date_from = $_SESSION["delivery"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["delivery"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["delivery"]["list"]["date_to"]) && !empty($_SESSION["delivery"]["list"]["date_to"])){
	$date_to = $_SESSION["delivery"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date<='".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["customer_id"])){
	$customer_id=slash($_GET["customer_id"]);
	$_SESSION["delivery"]["list"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["delivery"]["list"]["customer_id"]))
	$customer_id=$_SESSION["delivery"]["list"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$_SESSION["delivery"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["delivery"]["list"]["q"])) {
	$q=$_SESSION["delivery"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id like '%".$q."%')";
	$is_search=true;
}
$sql = "SELECT * FROM `delivery` WHERE 1 $extra  order by gatepass_id ASC";
switch($tab){
	case 'addedit':
		include("modules/delivery/addedit_do.php");
	break;
	case 'delete':
		include("modules/delivery/delete_do.php");
	break;
	case 'status':
		include("modules/delivery/status_do.php");
	break;
	case 'bulk_action':
		include("modules/delivery/bulkactions.php");
	break;
	case 'report':
		include("modules/delivery/report.php");
		die;
	break;
	case 'print_receipt':
		include("modules/delivery/print_receipt.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/delivery/list.php");
			break;
			case 'addedit':
				include("modules/delivery/addedit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>