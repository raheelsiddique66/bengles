<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["income"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["income"]["date_from"]))
	$date_from=$_SESSION["reports"]["income"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["income"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["income"]["date_to"]))
	$date_to=$_SESSION["reports"]["income"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and datetime_added<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if( empty( $extra ) ) {
	$extra = ' and 1=0 ';
}
?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Income Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div class="">
        	<form class="form-horizontal" action="" method="get">
                <input type="hidden" name="tab" value="income" />
                <span class="col-sm-1">Date From</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>">
                </div>
                <span class="col-sm-1">Date To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>">
                </div>
                
                <div class="col-sm-2 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<?php
		//$sql="select sum(amount) as total from project_payment where status = 1 $extra";
		//$project_payment=dofetch(doquery($sql, $dblink));
		?>
        <!--<tr class="head">
            <th class="text-right">Income from <?php echo $date_from?> to <?php echo $date_to?></th>
            <th class="text-right" ><?php echo curr_format($project_payment[ "total" ])?></th>
        </tr>-->
        
        <!--<tr class="bg-success">
            <th class="text-right">Revenue <?php echo $date_from?> to <?php echo $date_to?></th>
            <th class="text-right" ><?php echo curr_format($sale_total[ "total" ]-$purchase_total[ "total" ]-$sale_return_total[ "total" ]+$purchase_return_total[ "total" ])?></th>
        </tr>-->
        <?php
		$total = 0;
        $rs = doquery( "select title, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 $extra group by expense_category_id", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				if( $r[ "total" ] > 0 ){
					$total += $r[ "total" ];
					?>
                    <tr class="head">
                        <th class="text-right"><?php echo unslash( $r[ "title" ] )?></th>
                        <th class="text-right" ><?php echo curr_format($r[ "total" ])?></th>
                    </tr>	
                    <?php
				}
			}
		}
		//$rs = dofetch( doquery( "select sum(amount) as total from salary where status=1 and (month>='".date('m',strtotime(date_dbconvert($date_from)))."' and month<='".date('m',strtotime(date_dbconvert($date_to)))."' and year='".date('Y',strtotime(date_dbconvert($date_from)))."' or month<='".date('m',strtotime(date_dbconvert($date_to)))."' and year>'".date('Y',strtotime(date_dbconvert($date_from)))."' and year<='".date('Y',strtotime(date_dbconvert($date_to)))."')", $dblink ) );
		?>
        <tr class="head">
            <th class="text-right">Salary</th>
            <th class="text-right" ><?php // echo curr_format($rs[ "total" ])?></th>
        </tr>
         <tr class="head">
            <th class="text-right">Total Expense</th>
            <th class="text-right" ><?php echo curr_format($total)?></th>
        </tr>
        <tr class="head bg-success">
            <th class="text-right">Net Income</th>
            <th class="text-right" ><?php // echo curr_format($project_payment[ "total" ]-$total-$rs[ "total" ])?></th>
        </tr>	
  	</table>
</div>
