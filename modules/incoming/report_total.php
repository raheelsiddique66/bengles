<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "SELECT a.*, group_concat(a.id) as incoming_ids FROM `incoming` a left join customer b on a.customer_id = b.id  WHERE 1 $extra and b.status = 1 group by customer_id order by customer_name";
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select * from color order by sortorder", $dblink);
$colors_total = array();
while($r2=dofetch($rs2)){
	$colors[$r2["id"]] = unslash($r2["title"]);
    $colors_total[$r2["id"]] = 0;
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
			echo "List of Incoming of ";
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
    foreach($colors as $color_id => $color) {
        ?>
        <th><?php echo $color ?></th>
        <?php
    }
    ?>
    <th width="10%">Total</th>
</tr>
</thead>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    $grand_total_quantity = 0;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
        	<td align="center"><?php echo $sn?></td>
			<td><?php echo get_field($r["customer_id"], "customer", "customer_name" ); ?></td>
			<?php
            $colors_incoming = [];
            $total_quantity = 0;
			$rs1 = doquery( "select color_id, sum(quantity) from incoming_items where incoming_id in (".($r["incoming_ids"]).") group by quantity", $dblink );
			if(numrows($rs1)>0){
				while($r1=dofetch($rs1)){
                    if(!isset($colors_incoming[$r1["color_id"]])){
                        $colors_incoming[$r1["color_id"]] = 0;
                    }
				    $colors_incoming[$r1["color_id"]] += $r1["sum(quantity)"];
                    $colors_total[$r1["color_id"]] += $r1["sum(quantity)"];
                    $total_quantity += $r1["sum(quantity)"];
				}
			}
            $grand_total_quantity += $total_quantity;
            foreach($colors as $color_id => $color){
                ?>
                <td class="text-right"><?php echo isset($colors_incoming[$color_id]) ? curr_format($colors_incoming[$color_id]) : 0 ?></td>
                <?php
            }
			?>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
        </tr>
		<?php
		$sn++;
	}
}
?>
<tr>
	<td></td>
	<th class="text-right">Grand Total</th>
    <?php
    foreach($colors as $color_id => $color){
        ?>
        <th class="text-right"><?php echo curr_format($colors_total[$color_id]) ?></th>
        <?php
    }
    ?>
	<th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>
</tr>
</table>
<?php
die;
