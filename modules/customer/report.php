<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["start_date"])){
	$start_date=slash($_GET["start_date"]);
	$_SESSION["customer_manage"]["report"][ "start_date" ]=$start_date;
}
if(isset($_SESSION["customer_manage"]["report"][ "start_date" ]))
	$start_date=$_SESSION["customer_manage"]["report"][ "start_date" ];
else
	$start_date=date("01/m/Y");
if(!empty($start_date)){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00'";
	$is_search=true;
}
if(isset($_GET["end_date"])){
	$end_date=slash($_GET["end_date"]);
	$_SESSION["customer_manage"]["report"][ "end_date" ]=$end_date;
}
if(isset($_SESSION["customer_manage"]["report"][ "end_date" ]))
	$end_date=$_SESSION["customer_manage"]["report"][ "end_date" ];
else
	$end_date=date("d/m/Y");
if(!empty($end_date)){
	$extra.=" and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59'";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title"><?php echo $customer["customer_name"]?></h1>
  	<ol class="breadcrumb">
    	<li class="active"><?php echo $customer["phone"]?></li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="customer_manage.php?tab=list" class="btn btn-light editproject">Back to List</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
            <a class="btn print-btn" href="customer_manage.php?tab=print_ledger&id=<?php echo $customer['id'];?>"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div class="clearfix">
        	<form class="form-horizontal" action="" method="get">
                <input type="hidden" name="tab" value="report" />
                <input type="hidden" name="id" value="<?php echo $customer["id"]?>" />
                <div class="col-md-3">
                    <input type="text" title="Enter Date From" name="start_date" id="start_date" placeholder="" class="form-control date-picker"  value="<?php echo $start_date?>" autocomplete="off" />
                </div>
                <div class="col-md-3">
                    <input type="text" title="Enter Date To" name="end_date" id="end_date" placeholder="" class="form-control date-picker"  value="<?php echo $end_date?>" autocomplete="off" />
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
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th>Date</th>
                <th>Transaction</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Balance</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
           
            // $sql="select sum(amount) as amount from (select concat( 'Delivery #', a.gatepass_id) as transaction, unit_price * quantity as amount, 0 as discount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$customer[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' union select concat( 'Payment #', id) as transaction, -amount, -discount from customer_payment where customer_id = '".$customer[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59') as transactions ";
			// $balance=dofetch(doquery($sql,$dblink));
			$balance = get_customer_balance($customer['id'], date_dbconvert($start_date));
			$sql="select concat( 'Delivery # ', a.gatepass_id, ' - ', c.title, ' - ', d.title, ' - ', e.title, ' - ', b.unit_price) as transaction, date as datetime_added, unit_price * sum(quantity) as amount, sum(quantity) as quantity, 0 as discount from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id left join design d on b.design_id = d.id left join machine e on b.machine_id = e.id where customer_id = '".$customer[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' group by a.id union select concat( 'Payment ', b.title) as transaction, datetime_added as datetime_added, -amount-discount as amount, '', -discount from customer_payment a left join machine b on a.machine_id = b.id where customer_id = '".$customer[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' order by datetime_added asc";
            $rs=show_page($rows, $pageNum, $sql);
            ?>
			<tr>
                <td class="text-right" colspan="6"><strong>Opening Balance</strong></td>
                <th class="text-right"><?php echo curr_format($balance); ?></th>
            </tr>
			<?php
			if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){             
					$balance+=$r["amount"];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td><?php echo unslash($r["transaction"]); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                        <td class="text-right"><?php echo curr_format($r["amount"]); ?></td>
                        <td class="text-right"><?php echo curr_format($r["discount"]); ?></td>
                        <td class="text-right"><?php echo curr_format($balance); ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="7" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "customer", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="7"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
