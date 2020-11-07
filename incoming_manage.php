<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'incoming_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report", "addedit", "print_receipt", "report_total");
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
	$_SESSION["incoming"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["incoming"]["list"]["date_from"]) && !empty($_SESSION["incoming"]["list"]["date_from"])){
	$date_from = $_SESSION["incoming"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["incoming"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["incoming"]["list"]["date_to"]) && !empty($_SESSION["incoming"]["list"]["date_to"])){
	$date_to = $_SESSION["incoming"]["list"]["date_to"];
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
	$_SESSION["incoming"]["list"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["incoming"]["list"]["customer_id"]))
	$customer_id=$_SESSION["incoming"]["list"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
	$machine_id=slash($_GET["machine_id"]);
	$_SESSION["incoming"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["incoming"]["list"]["machine_id"]))
	$machine_id=$_SESSION["incoming"]["list"]["machine_id"];
else
	$machine_id="";
if($tab!=="report_total"){
    if($machine_id!=""){
        $extra.=" and c.machine_id='".$machine_id."'";
        $is_search=true;
    }
}
if(isset($_GET["q"])){
	$_SESSION["incoming"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["incoming"]["list"]["q"])) {
	$q=$_SESSION["incoming"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id = '".$q."' or a.id = '".$q."')";
	$is_search=true;
}
$sql = "SELECT a.* FROM `incoming` a left join customer b on a.customer_id = b.id left join incoming_items c on a.id = c.incoming_id WHERE 1 $extra group by incoming_id order by b.customer_name, gatepass_id desc";
switch($tab){
	case 'addedit':
		include("modules/incoming/addedit_do.php");
	break;
	case 'delete':
		include("modules/incoming/delete_do.php");
	break;
	case 'status':
		include("modules/incoming/status_do.php");
	break;
	case 'bulk_action':
		include("modules/incoming/bulkactions.php");
	break;
	case 'report':
		include("modules/incoming/report.php");
		die;
	break;
	case 'print_receipt':
		include("modules/incoming/print_receipt.php");
	break;
	case 'report_total':
		include("modules/incoming/report_total.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/incoming/list.php");
			break;
			case 'addedit':
				include("modules/incoming/addedit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
