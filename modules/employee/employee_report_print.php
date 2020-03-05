<?php
if(!defined("APP_START")) die("No Direct Access");
include("employee_report_do.php");
//include("include/utility.php");
$sql="select concat( 'Salary #', id) as details, date, 0 as debit, calculated_salary as credit from employee_salary where employee_id = '".$employee[ "id" ]."' $extra union select 'Payment', date, amount as debit, 0 as credit from employee_payment where employee_id = '".$employee[ "id" ]."' $extra order by $order_by $order";
$rs = doquery( $sql, $dblink );
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
</style>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr class="head">
        <th colspan="9">
            <h1><?php echo get_config( 'site_title' )?></h1>
            <h2>Employee Report</h2>
            <h3> From <?php echo $date_from; ?> To <?php echo $date_to; ?> </h3>
            <h2> Employee: <?php echo $employee["name"]; ?></h2>
        </th>
    </tr>
    <tr>
        <th width="5%" align="center">S.no</th>
        <th width="15%">Date</th>
        <th>Details</th>
        <th align="right">Debit</th>
        <th align="right">Credit</th>
        <th align="right">Balance</th>
    </tr>
    <tbody>
        
        <?php if( numrows( $rs ) > 0 ) {
            $sn = 1;
            $balance = get_employee_balance( $employee["id"], date_dbconvert($date_from)); ?>
        <tr>
            <td colspan="2"></td>
            <td><?php echo $order == 'desc'?'Closing':'Opening'?> Balance</td>
            <td></td>
            <td></td>
            <td align="right"><?php echo curr_format( $balance )?></td>
        </tr>
		<?php
            while($r=dofetch($rs)){
				?>
				<tr>
					<td align="center"><?php echo $sn++;?></td>
					<td><?php echo datetime_convert($r["date"]); ?></td>
					<td><?php echo unslash($r["details"]); ?></td>
					<td align="right"><?php echo curr_format($r["debit"]); ?></td>
					<td align="right"><?php echo curr_format($r["credit"]); ?></td>
					<td align="right"><?php if($order == 'asc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} echo curr_format( $balance ); if($order == 'desc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} ?></td>
				</tr>
				<?php
			} ?> 
                <tr>
                	<td colspan="2"></td>
                    <td><?php echo $order != 'desc'?'Closing':'Opening'?> Balance</td>
                    <td></td>
                    <td></td>
                    <td align="right"><?php echo curr_format( $balance )?></td>
                </tr>
            <?php
		}
        ?>
    </tbody>
</table>
<?php
die;
