<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'production_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report", "addedit", "print_receipt", "report_total", "new_production");
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
	$_SESSION["production"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["production"]["list"]["date_from"]) && !empty($_SESSION["production"]["list"]["date_from"])){
	$date_from = $_SESSION["production"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["production"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["production"]["list"]["date_to"]) && !empty($_SESSION["production"]["list"]["date_to"])){
	$date_to = $_SESSION["production"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date<='".date_dbconvert($date_to)."'";
	$is_search=true;
}
if(isset($_GET["customer_id"])){
	$customer_id=slash($_GET["customer_id"]);
	$_SESSION["production"]["list"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["production"]["list"]["customer_id"]))
	$customer_id=$_SESSION["production"]["list"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$_SESSION["production"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["production"]["list"]["q"])) {
	$q=$_SESSION["production"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id like '%".$q."%')";
	$is_search=true;
}
if(isset($_GET["color_id"])){
	$color_id=slash($_GET["color_id"]);
	$_SESSION["production"]["list"]["color_id"]=$color_id;
}
if(isset($_SESSION["production"]["list"]["color_id"]))
	$color_id=$_SESSION["production"]["list"]["color_id"];
else
	$color_id="";
if($color_id!=""){
	$extra.=" and c.color_id='".$color_id."'";
	$is_search=true;
}
if(isset($_GET["design_id"])){
	$design_id=slash($_GET["design_id"]);
	$_SESSION["production"]["list"]["design_id"]=$design_id;
}
if(isset($_SESSION["production"]["list"]["design_id"]))
	$design_id=$_SESSION["production"]["list"]["design_id"];
else
	$design_id="";
if($design_id!=""){
	$extra.=" and c.design_id='".$design_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
	$machine_id=slash($_GET["machine_id"]);
	$_SESSION["production"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["production"]["list"]["machine_id"]))
	$machine_id=$_SESSION["production"]["list"]["machine_id"];
else
	$machine_id="";
if($tab!=="report_total"){
    if($machine_id!=""){
        $extra.=" and c.machine_id='".$machine_id."'";
        $is_search=true;
    }
}
$sql = "SELECT a.* FROM `production` a left join customer b on a.customer_id = b.id left join production_items c on a.id = c.production_id WHERE 1 $extra group by a.id order by a.date desc, a.gatepass_id desc";
switch($tab){
	case 'addedit':
		include("modules/production/addedit_do.php");
	break;
	case 'delete':
		include("modules/production/delete_do.php");
	break;
	case 'status':
		include("modules/production/status_do.php");
	break;
	case 'bulk_action':
		include("modules/production/bulkactions.php");
	break;
	case 'report':
		include("modules/production/report.php");
		die;
	break;
    case 'print_receipt':
        include("modules/production/print_receipt.php");
	break;
	case 'report_total':
		include("modules/production/report_total.php");
	break;
	case 'new_production':
        include("modules/production/new_production_do.php");
    break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/production/list.php");
			break;
			case 'addedit':
				include("modules/production/addedit.php");
			break;
			case 'new_production':
				include("modules/production/new_production.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
