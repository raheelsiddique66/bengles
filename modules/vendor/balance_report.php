<?php
if(!defined("APP_START")) die("No Direct Access");
$sql1="select * from vendor where 1 $extra and status = 1 order by vendor_name";
$rs = doquery( $sql1, $dblink );
$total_balance = 0;
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
    font-size: 18px;
	vertical-align:top;
    font-weight: bold;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:800px;
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
    	<h2>Vendor List</h2>
        <p>
        	<?php
			echo "List of";
            if( !empty( $q ) ){
                ?>
                Vendor:  <span class="nastaleeq"><?php echo $q."<br>";?></span>
                <?php
            }
            if( !empty( $date ) ){
                echo " Date ".$date;
            }
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="10%">Remarks</th>
    <th width="10%" style="font-size: 18px">Balance</th>
    <th width="10%">Phone</th>
    <th width="10%">Machine</th>
    <th width="20%">Vendor</th>
    <th width="2%" class="text-center nastaleeq">سیریل</th>
</tr>
<?php
$total = 1;
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
            <td><?php echo unslash($r["phone"]); ?></td>
            <td style="font-size: 18px; text-align: right"><?php echo curr_format(get_vendor_balance($r['id'], date_dbconvert($date)));?></td>
            <td></td>
            <td><?php if($r["machine_id"]==0) echo "All Machine"; else echo get_field($r["machine_id"], "machine","title");?></td>
            <td class="nastaleeq"><?php echo unslash( $r[ "vendor_name_urdu" ] );?></td>
            <td align="center"><?php echo $sn++?></td>
        </tr>
		<?php
	}
}
?>
    <tr>
        <td></td>
        <th align="right" class="bg-success" style="background-color:#dad55e;padding: 10px 5px;vertical-align: middle;font-weight: 900;font-size: 22px;"><?php echo curr_format(get_vendor_total_balance($machine_id, date_dbconvert($date)));?></th>
        <th colspan="4" align="left" style="vertical-align: middle;font-weight: 900;font-size: 22px;">Total</th>
    </tr>
</table>
<?php
die;