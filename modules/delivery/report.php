<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select * from color order by sortorder", $dblink);
while($r2=dofetch($rs2)){
	$colors[$r2["id"]] = unslash($r2["title"]);
}
$designs = [];
$rs3 = doquery("select * from design order by sortorder", $dblink);
while($r3=dofetch($rs3)){
	$designs[$r3["id"]] = unslash($r3["title"]);
}
$sizes = [];
$rs4 = doquery("select * from size order by sortorder", $dblink);
while($r4=dofetch($rs4)){
	$sizes[$r4["id"]] = unslash($r4["title"]);
}
$grand_totals = [];
foreach($sizes as $size_id => $size){
	$grand_totals[$size_id] = 0;
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
    	<h2>Delivery List</h2>
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
				echo " to ".$date_to."<br>";
			}
			if( !empty( $customer_id ) ){
				echo " Customer ".get_field($customer_id, "customer", "customer_name" )."<br>";
			}
			if( !empty( $q ) ){
				echo "Gatepass ID ".$q."<br>";
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="2%" align="center" rowspan="2">S.no</th>
	<th width="8%" rowspan="2">Date</th>
    <th width="8%" rowspan="2">Gatepass ID</th>
	<?php if(empty( $customer_id ) ){?>
	<th width="10%" rowspan="2">Customer</th>
	<?php }?>
	<th width="8%" rowspan="2">Claim</th>
	<th width="10%" rowspan="2">Labour</th>
	<th width="60%" colspan="<?php echo count($sizes)+5?>">Items</th>
</tr>
<tr>
	<td>Color</td>
	<td>Design</td>
	<?php
	foreach($sizes as $size){
		?>
		<th class="text-center"><?php echo $size;?></th>
		<?php  
	}
	?>
	<th class="bg-grey text-center">Total</th>
	<th class="text-center">Price</th>
	<th class="bg-grey text-center">Total Price</th>
</tr>
</thead>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
        	<td align="center"><?php echo $sn?></td>
			<td><?php echo date_convert($r["date"]); ?></td>
			<td align="center"><?php echo $r["gatepass_id"]; ?></td>
			<?php if(empty( $customer_id ) ){?>
			<td><?php echo get_field($r["customer_id"], "customer", "customer_name" ); ?></td>
			<?php }?>
			<td><?php echo  unslash($r["claim"]); ?></td>
			<td><?php echo get_field($r["labour_id"], "labour", "name" ); ?></td>
					<?php
					$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from delivery_items where delivery_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
					if(numrows($rs1)>0){
						$price = 0;
						$total_price = 0;
						$final_price = 0;
						$final_total_price = 0;
						$totals = [];
						foreach($sizes as $size_id => $size){
							$totals[$size_id] = 0;
						}
						$cnt = 0;
						while($r1=dofetch($rs1)){
							$price += $r1["unit_price"];
							
							$cnt++;
							if($cnt>1){
								echo '</tr><tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>';
								if(empty($customer_id)){
									echo '<td>&nbsp;</td>';
								}
							}
							?>
							<td><?php echo $colors[$r1["color_id"]]?></td>
							<td><?php echo $designs[$r1["design_id"]]?></td>
							<?php
							$quantities = [];
							$t = 0;
							foreach(explode(",", $r1["sizes"]) as $size){
								$size = explode("x", $size);
								$quantities[$size[0]]=$size[1];
								$t += $size[1];
							}
							foreach($sizes as $size_id => $size){
								$totals[$size_id] += isset($quantities[$size_id])?$quantities[$size_id]:0;
								?>
								<td class="text-right"><?php echo isset($quantities[$size_id])?$quantities[$size_id]:"--";?></td>
								<?php
							}
							$total_price +=  $t * $r1["unit_price"];
							
							
							?>
							<th class="text-right bg-grey"><?php echo $t?></th>
							<td class="text-right color3-bg"><?php echo $r1["unit_price"]?></td>
							<th class="text-right bg-grey"><?php echo $t * $r1["unit_price"]?></th>
							<?php
						}
						?>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<?php 
							if(empty($customer_id)){
								?>
								<td>&nbsp;</td>
								<?php 
							}
							?>
							<th colspan="2" class="text-right bg-grey">Total</th>
							<?php
							$t = 0;
							foreach($totals as $size_id => $total){
								$grand_totals[$size_id] += $total;
								$t += $total;
								
								?>
								<th class="text-right bg-grey"><?php echo $total?></th>
								<?php
							}
							?>
							<th class="text-right bg-grey"><?php echo $t?></th>
							<th class="text-right bg-grey"><?php echo $price?></th>
                            <th class="text-right bg-grey"><?php echo $total_price?></th>
						<?php
					}
					?>
        </tr>
		<?php
		$sn++;
	}
}
?>
<tr>
	<td colspan="<?php if(empty($customer_id)) echo "6"; else echo "5";?>"></td>
	<th colspan="2" class="text-right bg-grey">Grand Total</th>
	<?php
	$final_total = 0;
	foreach($grand_totals as $total){
		//print_r($total);
		$final_total += $total;
		$final_price += $price;
		$final_total_price += $total_price;
		?>
		<th class="bg-grey text-right"><?php echo $total?></th>
		<?php
	}
	?>
	<th class="text-right bg-grey"><?php echo $final_total?></th>
	<th class="text-right bg-grey"><?php echo $final_price?></th>
	<th class="text-right bg-grey"><?php echo $final_total_price?></th>
</tr>
</table>
<?php
die;