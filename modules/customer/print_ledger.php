<?php
if(!defined("APP_START")) die("No Direct Access");
$customer=dofetch(doquery("select * from customer where id = '".$_GET["id"]."' order by customer_name", $dblink));
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
<style>
    h1, h2, h3, p {
        margin: 0 0 10px;
    }

    body {
        margin:  0;
        font-family:  Arial;
        font-size:  11px;
    }
    .head th, .head td{ border:0;}
    th, td {
        border: solid 1px #000;
        padding: 5px 5px;
        font-size: 11px;
        vertical-align:top;
    }
    table table th, table table td{
        padding:3px;
    }
    table {
        border-collapse:collapse;
        max-width:1200px;
        margin:0 auto;
    }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="10">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Customer Ledger</h2>
        <p style="font-size: 22px;background: #187bd0;padding: 5px;">
        	<?php
			echo "List of";
            if( !empty( $start_date ) || !empty( $end_date ) ){
                echo "<br />Date";
            }
            if( !empty( $start_date ) ){
                echo " from ".$start_date;
            }
            if( !empty( $end_date ) ){
                echo " to ".$end_date."<br>";
            }
            if( !empty( $customer["id"] ) ){
                ?>
                Customer:  <span class="nastaleeq"><?php echo get_field($customer["id"], "customer", "customer_name_urdu" )."<br>";?></span>
                <?php
            }
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" class="text-center">S.no</th>
    <th>Date</th>
    <th>Transaction</th>
    <th align="right">Quantity</th>
    <th align="right">Amount</th>
    <th align="right">Discount</th>
    <th align="right">Balance</th>
</tr>
<?php
            // $sql="select sum(amount) as amount from (select concat( 'Delivery #', a.gatepass_id) as transaction, unit_price * quantity as amount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$customer[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' union select concat( 'Payment #', id) as transaction, -amount, -discount from customer_payment where customer_id = '".$customer[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59') as transactions ";
			// $balance=dofetch(doquery($sql,$dblink));
			$balance = get_customer_balance($customer['id'], date_dbconvert($start_date));
			//$sql="select concat( 'Delivery # ', a.gatepass_id, ' - ', c.title, ' - ', d.title) as transaction, date as datetime_added, unit_price * sum(quantity) as amount, sum(quantity) as quantity from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id left join design d on b.design_id = d.id where customer_id = '".$customer[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' group by a.id union select 'Payment', datetime_added as datetime_added, -amount, '' from customer_payment where customer_id = '".$customer[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' order by datetime_added desc";
            $sql="select concat( 'Delivery # ', a.gatepass_id, ' - ', c.title, ' - ', d.title, ' - ', e.title, ' - ', b.unit_price) as transaction, date as datetime_added, unit_price * sum(quantity) as amount, sum(quantity) as quantity, 0 as discount from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id left join design d on b.design_id = d.id left join machine e on b.machine_id = e.id where customer_id = '".$customer[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' group by a.id union select concat( 'Payment ', b.title) as transaction, datetime_added as datetime_added, -amount-discount as amount, '', -discount from customer_payment a left join machine b on a.machine_id = b.id where customer_id = '".$customer[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' order by datetime_added asc";
            $rs=show_page($rows, $pageNum, $sql);
            ?>
			<tr>
                <td align="right" colspan="6"><strong>Opening Balance</strong></td>
                <th align="right"><?php echo curr_format($balance); ?></th>
            </tr>
			<?php
            $total_quantity = $total_amount = $total_discount = $total_balance = 0;
			if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){
					$balance+=$r["amount"];
                    $total_quantity+=$r["quantity"];
                    $total_amount+=$r["amount"];
                    $total_discount+=$r["discount"];
                    $total_balance+=$balance;
                    ?>
                    <tr>
                        <td align="center"><?php echo $sn;?></td>
                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td><?php echo unslash($r["transaction"]); ?></td>
                        <td align="right"><?php echo $r["quantity"]; ?></td>
                        <td align="right"><?php echo curr_format($r["amount"]); ?></td>
                        <td align="right"><?php echo curr_format($r["discount"]); ?></td>
                        <td align="right"><?php echo curr_format($balance); ?></td>
                    </tr>
                    <?php
                    $sn++;
                }
                ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?php echo $total_quantity;?></th>
                    <th><?php echo curr_format($total_amount);?></th>
                    <th><?php echo curr_format($total_discount);?></th>
                    <th><?php echo curr_format($total_balance);?></th>
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

</table>
<?php
die;
