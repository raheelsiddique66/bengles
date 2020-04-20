<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_salary_manage.php';
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
	$_SESSION["employee_salary"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["employee_salary"]["list"]["date_from"]) && !empty($_SESSION["employee_salary"]["list"]["date_from"])){
	$date_from = $_SESSION["employee_salary"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["employee_salary"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["employee_salary"]["list"]["date_to"]) && !empty($_SESSION["employee_salary"]["list"]["date_to"])){
	$date_to = $_SESSION["employee_salary"]["list"]["date_to"];
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
	$_SESSION["employee_salary"]["list"]["employee_id"]=$employee_id;
}
if(isset($_SESSION["employee_salary"]["list"]["employee_id"]))
	$employee_id=$_SESSION["employee_salary"]["list"]["employee_id"];
else
	$employee_id="";
if($employee_id!=""){
	$extra.=" and employee_id='".$employee_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["employee_salary"]["list"]["q"]=$q;
}
if(isset($_SESSION["employee_salary"]["list"]["q"]))
	$q=$_SESSION["employee_salary"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and b.name like '%".$q."%' ";
	$is_search=true;
}
$sql="select a.*,b.name as name from employee_salary a inner join employees b on a.employee_id = b.id where 1 $extra order by date";

switch($tab){
	case 'add':
		include("modules/employee_salary/add_do.php");
	break;
	case 'edit':
		include("modules/employee_salary/edit_do.php");
	break;
	case 'delete':
		include("modules/employee_salary/delete_do.php");
	break;
	case 'status':
		include("modules/employee_salary/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee_salary/bulkactions.php");
	break;
	case 'print':
		include("modules/employee_salary/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_salary/list.php");
                break;
                case 'add':
                    include("modules/employee_salary/add.php");
                break;
                case 'edit':
                    include("modules/employee_salary/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>