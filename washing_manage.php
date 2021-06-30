<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'washing_manage.php';
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
	$_SESSION["washing"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["washing"]["list"]["date_from"]) && !empty($_SESSION["washing"]["list"]["date_from"])){
	$date_from = $_SESSION["washing"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["washing"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["washing"]["list"]["date_to"]) && !empty($_SESSION["washing"]["list"]["date_to"])){
	$date_to = $_SESSION["washing"]["list"]["date_to"];
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
	$_SESSION["washing"]["list"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["washing"]["list"]["customer_id"]))
	$customer_id=$_SESSION["washing"]["list"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$_SESSION["washing"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["washing"]["list"]["q"])) {
	$q=$_SESSION["washing"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (gatepass_id like '%".$q."%')";
	$is_search=true;
}
if(isset($_GET["color_id"])){
	$color_id=slash($_GET["color_id"]);
	$_SESSION["washing"]["list"]["color_id"]=$color_id;
}
if(isset($_SESSION["washing"]["list"]["color_id"]))
	$color_id=$_SESSION["washing"]["list"]["color_id"];
else
	$color_id="";
if($color_id!=""){
	$extra.=" and c.color_id='".$color_id."'";
	$is_search=true;
}
$sql = "SELECT a.* FROM `washing` a left join customer b on a.customer_id = b.id left join washing_items c on a.id = c.washing_id WHERE 1 $extra  order by a.date desc, a.gatepass_id desc";
switch($tab){
	case 'addedit':
		include("modules/washing/addedit_do.php");
	break;
	case 'delete':
		include("modules/washing/delete_do.php");
	break;
	case 'status':
		include("modules/washing/status_do.php");
	break;
	case 'bulk_action':
		include("modules/washing/bulkactions.php");
	break;
	case 'report':
		include("modules/washing/report.php");
		die;
	break;
    case 'print_receipt':
        include("modules/washing/print_receipt.php");
        break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/washing/list.php");
			break;
			case 'addedit':
				include("modules/washing/addedit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
