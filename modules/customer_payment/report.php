<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_amount = $total_discount = 0;
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
.text-right{ text-align:right}
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="8">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Customer Payment List</h2>
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
                ?>
                Customer:  <span class="nastaleeq"><?php echo get_field($customer_id, "customer", "customer_name_urdu" )."<br>";?></span>
                <?php
            }
            if( !empty( $machine_id ) ){
                echo " Machine: ".get_field($machine_id, "machine", "title" )."<br>";
            }
            if( !empty( $account_id ) ){
                echo " Account: ".get_field($account_id, "account", "title" );
            }
			?>
        </p>
    </th>
</tr>
<tr>
    <th>Paid By</th>
    <th class="text-right nastaleeq">رقم</th>
    <th class="text-right nastaleeq">ڈسکاؤنٹ</th>
    <th class="nastaleeq">تاریخ</th>
    <th>Machine</th>
    <th>Customer</th>
    <th width="5%" class="text-center">ID</th>
    <th width="2%" class="text-center nastaleeq">سیریل</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
        $total_amount += $r["amount"];
        $total_discount += $r["discount"];
		?>
		<tr>
            <td class="nastaleeq"><?php echo get_field( unslash($r["account_id"]), "account", "title_urdu" ); ?></td>
            <td class="text-right"><?php echo curr_format(unslash($r["amount"])); ?></td>
            <td class="text-right"><?php echo curr_format(unslash($r["discount"])); ?></td>
            <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
            <td><?php if($r["machine_id"]==0) echo "All Machine"; else echo get_field($r["machine_id"], "machine","title");?></td>
            <td class="nastaleeq"><?php echo unslash( $r[ "customer_name_urdu" ] );?></td>
            <td class="text-center"><?php echo $r["id"]?></td>
            <td align="center"><?php echo $sn++?></td>
        </tr>
		<?php
	}
}
?>
<tr>
<th></th>
<th align="right"><?php echo curr_format($total_amount)?></th>
<th align="right"><?php echo curr_format($total_discount)?></th>
<th align="right" colspan="5">ٹوٹل</th>
</tr>
</table>
<?php
die;