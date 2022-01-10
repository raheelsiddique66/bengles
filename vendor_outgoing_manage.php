<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'vendor_outgoing_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report", "addedit", "print_receipt", "report_total", "new_vendor_outgoing");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$extra1='';
$is_search=false;
if( isset($_GET["date_from"]) ){
	$_SESSION["vendor_outgoing"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["vendor_outgoing"]["list"]["date_from"]) && !empty($_SESSION["vendor_outgoing"]["list"]["date_from"])){
	$date_from = $_SESSION["vendor_outgoing"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["vendor_outgoing"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["vendor_outgoing"]["list"]["date_to"]) && !empty($_SESSION["vendor_outgoing"]["list"]["date_to"])){
	$date_to = $_SESSION["vendor_outgoing"]["list"]["date_to"];
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
	$_SESSION["vendor_outgoing"]["list"]["vendor_id"]=$vendor_id;
}
if(isset($_SESSION["vendor_outgoing"]["list"]["vendor_id"]))
	$vendor_id=$_SESSION["vendor_outgoing"]["list"]["vendor_id"];
else
	$vendor_id="";
if($vendor_id!=""){
	$extra.=" and vendor_id='".$vendor_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
	$machine_id=slash($_GET["machine_id"]);
	$_SESSION["vendor_outgoing"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["vendor_outgoing"]["list"]["machine_id"]))
	$machine_id=$_SESSION["vendor_outgoing"]["list"]["machine_id"];
else
	$machine_id="";
if($tab!=="report_total"){
    if($machine_id!=""){
        $extra.=" and c.machine_id='".$machine_id."'";
        $is_search=true;
    }
}
if(isset($_GET["q"])){
	$_SESSION["vendor_outgoing"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["vendor_outgoing"]["list"]["q"])) {
	$q=$_SESSION["vendor_outgoing"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id = '".$q."' or a.id = '".$q."')";
	$is_search=true;
}
$sql = "SELECT a.* FROM `vendor_outgoing` a left join vendor b on a.vendor_id = b.id left join vendor_outgoing_items c on a.id = c.vendor_outgoing_id WHERE 1 $extra group by vendor_id order by date desc, gatepass_id desc";
switch($tab){
	case 'addedit':
		include("modules/vendor_outgoing/addedit_do.php");
	break;
	case 'delete':
		include("modules/vendor_outgoing/delete_do.php");
	break;
	case 'status':
		include("modules/vendor_outgoing/status_do.php");
	break;
	case 'bulk_action':
		include("modules/vendor_outgoing/bulkactions.php");
	break;
	case 'report':
		include("modules/vendor_outgoing/report.php");
		die;
	break;
	case 'print_receipt':
		include("modules/vendor_outgoing/print_receipt.php");
	break;
	case 'report_total':
		include("modules/vendor_outgoing/report_total.php");
	break;
	case 'new_vendor_outgoing':
        include("modules/vendor_outgoing/new_vendor_outgoing_do.php");
    break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/vendor_outgoing/list.php");
			break;
			case 'addedit':
				include("modules/vendor_outgoing/addedit.php");
			break;
			case 'new_vendor_outgoing':
				include("modules/vendor_outgoing/new_vendor_outgoing.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
