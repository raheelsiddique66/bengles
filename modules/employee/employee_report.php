<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs1=doquery("select * from employees where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs1)>0){
		$employee=dofetch($rs1);		
    }
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["employee_manage"]["report"][ "date_from" ]=$date_from;
}
if(isset($_SESSION["employee_manage"]["report"][ "date_from" ]))
	$date_from=$_SESSION["employee_manage"]["report"][ "date_from" ];
else
	$date_from=date("01/m/Y");
if(!empty($date_from)){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
	$is_search=true;
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["employee_manage"]["report"][ "date_to" ]=$date_to;
}
if(isset($_SESSION["employee_manage"]["report"][ "date_to" ]))
	$date_to=$_SESSION["employee_manage"]["report"][ "date_to" ];
else
	$date_to=date("d/m/Y");
if(!empty($date_to)){
	$extra.=" and date<'".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
	$is_search=true;
}
//$sql = "SELECT a.calculated_salary, a.date, b.amount FROM `employee_salary` a inner join employee_payment b on a.employee_id = b.employee_id where a.employee_id=".$_GET['employee_id']." $extra order by $order_by $order";
?>
<div class="page-header">
	<h1 class="title"><?php echo $employee["name"]?></h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
            <a href="employee_manage.php?tab=list" class="btn btn-light editproject">Back to List</a> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="employee_report" />
                <input type="hidden" name="id" value="<?php echo $employee["id"]?>" />
                <div class="col-md-3">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="Date from" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off" />
                </div>
                <div class="col-md-3">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="Date to" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off" />
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
                <th width="5%" class="text-center">S.no</th>
                <th width="20%">Date </th>
                <th width="30%">Details</th>
                <th width="30%">Amount</th>
            </tr>
    	</thead>
    	<tbody>
            <tr>
                <td class="text-right" colspan="3"><strong>Balance</strong></td>
                <td class="text-right"><?php echo get_employee_balance( $employee["id"], "") ?></td>
            </tr>
            <?php
            $sql="select concat( 'Salary #', id) as details, date, calculated_salary as amount from employee_salary where employee_id = '".$employee[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59' union select 'Payment', date, amount from employee_payment where employee_id = '".$employee[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59' order by date desc";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){
                ?>
                 <tr>
                    <td class="text-center"><?php echo $sn;?></td>
                    <td><?php echo date_convert($r['date']); ?></td>
                    <td><?php echo $r['details']; ?></td>
                    <td><?php echo $r['amount']; ?></td>
                </tr>
                <?php 
                $sn++;
                }
            } 
            else{	
                ?>
                <tr>
                    <td colspan="4"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
