<?php
if(!defined("APP_START")) die("No Direct Access");
$employee = dofetch( doquery( "select * from admin where id = '".slash( $_GET[ "id" ] )."'", $dblink ) );
$sql = "SELECT id, employee_id, CONCAT(LAST_DAY(CONCAT(year,'-',month+1,'-01')),' 00:00:00') as dt, concat( MONTHNAME(LAST_DAY(CONCAT(year,'-',month+1,'-01'))), ' ', year, ' salary') as details, 0 as debit, amount as credit FROM salary where status=1 and employee_id = '".$employee[ "id" ]."' union select a.id, employee_id, a.datetime_added as dt, concat(a.details, if(a.details='','', ' - '),'Paid by ', b.title), amount as debit, 0 as credit from salary_payment a left join account b on a.account_id = b.id where a.status=1 and employee_id = '".$employee[ "id" ]."' order by dt desc";
$salary_total = get_user_salary_total( $employee[ "id" ] );
$balance = $salary_total[ "balance" ];
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
            <!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Wamtsol</h1>
</body>
</html>            <h2>Salary Sheet of <?php echo $employee[ "name" ]?></h2>
        </th>
    </tr>
    <tr>
        <th width="5%" align="center">S.no</th>
        <th>Date</th>
        <th>Details</th>
        <th align="right">Debit</th>
        <th align="right">Credit</th>
        <th align="right">Balance</th>
    </tr>
    <tbody>
        <tr>
            <td colspan="2"></td>
            <td>Total</td>
            <td align="right"><?php echo curr_format( $salary_total[ "total_debit" ] )?></td>
            <td align="right"><?php echo curr_format( $salary_total[ "total_credit" ] )?></td>
            <td align="right"><?php echo curr_format( $balance )?></td>
        </tr>
        <?php
        $rs = doquery( $sql, $dblink );
		if( numrows( $rs ) > 0 ) {
			$sn = 1;
			while( $r = dofetch( $rs ) ) {
				?>
				<tr>
					<td class="text-center"><?php echo $sn++?></td>
					<td><?php echo date_convert( $r[ "dt" ] )?></td>
					<td><?php echo unslash( $r[ "details" ] )?></td>
					<td align="right"><?php echo curr_format( $r[ "debit" ] )?></td>
					<td align="right"><?php echo curr_format( $r[ "credit" ] )?></td>
					<td align="right"><?php echo curr_format( $balance )?></td>
				</tr>
				<?php
				$balance -= $r[ "debit" ];
				$balance += $r[ "credit" ];
			}
		}
		?>
        <tr>
            <td colspan="2"></td>
            <td>Opening Balance</td>
            <td></td>
            <td></td>
            <td align="right"><?php echo $balance?></td>
        </tr>
   	</tbody>
</table>
