<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
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
    	<h2>Customer List</h2>
        <p>
        	<?php
			echo "List of";
            if( !empty( $q ) ){
                ?>
                Customer:  <span class="nastaleeq"><?php echo $q."<br>";?></span>
                <?php
            }
            if( !empty( $machine_id ) ){
                echo " Machine: ".get_field($machine_id, "machine", "title" )."<br>";
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
    <th width="15%">Customer</th>
    <th width="2%" class="text-center nastaleeq">سیریل</th>
</tr>
<?php
$total = 1;
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
            <td></td>
            <td style="font-size: 18px"><?php echo get_customer_balance($r['id']);?></td>
            <td><?php echo unslash($r["phone"]); ?></td>
            <td><?php if($r["machine_id"]==0) echo "All Machine"; else echo get_field($r["machine_id"], "machine","title");?></td>
            <td class="nastaleeq"><?php echo unslash( $r[ "customer_name_urdu" ] );?></td>
            <td align="center"><?php echo $sn++?></td>
        </tr>
		<?php
	}
}
?>
    <tr>
        <td></td>
        <th align="left"><?php echo get_customer_total_balance($machine_id);?></th>
        <th colspan="4" align="left">Total</th>
    </tr>
</table>
<?php
die;