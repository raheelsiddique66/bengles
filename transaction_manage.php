<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'transaction_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$extra='';
$is_search=false;
if( isset($_GET["date_from"]) ){
    $_SESSION["transaction"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["transaction"]["list"]["date_from"]) && !empty($_SESSION["transaction"]["list"]["date_from"])){
    $date_from = $_SESSION["transaction"]["list"]["date_from"];
}
else{
    $date_from = "";
}
if( !empty($date_from) ){
    $extra.=" and datetime_added>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
    $is_search=true;
}
if( isset($_GET["date_to"]) ){
    $_SESSION["transaction"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["transaction"]["list"]["date_to"]) && !empty($_SESSION["transaction"]["list"]["date_to"])){
    $date_to = $_SESSION["transaction"]["list"]["date_to"];
}
else{
    $date_to = "";
}
if( !empty($date_to) ){
    $extra.=" and datetime_added<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
    $is_search=true;
}
if(isset($_GET["reference_id"])){
    $reference_id=slash($_GET["reference_id"]);
    $_SESSION["transaction"]["list"]["reference_id"]=$reference_id;
}
$acount_extra = array();
if(isset($_SESSION["transaction"]["list"]["reference_id"]))
    $reference_id=$_SESSION["transaction"]["list"]["reference_id"];
else
    $reference_id="";
if($reference_id!=""){
    $acount_extra[]="reference_id='".$reference_id."'";
    $is_search=true;
}
if(isset($_GET["account_id"])){
    $account_id=slash($_GET["account_id"]);
    $_SESSION["transaction"]["list"]["account_id"]=$account_id;
}
if(isset($_SESSION["transaction"]["list"]["account_id"]))
    $account_id=$_SESSION["transaction"]["list"]["account_id"];
else
    $account_id="";
if($account_id!=""){
    $acount_extra[]="account_id='".$account_id."'";
    $is_search=true;
}
if( count($acount_extra) > 0 ){
    $extra.=" and (".implode(" or ", $acount_extra ).")";
}
$sql="select * from transaction where 1 $extra order by datetime_added desc";
switch($tab){
	case 'add':
		include("modules/transaction/add_do.php");
	break;
	case 'edit':
		include("modules/transaction/edit_do.php");
	break;
	case 'delete':
		include("modules/transaction/delete_do.php");
	break;
	case 'status':
		include("modules/transaction/status_do.php");
	break;
	case 'bulk_action':
		include("modules/transaction/bulkactions.php");
	break;
	case 'report':
		include("modules/transaction/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/transaction/list.php");
                break;
                case 'add':
                    include("modules/transaction/add.php");
                break;
                case 'edit':
                    include("modules/transaction/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>