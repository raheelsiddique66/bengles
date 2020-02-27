<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "salary", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/employee/add_do.php");
	break;
	case 'edit':
		include("modules/employee/edit_do.php");
	break;
	case 'delete':
		include("modules/employee/delete_do.php");
	break;
	case 'status':
		include("modules/employee/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee/bulkactions.php");
	break;
	case 'salary':
		include("modules/employee/salary_do.php");
	break;
    case 'report':
        include("modules/employee/report.php");
        die;
    break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee/list.php");
                break;
                case 'add':
                    include("modules/employee/add.php");
                break;
                case 'edit':
                    include("modules/employee/edit.php");
				break;
				case 'salary':
					include("modules/employee/salary.php");
				break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>