<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'customer_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report", "print", "balance_report", "customer_dashboard");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["q"])){
    $q=slash($_GET["q"]);
    $_SESSION["customer_manage"]["list"]["q"]=$q;
}
if(isset($_SESSION["customer_manage"]["list"]["q"]))
    $q=$_SESSION["customer_manage"]["list"]["q"];
else
    $q="";
if(!empty($q)){
    $extra.=" and customer_name like '%".$q."%'";
    $is_search=true;
}
if(isset($_GET["machine_id"])){
    $machine_id=slash($_GET["machine_id"]);
    $_SESSION["customer_manage"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["customer_manage"]["list"]["machine_id"]))
    $machine_id=$_SESSION["customer_manage"]["list"]["machine_id"];
else
    $machine_id='';
if($machine_id!=""){
    $extra.=" and machine_id='".$machine_id."'";
    $is_search=true;
}
if( isset($_GET["date"]) ){
    $_SESSION["customer_manage"]["list"]["date"] = $_GET["date"];
}
if(isset($_SESSION["customer_manage"]["list"]["date"]) && !empty($_SESSION["customer_manage"]["list"]["date"])){
    $date = $_SESSION["customer_manage"]["list"]["date"];
}
else{
    $date = "";
}
if( !empty($date) ){
    $is_search=true;
}
$sql="select * from customer where 1 $extra order by customer_name";
switch($tab){
	case 'add':
		include("modules/customer/add_do.php");
	break;
	case 'edit':
		include("modules/customer/edit_do.php");
	break;
	case 'delete':
		include("modules/customer/delete_do.php");
	break;
	case 'status':
		include("modules/customer/status_do.php");
	break;
	case 'bulk_action':
		include("modules/customer/bulkactions.php");
	break;
	case 'report':
		include("modules/customer/report_do.php");
	break;
	case 'print':
		include("modules/customer/print_do.php");
	break;
    case 'balance_report':
        include("modules/customer/balance_report.php");
    break;
    case 'customer_dashboard':
        include("modules/customer/customer_dashboard_do.php");
    break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/customer/list.php");
                break;
                case 'add':
                    include("modules/customer/add.php");
                break;
                case 'edit':
                    include("modules/customer/edit.php");
				break;
				case 'report':
					include("modules/customer/report.php");
				break;
                case 'customer_dashboard':
                    include("modules/customer/customer_dashboard.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>