<?php
if(!defined("APP_START")) die("No Direct Access");

$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
    $date_from=slash($_GET["date_from"]);
    $_SESSION["employee_report"]["employee_manage"]["date_from"]=$date_from;
}
if(isset($_SESSION["employee_report"]["employee_manage"]["date_from"]))
    $date_from=$_SESSION["employee_report"]["employee_manage"]["date_from"];
else
    $date_from=date("01/m/Y");
if(isset($_GET["date_to"])){
    $date_to=slash($_GET["date_to"]);
    $_SESSION["employee_report"]["employee_manage"]["date_to"]=$date_to;
}
if(isset($_SESSION["employee_report"]["employee_manage"]["date_to"]))
    $date_to=$_SESSION["employee_report"]["employee_manage"]["date_to"];
else
    $date_to=date("d/m/Y");
if( !empty( $date_from ) && !empty( $date_from ) ) {
    $extra.=" and a.date BETWEEN '".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00' AND '".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}


$order_by = " date";
$order = "desc";

$orderby = $order_by." ".$order;
$sql = "SELECT a.calculated_salary, a.date, b.amount FROM `employee_salary` a inner join employee_payment b on a.employee_id = b.employee_id where a.employee_id=".$_GET['employee_id']." $extra order by $order_by $order";

?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="employee_manage.php?tab=general_journal_print"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="employee_report" />
                <input type="hidden" name="employee_id" value="<?php echo $_GET["employee_id"]?>" />
                <span class="col-sm-1 text-to">From</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" >
                </div>
                <span class="col-sm-1 text-to">To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" >
                </div>                
                <div class="col-sm-3 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="20%">
                	<a href="employee_manage.php?tab=employee_report&order_by=date&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Date
                        <?php
						if( $order_by == "date" ) {
							?>
							<span class="sort-icon">
								<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
							</span>
							<?php
						}
						?>
                  	</a>
                </th>
                <th width="30%">Employee Salary</th>
                <th width="30%">Employee Payment</th>
            </tr>
    	</thead>
    	<tbody>
            
                
             <?php
             $rs=doquery( $sql, $dblink );
             if(numrows($rs)>0){
                while($r=dofetch($rs)){

             ?>
                 <tr>
                    <td><?php echo date_convert($r['date']); ?></td>
                     <td><?php echo $r['calculated_salary']; ?></td>
                     <td><?php echo $r['amount']; ?></td>
<!--                --><?php
//                $sql2 = "SELECT * FROM `employee_salary` where employee_id='".$_GET['employee_id']."' $extra";
//                    $rs2=doquery( $sql2, $dblink );
//                    if(numrows($rs2)>0){
//                        while($r2=dofetch($rs2)){
//                
//                ?>
<!--                            <td class="text-center">--><?php //echo curr_format($r2["calculated_salary"]); ?><!--</td><br>-->
<!--                --><?php //}} ?>
<!--                -->
<!--                --><?php
//                $sql3 = "SELECT * FROM `employee_payment` where employee_id='".$_GET['employee_id']."' $extra";
//                $rs3=doquery( $sql3, $dblink );
//                if(numrows($rs3)>0){
//                    while($r3=dofetch($rs3)){
//            
//                        ?>
<!--                            <td class="text-center">--><?php //echo curr_format($r3["amount"]); ?><!--</td><br>-->
<!--                    --><?php //}} ?>
                
                </tr>
             <?php }} ?>
                	
            
<!--                <tr>-->
<!--                    <td colspan="6"  class="no-record">No Result Found</td>-->
<!--                </tr>-->
                
    	</tbody>
  	</table>
</div>
