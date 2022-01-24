<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "SELECT a.*, group_concat(a.id) as vendor_delivery_ids, b.balance, b.vendor_name, b.vendor_name_urdu, b.id as vendorid FROM vendor b left join vendor_delivery a on (a.vendor_id = b.id $extra) left join vendor_delivery_items c on a.id = c.vendor_delivery_id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by b.id order by vendor_name";
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select * from color where status = 1 order by sortorder", $dblink);
while($r2=dofetch($rs2)){
	$rates = doquery("select distinct(unit_price) as rate from vendor_delivery_items where vendor_delivery_id in (select id from vendor_delivery where 1 $extra)".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and color_id = '".$r2["id"]."' order by unit_price", $dblink);
	foreach($rates as $rate){
        $colors[$r2["id"]][$rate["rate"]] = unslash($r2["title_urdu"]);
    }

}
?>
<style>
@font-face {
    font-family: 'NafeesRegular';
    src: url('fonts/NafeesRegular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;

}
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
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
	vertical-align:middle;
    font-weight: bold;
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
.total_col th{
    background-color:#2AB750; font-size: 18px
}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
<tr class="head">
	<th colspan="18">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Summary</h2>
        <p style=" font-size: 22px;background: #187bd0;padding: 5px;color:#fff;">
        	<?php
			echo "List of Vendor Delivery of ";
			$all = true;
            if( !empty( $vendor_id ) ){
                ?>
                Vendor <span class="nastaleeq"><?php echo get_field($vendor_id, "vendor", "vendor_name_urdu" )."<br>";?></span>
                <?php
                $all = false;
            }
			if( !empty( $q ) ){
				echo " Gatepass ID ".$q."<br>";
                $all = false;
			}
			if($all){
			    echo " All Vendors";
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
    <th width="10%">New Balance</th>
    <th width="10%">Discount</th>
    <th width="10%">Payment</th>
    <th width="10%">Previous Amount</th>
    <th width="10%">Amount</th>
    <?php if($_SERVER['SERVER_NAME'] != 'goldenglass.burhanpk.com'){?><th width="10%" class="nastaleeq"> وائرس</th><?php }?>
    <th width="10%">Total</th>
    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            $colors_total[$color_id][$rate] = 0;
            ?>
            <th><span class="nastaleeq"><?php echo $color_title."<br>"?></span><?php echo "@".$rate ?></th>
            <?php
        }
    }
    ?>
    <th width="30%">Vendor</th>
    <th width="2%" align="center">سیریل</th>
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
    $total_claim = 0;
    $cus_balance = 0;
	while( $r = dofetch( $rs ) ) {
	    $balance = get_vendor_balance($r["vendorid"], date_dbconvert($date_from));
        $sql="select sum(amount) as amount, sum(discount) as discount, sum(claim) as claim from vendor_payment where vendor_id = '".$r[ "vendorid" ]."'".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." and datetime_added>='".date_dbconvert($date_from)."' and datetime_added<='".date_dbconvert($date_to)."'";
        $income1=dofetch(doquery($sql,$dblink));
        $income = $income1[ "amount" ];
        $discount = $income1[ "discount" ];
        $claim = $income1[ "claim" ];
        $total_balance += $balance;
        $total_income += $income;
        $total_discount += $discount;
        $total_claim += $claim;
        $colors_vendor_delivery = [];
        $total_quantity = $total_amount = 0;
        if(!empty($r["vendor_delivery_ids"])) {
            $rs1 = doquery("select color_id, unit_price, sum(quantity), sum(quantity*unit_price) as total from vendor_delivery_items where vendor_delivery_id in (" . ($r["vendor_delivery_ids"]) . ")" . (!empty($machine_id) ? " and machine_id = '" . $machine_id . "'" : "") . " group by color_id,unit_price", $dblink);
            if (numrows($rs1) > 0) {
                while ($r1 = dofetch($rs1)) {
                    if (!isset($colors_vendor_delivery[$r1["color_id"]][$r1["unit_price"]])) {
                        $colors_vendor_delivery[$r1["color_id"]][$r1["unit_price"]] = 0;
                    }
                    $colors_vendor_delivery[$r1["color_id"]][$r1["unit_price"]] = $r1["sum(quantity)"];
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
            <th class="text-right"><?php echo curr_format($total_amount+$balance-$income-$discount)?></th>
            <th class="text-right"><?php echo curr_format($discount)?></th>
            <th class="text-right"><?php echo curr_format($income)?></th>
            <th class="text-right"><?php echo curr_format($balance)?></th>
            <th class="text-right"><?php echo curr_format($total_amount)?></th>
            <?php if($_SERVER['SERVER_NAME'] != 'goldenglass.burhanpk.com'){?><th class="text-right"><?php echo unslash($claim)?></th><?php }?>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
            <?php

            foreach($colors as $color_id => $color){
                foreach($color as $rate => $color_title) {
                    ?>
                    <td class="text-right"><?php echo isset($colors_vendor_delivery[$color_id][$rate]) ? curr_format($colors_vendor_delivery[$color_id][$rate]) : 0 ?></td>
                    <?php
                }
            }
            ?>
            <td class="nastaleeq"><span style="margin-right: 10px;"><?php echo unslash($r["vendor_name_urdu"]); ?></span></td>
        	<td align="center"><?php echo $sn?></td>
        </tr>
		<?php
		$sn++;
	}
}
?>
<tr class="total_col">
    <th class="text-right"><?php echo curr_format($grand_total_amount+$total_balance-$total_income-$total_discount)?></th>
    <th class="text-right"><?php echo curr_format($total_discount)?></th>
    <th class="text-right"><?php echo curr_format($total_income)?></th>
    <th class="text-right"><?php echo curr_format($total_balance)?></th>
    <th class="text-right"><?php echo curr_format($grand_total_amount)?></th>
    <?php if($_SERVER['SERVER_NAME'] != 'goldenglass.burhanpk.com'){?><th class="text-right"><?php echo curr_format($total_claim)?></th><?php }?>
    <th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>

    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            ?>
            <th class="text-right"><?php echo curr_format($colors_total[$color_id][$rate]) ?></th>
            <?php
        }
    }
    ?>
    <th class="text-right">Grand Total</th>
    <td></td>
</tr>
</table>
<?php
die;
