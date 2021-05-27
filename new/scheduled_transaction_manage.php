<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'scheduled_transaction_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
if(isset($_GET["project_id"])){
	$project_id=slash($_GET["project_id"]);
	$_SESSION["scheduled_transaction"]["list"]["project_id"]=$project_id;
}
switch($tab){
	case 'add':
		include("modules/scheduled_transaction/add_do.php");
	break;
	case 'edit':
		include("modules/scheduled_transaction/edit_do.php");
	break;
	case 'delete':
		include("modules/scheduled_transaction/delete_do.php");
	break;
	case 'status':
		include("modules/scheduled_transaction/status_do.php");
	break;
	case 'bulk_action':
		include("modules/scheduled_transaction/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/scheduled_transaction/list.php");
                break;
                case 'add':
                    include("modules/scheduled_transaction/add.php");
                break;
                case 'edit':
                    include("modules/scheduled_transaction/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>