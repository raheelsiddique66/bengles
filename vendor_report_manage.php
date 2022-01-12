<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'report_manage.php';
include("include/admin_type_access.php");
$tab_array=array("general_journal", "general_journal_print", "balance_sheet", "income", "income_print", "vendor_stock_report", "vendor_stock_report_print", "vendor_stock_total", "vendor_stock_total_print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="daily";
}
switch($tab){
	case 'vendor_stock_report_print':
		include("modules/vendor_reports/vendor_stock_report_print.php");
	break;
    case 'vendor_stock_total_print':
        include("modules/vendor_reports/vendor_stock_total_print.php");
    break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'vendor_stock_report':
				include("modules/vendor_reports/vendor_stock_report.php");
			break;
            case 'vendor_stock_total':
                include("modules/vendor_reports/vendor_stock_total.php");
            break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>