<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "SELECT a.*, group_concat(a.id) as delivery_ids FROM `delivery` a left join customer b on a.customer_id = b.id  WHERE 1 $extra group by customer_id order by customer_name";
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select * from color order by sortorder", $dblink);
while($r2=dofetch($rs2)){
	$rates = doquery("select distinct(unit_price) as rate from delivery_items where delivery_id in (select id from delivery where 1 $extra) and color_id = '".$r2["id"]."' order by unit_price", $dblink);
	foreach($rates as $rate){
        $colors[$r2["id"]][$rate["rate"]] = unslash($r2["title"]);
    }

}
?>
<style>
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
	vertical-align:top;
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
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
<tr class="head">
	<th colspan="18">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Summary</h2>
        <p>
        	<?php
			echo "List of Delivery of ";
			$all = true;
			if( !empty( $customer_id ) ){
				echo " Customer ".get_field($customer_id, "customer", "customer_name" )."<br>";
                $all = false;
			}
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
    <th width="2%" align="center">S.no</th>
	<th width="30%">Customer</th>
    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            $colors_total[$color_id][$rate] = 0;
            ?>
            <th><?php echo $color_title."<br>@".$rate ?></th>
            <?php
        }
    }
    ?>
    <th width="10%">Total</th>
    <th width="10%">Amount</th>
</tr>
</thead>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    $grand_total_quantity = 0;
	$grand_total_amount = 0;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
        	<td align="center"><?php echo $sn?></td>
			<td><?php echo get_field($r["customer_id"], "customer", "customer_name" ); ?></td>
			<?php
            $colors_delivery = [];
            $total_quantity = $total_amount = 0;
			$rs1 = doquery( "select color_id, unit_price, sum(quantity), sum(quantity*unit_price) as total from delivery_items where delivery_id in (".($r["delivery_ids"]).") group by unit_price", $dblink );
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
            foreach($colors as $color_id => $color){
                foreach($color as $rate => $color_title) {
                    ?>
                    <td class="text-right"><?php echo isset($colors_delivery[$color_id][$rate]) ? curr_format($colors_delivery[$color_id][$rate]) : 0 ?></td>
                    <?php
                }
            }
			?>
            <th class="text-right bg-grey"><?php echo curr_format($total_quantity)?></th>
            <th class="text-right bg-grey"><?php echo curr_format($total_amount)?></th>
        </tr>
		<?php
		$sn++;
	}
}
?>
<tr>
	<td></td>
	<th class="text-right bg-grey">Grand Total</th>
    <?php
    foreach($colors as $color_id => $color){
        foreach($color as $rate => $color_title) {
            ?>
            <th class="text-right"><?php echo curr_format($colors_total[$color_id][$rate]) ?></th>
            <?php
        }
    }
    ?>
	<th class="text-right bg-grey"><?php echo curr_format($grand_total_quantity)?></th>
	<th class="text-right bg-grey"><?php echo curr_format($grand_total_amount)?></th>
</tr>
</table>
<?php
die;
