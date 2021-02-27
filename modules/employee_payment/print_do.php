<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_amount = 0;
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
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:900px;
	margin:0 auto;
}
.text-center{ text-align:center}
.text-right{ text-align:right}
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Employee Payment List</h2>
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
                if( !empty( $employee_id ) ){
                    echo " Employee: ".get_field($employee_id, "employees", "name" )."<br>";
                }
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="10%">Date</th>
    <th width="20%">Employee Name</th>
    <th width="15%">Account</th>
    <th width="10%" align="right">Amount</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
        $total_amount += $r["amount"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
            <td><?php echo date_convert($r["date"]); ?></td>
            <td class="nastaleeq"><?php echo get_field($r["employee_id"], "employees", "name"); ?></td>
            <td><?php echo get_field($r["account_id"], "account", "title"); ?></td>
            <td align="right"><?php echo unslash($r["amount"]); ?></td>
        </tr>
		<?php
	}
}
?>
<tr>
    <th align="right" colspan="3">Total</th>
    <th></th>
    <th align="right"><?php echo $total_amount?></th>
</tr>
</table>
<?php
die;