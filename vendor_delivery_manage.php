<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'vendor_delivery_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report", "addedit", "print_receipt", "report_total", "current_report", "new_vendor_delivery");
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
	$_SESSION["vendor_delivery"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["vendor_delivery"]["list"]["date_from"]) && !empty($_SESSION["vendor_delivery"]["list"]["date_from"])){
	$date_from = $_SESSION["vendor_delivery"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["vendor_delivery"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["vendor_delivery"]["list"]["date_to"]) && !empty($_SESSION["vendor_delivery"]["list"]["date_to"])){
	$date_to = $_SESSION["vendor_delivery"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date<='".date_dbconvert($date_to)."'";
	$is_search=true;
}
if(isset($_GET["vendor_id"])){
	$vendor_id=slash($_GET["vendor_id"]);
	$_SESSION["vendor_delivery"]["list"]["vendor_id"]=$vendor_id;
}
if(isset($_SESSION["vendor_delivery"]["list"]["vendor_id"]))
	$vendor_id=$_SESSION["vendor_delivery"]["list"]["vendor_id"];
else
	$vendor_id="";
if($vendor_id!=""){
	$extra.=" and vendor_id='".$vendor_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
	$machine_id=slash($_GET["machine_id"]);
	$_SESSION["vendor_delivery"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["vendor_delivery"]["list"]["machine_id"]))
	$machine_id=$_SESSION["vendor_delivery"]["list"]["machine_id"];
else
	$machine_id="";
if($tab!=="report_total" && $tab!=="current_report"){
if($machine_id!=""){
	$extra.=" and c.machine_id='".$machine_id."'";
	$is_search=true;
}
}
if(isset($_GET["q"])){
	$_SESSION["vendor_delivery"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["vendor_delivery"]["list"]["q"])) {
	$q=$_SESSION["vendor_delivery"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id like '%".$q."%')";
	$is_search=true;
}
$sql = "SELECT a.* FROM `vendor_delivery` a left join vendor b on a.vendor_id = b.id left join vendor_delivery_items c on a.id = c.vendor_delivery_id WHERE 1 $extra group by c.vendor_delivery_id order by a.date desc, a.gatepass_id desc";
switch($tab){
	case 'addedit':
		include("modules/vendor_delivery/addedit_do.php");
	break;
	case 'delete':
		include("modules/vendor_delivery/delete_do.php");
	break;
	case 'status':
		include("modules/vendor_delivery/status_do.php");
	break;
	case 'bulk_action':
		include("modules/vendor_delivery/bulkactions.php");
	break;
	case 'report':
		include("modules/vendor_delivery/report.php");
		die;
	break;
	case 'print_receipt':
		include("modules/vendor_delivery/print_receipt.php");
	break;
	case 'report_total':
		include("modules/vendor_delivery/report_total.php");
	break;
    case 'current_report':
        include("modules/vendor_delivery/current_report.php");
    break;
	case 'new_vendor_delivery':
        include("modules/vendor_delivery/new_vendor_delivery_do.php");
    break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/vendor_delivery/list.php");
			break;
			case 'addedit':
				include("modules/vendor_delivery/addedit.php");
			break;
			case 'new_vendor_delivery':
				include("modules/vendor_delivery/new_vendor_delivery.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
