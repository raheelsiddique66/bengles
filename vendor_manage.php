<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'vendor_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report", "print", "print_ledger", "balance_report", "customer_dashboard", "combine_print");
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
    $_SESSION["vendor_manage"]["list"]["q"]=$q;
}
if(isset($_SESSION["vendor_manage"]["list"]["q"]))
    $q=$_SESSION["vendor_manage"]["list"]["q"];
else
    $q="";
if(!empty($q)){
    $extra.=" and vendor_name like '%".$q."%'";
    $is_search=true;
}
if(isset($_GET["machine_id"])){
    $machine_id=slash($_GET["machine_id"]);
    $_SESSION["vendor_manage"]["list"]["machine_id"]=$machine_id;
}
if(isset($_SESSION["vendor_manage"]["list"]["machine_id"]))
    $machine_id=$_SESSION["vendor_manage"]["list"]["machine_id"];
else
    $machine_id='';
if(!empty($machine_id) && !empty($machine_id[0])){
    $extra.=" and machine_id in (".implode(",",$machine_id).")";
    $is_search=true;
}
if( isset($_GET["date"]) ){
    $_SESSION["vendor_manage"]["list"]["date"] = $_GET["date"];
}
if(isset($_SESSION["vendor_manage"]["list"]["date"]) && !empty($_SESSION["vendor_manage"]["list"]["date"])){
    $date = $_SESSION["vendor_manage"]["list"]["date"];
}
else{
    $date = "";
}
if( !empty($date) ){
    $is_search=true;
}
$sql="select * from vendor where 1 $extra order by vendor_name";
switch($tab){
	case 'add':
		include("modules/vendor/add_do.php");
	break;
	case 'edit':
		include("modules/vendor/edit_do.php");
	break;
	case 'delete':
		include("modules/vendor/delete_do.php");
	break;
	case 'status':
		include("modules/vendor/status_do.php");
	break;
	case 'bulk_action':
		include("modules/vendor/bulkactions.php");
	break;
	case 'report':
		include("modules/vendor/report_do.php");
	break;
	case 'print':
		include("modules/vendor/print_do.php");
	break;
    case 'balance_report':
        include("modules/vendor/balance_report.php");
    break;
    case 'vendor_dashboard':
        include("modules/vendor/vendor_dashboard_do.php");
    break;
    case 'print_ledger':
        include("modules/vendor/print_ledger.php");
    break;
    case 'combine_print':
		include("modules/vendor/combine_print.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/vendor/list.php");
                break;
                case 'add':
                    include("modules/vendor/add.php");
                break;
                case 'edit':
                    include("modules/vendor/edit.php");
				break;
				case 'report':
					include("modules/vendor/report.php");
				break;
                case 'vendor_dashboard':
                    include("modules/vendor/vendor_dashboard.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>