<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_amount = $total_discount = $total_claim = 0;
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
.text-right{ text-align:right}
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="10">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Vendor Payment List</h2>
        <p style="font-size: 22px;background: #187bd0;padding: 5px;">
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
            if( !empty( $vendor_id ) ){
                ?>
                vendor:  <span class="nastaleeq"><?php echo get_field($vendor_id, "vendor", "vendor_name_urdu" )."<br>";?></span>
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
    <th width="10%">Paid By</th>
    <th class="text-right nastaleeq" width="10%">وائرس/تعداد</th>
    <th class="text-right nastaleeq" width="10%">رقم</th>
    <th class="text-right nastaleeq" width="10%">ڈسکاؤنٹ</th>
    <th class="nastaleeq text-center" width="10%">حوالہ نمبر</th>
    <th class="nastaleeq" width="12%">تاریخ</th>
    <th width="10%">Machine</th>
    <th width="15%">vendor</th>
    <th width="5%" class="text-center">ID</th>
    <th width="2%" class="text-center nastaleeq">سیریل</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
        $total_claim += $r["claim"];
        $total_amount += $r["amount"];
        $total_discount += $r["discount"];
		?>
		<tr>
            <td class="nastaleeq"><?php echo get_field( unslash($r["account_id"]), "account", "title_urdu" ); ?></td>
            <td class="text-right"><?php echo unslash($r["claim"]); ?></td>
            <td class="text-right"><?php echo curr_format(unslash($r["amount"])); ?></td>
            <td class="text-right"><?php echo curr_format(unslash($r["discount"])); ?></td>
            <td><?php echo unslash($r["details"]); ?></td>
            <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
            <td><?php if($r["machine_id"]==0) echo "All Machine"; else echo get_field($r["machine_id"], "machine","title");?></td>
            <td class="nastaleeq"><?php echo unslash( $r[ "vendor_name_urdu" ] );?></td>
            <td class="text-center"><?php echo $r["id"]?></td>
            <td align="center"><?php echo $sn++?></td>
        </tr>
		<?php
	}
}
?>
<tr>
<th></th>
<th align="right" style="background-color:#dad55e;padding: 10px 5px;vertical-align: middle;font-weight: 900;font-size: 22px;"><?php echo curr_format($total_claim)?></th>
<th align="right" style="background-color:#dad55e;padding: 10px 5px;vertical-align: middle;font-weight: 900;font-size: 22px;"><?php echo curr_format($total_amount)?></th>
<th align="right" style="background-color:#dad55e;padding: 10px 5px;vertical-align: middle;font-weight: 900;font-size: 22px;"><?php echo curr_format($total_discount)?></th>
<th align="left" colspan="6" style="vertical-align: middle;font-weight: 900;font-size: 22px;">ٹوٹل</th>
</tr>
</table>
<?php
die;