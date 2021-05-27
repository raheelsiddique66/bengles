<?php
if(!defined("APP_START")) die("No Direct Access");
<<<<<<< HEAD
$sql = "SELECT a.*, group_concat(a.id)  as delivery_ids, b.balance, b.customer_name FROM `delivery` a left join customer b on a.customer_id = b.id  WHERE 1 $extra group by customer_id order by customer_name";
=======
$sql = "SELECT a.*, group_concat(a.id) as delivery_ids, b.balance, b.customer_name, b.customer_name_urdu, b.id as customerid FROM customer b left join delivery a on (a.customer_id = b.id) left join delivery_items c on a.id = c.delivery_id where 1 $extra ".(!empty($machine_id)?"and c.machine_id = '".$machine_id."'":"")." and b.status = 1 group by b.id order by customer_name";
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select * from color order by sortorder", $dblink);
while($r2=dofetch($rs2)){
	$rates = doquery("select distinct(unit_price) as rate from delivery_items where delivery_id in (select id from delivery where 1 $extra)".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and color_id = '".$r2["id"]."' order by unit_price", $dblink);
	foreach($rates as $rate){
<<<<<<< HEAD
        $colors[$r2["id"]][$rate["rate"]] = unslash($r2["title"]);
    }

}
?>
<style>
=======
        $colors[$r2["id"]][$rate["rate"]] = unslash($r2["title_urdu"]);
    }

}
//$sql = "SELECT a.*, group_concat(a.id) as delivery_ids, b.balance, b.customer_name, b.customer_name_urdu, b.id as customerid FROM customer b left join delivery a on (a.customer_id = b.id $extra) left join delivery_items c on a.id = c.delivery_id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by b.id order by customer_name";

?>
<style>
@font-face {
    font-family: 'NafeesRegular';
    src: url('fonts/NafeesRegular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;

}
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  14px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 14px;
<<<<<<< HEAD
	vertical-align:top;
=======
	vertical-align:middle;
    font-weight: bold;
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
.text-center{ text-align:center}
.text-left{ text-align:left}
.text-right{ text-align:right}
.bg-grey{ background:#ccc;}
<<<<<<< HEAD
=======
.total_col th{
    background-color:#2AB750; font-size: 18px
}
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
<tr class="head">
	<th colspan="18">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Summary</h2>
<<<<<<< HEAD
        <p>
        	<?php
			echo "List of Delivery of ";
			$all = true;
			if( !empty( $customer_id ) ){
				echo " Customer ".get_field($customer_id, "customer", "customer_name" )."<br>";
                $all = false;
			}
=======
        <p style=" font-size: 22px;background: #187bd0;padding: 5px;color:#fff;">
        	<?php
			echo "List of Delivery of ";
			$all = true;
            if( !empty( $customer_id ) ){
                ?>
                Customer <span class="nastaleeq"><?php echo get_field($customer_id, "customer", "customer_name_urdu" )."<br>";?></span>
                <?php
                $all = false;
            }
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
			if( !empty( $q ) ){
				echo " Gatepass ID ".$q."<br>";
                $all = false;
			}
			if($all){
			    echo " All Customers";
            }
            if( !empty( $date_from ) || !empty( $date_to ) ){
                echo "<br />Date";
            }
            if( !empty( $date_from ) ){
                echo " from ".$date_from;
            }
            if( !empty( $date_to ) ){
                echo " to ".$date_to."<br>";
            }
			?>
        </p>
    </th>
</tr>
<tr>
<<<<<<< HEAD
    <th width="2%" align="center">S.no</th>
	<th width="30%">Customer</th>
=======
<!--    <th width="10%">New Balance</th>-->
<!--    <th width="10%">Discount</th>-->
<!--    <th width="10%">Payment</th>-->
<!--    <th width="10%">Previous Amount</th>-->
    <th width="10%">Amount</th>
    <th width="10%" class="nastaleeq"> وائرس</th>
    <th width="10%">Total</th>
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            $colors_total[$color_id][$rate] = 0;
            ?>
<<<<<<< HEAD
            <th><?php echo $color_title."<br>@".$rate ?></th>
=======
            <th><span class="nastaleeq"><?php echo $color_title."<br>"?></span><?php echo "@".$rate ?></th>
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
            <?php
        }
    }
    ?>
<<<<<<< HEAD
    <th width="10%">Total</th>
    <th width="10%">Amount</th>
    <!-- <th width="10%">Previous Amount</th>
    <th width="10%">Payment</th>
    <th width="10%">Discount</th>
    <th width="10%">New Balance</th> -->
=======
    <th width="30%">Customer</th>
    <th width="2%" align="center">سیریل</th>
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
</tr>
</thead>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    $grand_total_quantity = 0;
	$grand_total_amount = 0;
    $total_balance = 0;
    $total_income = 0;
    $total_discount = 0;
<<<<<<< HEAD
	while( $r = dofetch( $rs ) ) {
        if(!empty($date_from)){
            $sql="select sum(amount) as amount from (select sum(unit_price * quantity) as amount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$r[ "customer_id" ]."'".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and date<'".date_dbconvert($date_from)."' union select -sum(amount) from customer_payment where customer_id = '".$r[ "customer_id" ]."' and datetime_added<='".date_dbconvert($date_from)." 00:00:00') as transactions ";
            $balance=dofetch(doquery($sql,$dblink));
            $balance = $r["balance"]+$balance[ "amount" ];
        }
        else{
            $balance = 0;
        }
        $sql="select sum(amount) as amount, sum(discount) as discount from customer_payment where customer_id = '".$r[ "customer_id" ]."'".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and datetime_added>='".date_dbconvert($date_from)." 00:00:00' and datetime_added<='".date_dbconvert($date_to)." 23:59:59'";
        $income1=dofetch(doquery($sql,$dblink));
        $income = $income1[ "amount" ];
        $discount = $income1[ "discount" ];
        $total_balance += $balance;
        $total_income += $income;
        $total_discount += $discount;
        ?>
		<tr>
        	<td align="center"><?php echo $sn?></td>
			<td><?php echo unslash($r["customer_name"]); ?></td>
			<?php
            $colors_delivery = [];
            $total_quantity = $total_amount = 0;
			$rs1 = doquery( "select color_id, unit_price, sum(quantity), sum(quantity*unit_price) as total from delivery_items where delivery_id in (".($r["delivery_ids"]).")".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." group by unit_price", $dblink );
			if(numrows($rs1)>0){
				while($r1=dofetch($rs1)){
                    if(!isset($colors_delivery[$r1["color_id"]][$r1["unit_price"]])){
                        $colors_delivery[$r1["color_id"]][$r1["unit_price"]] = 0;
                    }
				    $colors_delivery[$r1["color_id"]][$r1["unit_price"]] = $r1["sum(quantity)"];
                    $colors_total[$r1["color_id"]][$r1["unit_price"]] += $r1["sum(quantity)"];
                    $total_quantity += $r1["sum(quantity)"];
                    $total_amount += $r1["total"];
				}
			}
            $grand_total_quantity += $total_quantity;
            $grand_total_amount += $total_amount;
=======
    $total_claim = 0;
    $cus_balance = 0;
	while( $r = dofetch( $rs ) ) {
	    $balance = get_customer_balance($r["customerid"], date_dbconvert($date_from));
        $sql="select sum(amount) as amount, sum(discount) as discount, sum(claim) as claim from customer_payment where customer_id = '".$r[ "customerid" ]."'".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and datetime_added>='".date_dbconvert($date_from)." 00:00:00' and datetime_added<='".date_dbconvert($date_to)." 23:59:59'";
        $income1=dofetch(doquery($sql,$dblink));
        $income = $income1[ "amount" ];
        $discount = $income1[ "discount" ];
        $claim = $income1[ "claim" ];
        $total_balance += $balance;
        $total_income += $income;
        $total_discount += $discount;
        $total_claim += $claim;
        $colors_delivery = [];
        $total_quantity = $total_amount = 0;
        if(!empty($r["delivery_ids"])) {
            $rs1 = doquery("select color_id, unit_price, sum(quantity), sum(quantity*unit_price) as total from delivery_items where delivery_id in (" . ($r["delivery_ids"]) . ")" . (!empty($machine_id) ? " and machine_id = '" . $machine_id . "'" : "") . " group by color_id,unit_price", $dblink);
            if (numrows($rs1) > 0) {
                while ($r1 = dofetch($rs1)) {
                    if (!isset($colors_delivery[$r1["color_id"]][$r1["unit_price"]])) {
                        $colors_delivery[$r1["color_id"]][$r1["unit_price"]] = 0;
                    }
                    $colors_delivery[$r1["color_id"]][$r1["unit_price"]] = $r1["sum(quantity)"];
                    $colors_total[$r1["color_id"]][$r1["unit_price"]] += $r1["sum(quantity)"];
                    $total_quantity += $r1["sum(quantity)"];
                    $total_amount += $r1["total"];
                }
            }
        }
        $grand_total_quantity += $total_quantity;
        $grand_total_amount += $total_amount;
        ?>
		<tr>
<!--            <th class="text-right">--><?php //echo curr_format($total_amount+$balance-$income-$discount)?><!--</th>-->
<!--            <th class="text-right">--><?php //echo curr_format($discount)?><!--</th>-->
<!--            <th class="text-right">--><?php //echo curr_format($income)?><!--</th>-->
<!--            <th class="text-right">--><?php //echo curr_format($balance)?><!--</th>-->
            <th class="text-right"><?php echo curr_format($total_amount)?></th>
            <th class="text-right"><?php echo unslash($claim)?></th>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
            <?php

>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
            foreach($colors as $color_id => $color){
                foreach($color as $rate => $color_title) {
                    ?>
                    <td class="text-right"><?php echo isset($colors_delivery[$color_id][$rate]) ? curr_format($colors_delivery[$color_id][$rate]) : 0 ?></td>
                    <?php
                }
            }
<<<<<<< HEAD
			?>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
            <th class="text-right"><?php echo curr_format($total_amount)?></th>
            <!-- <th class="text-right"><?php echo curr_format($balance)?></th>
            <th class="text-right"><?php echo curr_format($income)?></th>
            <th class="text-right"><?php echo curr_format($discount)?></th>
            <th class="text-right"><?php echo curr_format($total_amount+$balance-$income-$discount)?></th> -->
=======
            ?>
            <td class="nastaleeq"><span style="margin-right: 10px;"><?php echo unslash($r["customer_name_urdu"]); ?></span></td>
        	<td align="center"><?php echo $sn?></td>
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
        </tr>
		<?php
		$sn++;
	}
}
?>
<<<<<<< HEAD
<tr>
	<td></td>
	<th class="text-right">Grand Total</th>
=======
<tr class="total_col">
<!--    <th class="text-right">--><?php //echo curr_format($grand_total_amount+$total_balance-$total_income-$total_discount)?><!--</th>-->
<!--    <th class="text-right">--><?php //echo curr_format($total_discount)?><!--</th>-->
<!--    <th class="text-right">--><?php //echo curr_format($total_income)?><!--</th>-->
<!--    <th class="text-right">--><?php //echo curr_format($total_balance)?><!--</th>-->
    <th class="text-right"><?php echo curr_format($grand_total_amount)?></th>
    <th class="text-right"><?php echo curr_format($total_claim)?></th>
    <th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>

>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            ?>
            <th class="text-right"><?php echo curr_format($colors_total[$color_id][$rate]) ?></th>
            <?php
        }
    }
    ?>
<<<<<<< HEAD
	<th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>
	<th class="text-right"><?php echo curr_format($grand_total_amount)?></th>
    <!-- <th class="text-right"><?php echo curr_format($total_balance)?></th>
    <th class="text-right"><?php echo curr_format($total_income)?></th>
    <th class="text-right"><?php echo curr_format($total_discount)?></th>
    <th class="text-right"><?php echo curr_format($grand_total_amount+$total_balance-$total_income-$total_discount)?></th> -->
=======
    <th class="text-right">Grand Total</th>
    <td></td>
>>>>>>> e65b70d97b0c6a1ea8b92013e2e502764304887d
</tr>
</table>
<?php
die;
