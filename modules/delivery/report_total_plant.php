<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "SELECT a.*, group_concat(a.id) as delivery_ids, b.title, b.id as machineid, d.customer_name FROM machine b left join delivery_items c on b.id = c.machine_id left join delivery a on a.id = c.delivery_id $extra left join customer d on a.customer_id = d.id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by b.id order by title";
$rs = doquery( $sql, $dblink );
$colors_field = [];
$rs22 = doquery("select * from color_field where status = 1 order by sortorder", $dblink);
while($r22=dofetch($rs22)){
        $colors_field[$r22["id"]] = unslash($r22["title_urdu"]);
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
	<th colspan="30">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Summary</h2>
        <p style=" font-size: 22px;background: #187bd0;padding: 5px;color:#fff;">
        	<?php
			echo "List of Sale of ";
			$all = true;
            if( !empty( $machine_id ) ){
                ?>
                Plant <span class="nastaleeq"><?php echo get_field($machine_id, "machine", "title" )."<br>";?></span>
                <?php
                $all = false;
            }
			if( !empty( $q ) ){
				echo " Gatepass ID ".$q."<br>";
                $all = false;
			}
			if($all){
			    echo " All Plant";
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
    <th width="10%">Amount</th>
    <th width="10%">Total</th>
    <?php
    foreach($colors_field as $color_id => $color){
            ?>
            <th><span class="nastaleeq"><?php echo $color."<br>"?></span></th>
            <?php
        
    }
    ?>
    <th width="30%">Plant</th>
    <th width="2%" align="center">سیریل</th>
</tr>
</thead>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    $grand_total_quantity = 0;
	$grand_total_amount = 0;
	while( $r = dofetch( $rs ) ) {
        //echo $r["delivery_ids"];die;
        $colors_delivery = [];
        // $colors_total = [];
        $total_quantity = $total_amount = 0;
        if(!empty($r["delivery_ids"])) {
            $rs1 = doquery("select color_id, color_field_id, unit_price, sum(quantity), sum(quantity*unit_price) as total from delivery_items where delivery_id in (" . ($r["delivery_ids"]) . ")" . (!empty($machine_id) ? " and machine_id = '" . $machine_id . "'" : "") . " group by color_field_id", $dblink);
            if (numrows($rs1) > 0) {
                while ($r1 = dofetch($rs1)) {
                    if (!isset($colors_delivery[$r1["color_field_id"]])) {
                        $colors_delivery[$r1["color_field_id"]] = 0;
                        // $colors_total[$r1["color_field_id"]] = 0;
                    }
                    $colors_delivery[$r1["color_field_id"]] = $r1["sum(quantity)"];
                    $colors_total[$r1["color_field_id"]] += $r1["sum(quantity)"];
                    $total_quantity += $r1["sum(quantity)"];
                    $total_amount += $r1["total"];
                }
            }
        }
        $grand_total_quantity += $total_quantity;
        $grand_total_amount += $total_amount;
        ?>
		<tr>
            <th class="text-right"><?php echo curr_format($total_amount)?></th>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
            <?php

            foreach($colors_field as $color_field_id => $color){
                    ?>
                    <td class="text-right"><?php echo isset($colors_delivery[$color_field_id]) ? curr_format($colors_delivery[$color_field_id]) : 0 ?></td>
                    <?php
                
            }
            ?>
            <td class="nastaleeq"><span style="margin-right: 10px;"><?php echo unslash($r["title"]); ?></span></td>
        	<td align="center"><?php echo $sn?></td>
        </tr>
		<?php
		$sn++;
	}
}
?>
<tr class="total_col">
    <th class="text-right"><?php echo curr_format($grand_total_amount)?></th>
    <th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>

    <?php
    foreach($colors_field as $color_field_id => $color){
            ?>
            <th class="text-right"><?php echo curr_format($colors_total[$color_field_id]) ?></th>
            <?php
    }
    ?>
    <th class="text-right">Grand Total</th>
    <td></td>
</tr>
</table>
<?php
die;