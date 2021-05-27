<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["customer_id"])){
	$customer_id=slash($_GET["customer_id"]);
	$_SESSION["invoice"]["list"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["invoice"]["list"]["customer_id"]))
	$customer_id=$_SESSION["invoice"]["list"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
if(isset($_GET["machine_id"])){
    $machine_id=slash($_GET["machine_id"]);
    $_SESSION["invoice"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["invoice"]["list"]["machine_id"]))
    $machine_id=$_SESSION["invoice"]["list"]["machine_id"];
else
    $machine_id="";
if($machine_id!=""){
    $extra.=" and machine_id='".$machine_id."'";
    $is_search=true;
}
if( isset($_GET["date_from"]) ){
	$_SESSION["invoice"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["invoice"]["list"]["date_from"]) && !empty($_SESSION["invoice"]["list"]["date_from"])){
	$date_from = $_SESSION["invoice"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date_from>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["invoice"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["invoice"]["list"]["date_to"]) && !empty($_SESSION["invoice"]["list"]["date_to"])){
	$date_to = $_SESSION["invoice"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date_to<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["invoice"]["list"]["q"]=$q;
}
if(isset($_SESSION["invoice"]["list"]["q"]))
	$q=$_SESSION["invoice"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and a.id like '%".$q."%'";
	$is_search=true;
}
$sql="select a.*, b.customer_name from invoice a inner join customer b on a.customer_id = b.id where 1 ".$extra." order by b.customer_name";
switch($tab){
	case 'add':
		include("modules/invoice/add_do.php");
	break;
	case 'edit':
		include("modules/invoice/edit_do.php");
	break;
	case 'delete':
		include("modules/invoice/delete_do.php");
	break;
	case 'status':
		include("modules/invoice/status_do.php");
	break;
	case 'bulk_action':
		include("modules/invoice/bulkactions.php");
	break;
	case 'print':
		include("modules/invoice/print.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/invoice/list.php");
			break;
			case 'add':
				include("modules/invoice/add.php");
			break;
			case 'edit':
				include("modules/invoice/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>