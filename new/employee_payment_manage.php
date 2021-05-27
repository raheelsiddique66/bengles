<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_payment_manage.php';
include("include/admin_type_access.php");
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
if( isset($_GET["date_from"]) ){
	$_SESSION["employee_payment"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["employee_payment"]["list"]["date_from"]) && !empty($_SESSION["employee_payment"]["list"]["date_from"])){
	$date_from = $_SESSION["employee_payment"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["employee_payment"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["employee_payment"]["list"]["date_to"]) && !empty($_SESSION["employee_payment"]["list"]["date_to"])){
	$date_to = $_SESSION["employee_payment"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["employee_id"])){
	$employee_id=slash($_GET["employee_id"]);
	$_SESSION["employee_payment"]["list"]["employee_id"]=$employee_id;
}
if(isset($_SESSION["employee_payment"]["list"]["employee_id"]))
	$employee_id=$_SESSION["employee_payment"]["list"]["employee_id"];
else
	$employee_id="";
if($employee_id!=""){
	$extra.=" and employee_id='".$employee_id."'";
	$is_search=true;
}
if(isset($_GET["account_id"])){
	$account_id=slash($_GET["account_id"]);
	$_SESSION["employee_payment"]["list"]["account_id"]=$account_id;
}
if(isset($_SESSION["employee_payment"]["list"]["account_id"]))
	$account_id=$_SESSION["employee_payment"]["list"]["account_id"];
else
	$account_id="";
if($account_id!=""){
	$extra.=" and account_id='".$account_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["employee_payment"]["list"]["q"]=$q;
}
if(isset($_SESSION["employee_payment"]["list"]["q"]))
	$q=$_SESSION["employee_payment"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and id like '%".$q."%'";
	$is_search=true;
}
$sql="select * from employee_payment where 1 $extra";
switch($tab){
	case 'add':
		include("modules/employee_payment/add_do.php");
	break;
	case 'edit':
		include("modules/employee_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/employee_payment/delete_do.php");
	break;
	case 'status':
		include("modules/employee_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee_payment/bulkactions.php");
	break;
	case 'print':
		include("modules/employee_payment/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_payment/list.php");
                break;
                case 'add':
                    include("modules/employee_payment/add.php");
                break;
                case 'edit':
                    include("modules/employee_payment/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>