<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["start_date"])){
    $start_date=slash($_GET["start_date"]);
    $_SESSION["vendor_manage"]["report"][ "start_date" ]=$start_date;
}
if(isset($_SESSION["vendor_manage"]["report"][ "start_date" ]))
    $start_date=$_SESSION["vendor_manage"]["report"][ "start_date" ];
else
    $start_date=date("01/m/Y");
if(!empty($start_date)){
    $extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00'";
    $is_search=true;
}
if(isset($_GET["end_date"])){
    $end_date=slash($_GET["end_date"]);
    $_SESSION["vendor_manage"]["report"][ "end_date" ]=$end_date;
}
if(isset($_SESSION["vendor_manage"]["report"][ "end_date" ]))
    $end_date=$_SESSION["vendor_manage"]["report"][ "end_date" ];
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
	<th colspan="9">
    	<h2>Vendor Ledger</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $date_from ) || !empty( $date_to ) ){
				echo "<br />Date";
			}
			if( !empty( $date_from ) ){
				echo " from ".$date_from;
			}
			if( !empty( $date_to ) ){
				echo " to ".$date_to;
			}

			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th>Date</th>
    <th>Transaction</th>
    <th class="right">Quantity</th>
    <th align="right">Amount</th>
    <th align="right">Balance</th>
</tr>
<?php 
if( !empty( $id ) ){
	$balance = get_vendor_balance( $vendor[ "id" ], date_dbconvert( $date_to ) );
	$sn=1;
	?>
	<tr>
		<td align="center"><?php echo $sn++;?></td>
		<td><?php echo $date_to; ?></td>
		<td>Closing Balance</td>
		<td align="right">--</td>
		<td align="right"><?php echo curr_format($balance); ?></td>
	</tr>
	<?php
    $sql="select concat( 'Delivery # ', a.gatepass_id, ' ', c.title, ' ', d.title) as transaction, date as datetime_added, unit_price * sum(quantity) as amount, sum(quantity) as quantity from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id left join design d on b.design_id = d.id where vendor_id = '".$vendor[ "id" ]."' and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' group by a.id union select 'Payment', datetime_added as datetime_added, -amount, '' from vendor_payment where vendor_id = '".$vendor[ "id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59' order by datetime_added desc";
	$rs=doquery($sql,$dblink);
	if(numrows($rs)>0){
		while($r=dofetch($rs)){
			?>
			<tr>
				<td align="center"><?php echo $sn;?></td>
				<td><?php echo datetime_convert($r["date"]); ?></td>
				<td><?php echo unslash($r["transaction"]); ?></td>
                <td align="right"><?php echo $r["quantity"]; ?></td>
				<td align="right"><?php echo curr_format($r["amount"]); ?></td>
				<td align="right"><?php echo curr_format($balance); ?></td>
			</tr>
			<?php 
			$sn++;
			$balance = $balance - $r["amount"];
		}
		?>
		<tr>
			<td align="center"><?php echo $sn++;?></td>
			<td><?php echo $date_from; ?></td>
			<td>Opening Balance</td>
			<td align="right">--</td>
			<td align="right"><?php echo curr_format($balance); ?></td>
		</tr>
		<?php
	}
	else{	
		?>
		<tr>
			<td colspan="5"  class="no-record">No Result Found</td>
		</tr>
		<?php
	}
}
else {
	?>
	<tr>
		<td colspan="5"  class="no-record">Select Vendor from above dropdown</td>
	</tr>
	<?php
}
?>
</table>
<?php
die;