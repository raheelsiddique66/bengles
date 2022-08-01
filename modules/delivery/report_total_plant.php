<?php
if(!defined("APP_START")) die("No Direct Access");
$sql1 = "SELECT a.*, group_concat(a.id) as delivery_ids, group_concat(ifnull(d.id,0)) as cus_ids, b.title, b.id as machineid, d.customer_name FROM machine b left join delivery_items c on b.id = c.machine_id left join delivery a on a.id = c.delivery_id $extra left join customer d on a.customer_id = d.id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by b.id order by b.title";
$sql1 = "SELECT b.machine_id, c.title as machine, color_field_id, sum(quantity) as quantity, sum(unit_price*quantity) as total FROM delivery a left join delivery_items b on a.id = b.delivery_id left join machine c on b.machine_id = c.id left join customer d on a.customer_id = d.id where 1 $extra group by b.machine_id, b.color_field_id order by c.title";
//echo $sql1; die;
// echo "SELECT a.*, group_concat(a.id) as delivery_ids, group_concat(d.id) as cus_ids, b.title, b.id as machineid, d.customer_name FROM machine b left join delivery_items c on b.id = c.machine_id left join delivery a on a.id = c.delivery_id $extra left join customer d on a.customer_id = d.id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by c.machine_id order by b.title";die;
//echo $sql1;die;
// $sql1 = "SELECT a.*, group_concat(a.id) as delivery_ids, c.title, FROM delivery a left join delivery_items b on a.id = b.delivery_id left join machine c on (b.machine_id = c.id $extra) ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by c.id order by c.title";
$rs = doquery( $sql1, $dblink );
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
	$records = [];
	while( $r = dofetch( $rs ) ) {
	    if(!isset($records[$r["machine_id"]])){
	        $records[$r["machine_id"]] = [
	            "machine" => unslash($r["machine"]),
	            "quantity" => 0,
	            "total" => 0
	        ];
	    }
	    $records[$r["machine_id"]]["color_field_id_".$r["color_field_id"]] = $r["quantity"];
	    $records[$r["machine_id"]]["quantity"] += $r["quantity"];
	    $records[$r["machine_id"]]["total"] += $r["total"];
	}
	//print_r($records); die;
	foreach($records as $record){
	    //print_r($record);
        // echo $r["delivery_ids"];
        $colors_delivery = [];
        // $colors_total = [];
        $total_quantity = $total_amount = 0;
        if(!empty($r["delivery_ids"]) && 0) {
            //$sql = "select color_field_id, unit_price, sum(quantity), sum(quantity*unit_price) as total, b.customer_id as cusid from delivery_items a left join delivery b on a.delivery_id = b.id left join customer c on b.customer_id = c.id where delivery_id in (" . ($r["delivery_ids"]) . ") and customer_id in (" . ($r["cus_ids"]) . ")" . (!empty($machine_id) ? " and a.machine_id = '" . $machine_id . "'" : "") . " group by color_field_id";
            //echo $sql; die;
            //$rs1 = doquery($sql, $dblink);
            // echo "select color_id, color_field_id, unit_price, sum(quantity), sum(quantity*unit_price) as total, c.id as cusid from delivery_items a left join delivery b on a.delivery_id = b.id left join customer c on b.customer_id = c.id where delivery_id in (" . ($r["delivery_ids"]) . ") and customer_id in (" . ($r["cus_ids"]) . ")" . (!empty($machine_id) ? " and a.machine_id = '" . $machine_id . "'" : "") . " group by color_field_id";die;
            // $rs1 = doquery("select color_field_id, unit_price, sum(quantity), sum(quantity*unit_price) as total, b.customer_id as cusid from delivery_items a left join delivery b on a.delivery_id = b.id left join customer c on b.customer_id = c.id where delivery_id in (" . ($r["delivery_ids"]) . ") " . (!empty($machine_id) ? " and a.machine_id = '" . $machine_id . "'" : "") . " group by color_field_id", $dblink);
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
        $grand_total_quantity += $record["quantity"];
        $grand_total_amount += $record["total"];
        ?>
		<tr>
            <th class="text-right"><?php echo curr_format($record["total"])?></th>
            <th class="text-right"><?php echo curr_format($record["quantity"])?></th>
            <?php

            foreach($colors_field as $color_field_id => $color){
                $value = 0;
                if (!isset($colors_total[$color_field_id])) {
                    $colors_total[$color_field_id] = 0;
                }
                if(isset($record["color_field_id_".$color_field_id])){
                    $value = $record["color_field_id_".$color_field_id];
                    $colors_total[$color_field_id] += $value;
                }
                 //$record["color_field_id_".$color_field_id];
                    ?>
                    <td class="text-right"><?php echo curr_format($value);/*echo isset($colors_delivery[$color_field_id]) ? curr_format($colors_delivery[$color_field_id]) : 0*/ ?></td>
                    <?php
                
            }
            ?>
            <td class="nastaleeq"><span style="margin-right: 10px;"><?php echo $record["machine"]; ?></span></td>
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