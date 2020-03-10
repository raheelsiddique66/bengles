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
			<a class="btn print-btn" href="report_manage.php?tab=income_print"><i class="fa fa-print" aria-hidden="true"></i></a>
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
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off">
                </div>
                <span class="col-sm-1">Date To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off">
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
		$quantity = 0;
		$unit_price = 0;
		$payment_total = 0;
		$sql=doquery("select * from delivery a left join delivery_items b on a.id = b.delivery_id where status = 1 and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."' group by design_id,color_id",$dblink);
		if( numrows( $sql ) > 0 ) {
			while( $r1 = dofetch( $sql ) ) {
				//$quantity = $r1["quantity"];
				$unit_price = $r1["unit_price"];
				//echo $unit_price."<br>";
				//echo $r1["quantity"]."<br>";
			}
		}
		
		$q=dofetch(doquery("select sum(quantity) as quantity from delivery a left join delivery_items b on a.id = b.delivery_id where status = 1 and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'",$dblink));
		//echo $q["quantity"];
		//echo $unit_price;
		//$payment=dofetch($sql);	
		//print_r($payment);die;
		$payment_total += $unit_price*$q["quantity"];
		?>
        <tr class="head">
            <th class="text-right">Income from <?php echo $date_from?> to <?php echo $date_to?></th>
            <th class="text-right" >Rs. <?php echo $payment_total?></th>
        </tr>
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
                        <th class="text-right" >Rs.<?php echo curr_format($r[ "total" ])?></th>
                    </tr>	
                    <?php
				}
			}
		}
		?>
        <tr class="head">
            <th class="text-right">Total Expense</th>
            <th class="text-right" >Rs. <?php echo curr_format($total)?></th>
        </tr>
        <tr class="head bg-success">
            <th class="text-right">Net Income</th>
            <th class="text-right" >Rs. <?php echo $payment_total-$total?></th>
        </tr>	
  	</table>
</div>
