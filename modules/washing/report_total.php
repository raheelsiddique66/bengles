<?php
if(!defined("APP_START")) die("No Direct Access");
//$sql = "SELECT a.*, group_concat(a.id) as washing_ids, b.customer_name, b.customer_name_urdu, b.id as customerid FROM customer b left join washing a on (a.customer_id = b.id $extra) left join washing_items c on a.id = c.washing_id ".(!empty($machine_id)?"where c.machine_id = '".$machine_id."'":"")." group by b.id order by customer_name";
$sql = "SELECT a.*, group_concat(a.id) as washing_ids FROM `washing` a left join customer b on a.customer_id = b.id left join washing_items c on a.id = c.washing_id  WHERE 1 $extra and b.status = 1 ".(!empty($machine_id)?" and c.machine_id = '".$machine_id."'":"")." group by customer_id order by customer_name";
$rs = doquery( $sql, $dblink );
$colors = [];
$rs2 = doquery("select a.* from color a inner join washing_items b on a.id = b.color_id ".(!empty($machine_id)?"where machine_id = '".$machine_id."'":"")." order by sortorder", $dblink);
$colors_total = array();
while($r2=dofetch($rs2)){
	$colors[$r2["id"]] = unslash($r2["title_urdu"]);
    $colors_total[$r2["id"]] = 0;
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
	vertical-align:top;
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
        <p>
        	<?php
			echo "List of Washing of ";
			$all = true;
			if( !empty( $customer_id ) ){
			    ?>
                Customer <span class="nastaleeq"><?php echo get_field($customer_id, "customer", "customer_name_urdu" )."<br>";?></span>
                <?php
                $all = false;
			}
            if( !empty( $machine_id ) ){
			    ?>
                Machine <span class="nastaleeq"><?php echo get_field($machine_id, "machine", "title" )."<br>";?></span>
                <?php
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
    <th width="15%">Total</th>
    <?php
    foreach($colors as $color_id => $color) {
        ?>
        <th width="15%" class="nastaleeq"><?php echo $color ?></th>
        <?php
    }
    ?>
    <th>Customer</th>
    <th width="2%" align="center">S.no</th>
</tr>
</thead>
<?php
$grand_total_quantity = 0;
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    
	while( $r = dofetch( $rs ) ) {
        $colors_washing = [];
        $total_quantity = 0;
        $rs1 = doquery( "select color_id, sum(quantity) from washing_items where washing_id in (".($r["washing_ids"]).")".(!empty($machine_id)?" and machine_id = '".$machine_id."'":"")." group by color_id, quantity", $dblink );
        if(numrows($rs1)>0){
            while($r1=dofetch($rs1)){
                if(!isset($colors_washing[$r1["color_id"]])){
                    $colors_washing[$r1["color_id"]] = 0;
                }
                $colors_washing[$r1["color_id"]] += $r1["sum(quantity)"];
                $colors_total[$r1["color_id"]] += $r1["sum(quantity)"];
                $total_quantity += $r1["sum(quantity)"];
            }
        }
        $grand_total_quantity += $total_quantity;
		?>
		<tr>
            <th class="text-right"><?php echo curr_format($total_quantity)?></th>
            <?php

            foreach($colors as $color_id => $color){
                ?>
                <td class="text-right"><?php echo isset($colors_washing[$color_id]) ? curr_format($colors_washing[$color_id]) : 0 ?></td>
                <?php
            }
            ?>
            <td class="nastaleeq"><span style="margin-right: 10px;"><?php echo get_field($r["customer_id"], "customer", "customer_name_urdu" ); ?></span></td>
        	<td align="center"><?php echo $sn?></td>


        </tr>
		<?php
		$sn++;
	}
}
?>
<tr class="total_col">
    <th class="text-right"><?php echo curr_format($grand_total_quantity)?></th>
    <?php
    foreach($colors as $color_id => $color){
        ?>
        <th class="text-right"><?php echo curr_format($colors_total[$color_id]) ?></th>
        <?php
    }
    ?>
    <td></td>
    <th class="text-right">Grand Total</th>


</tr>
</table>
<?php
die;
