<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_amount = $salary_rate = $over_time_rate = 0;
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
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Employee Salary List</h2>
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
    <th width="20%">Employee Name</th>
    <th width="10%">Date</th>
    <th width="10%" align="right">Salary Rate</th>
    <th width="10%" align="right">Over Time rate</th>
    <th width="15%" align="right">Calculated Salary</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
        $salary_rate += $r["salary_rate"];
        $over_time_rate += $r["over_time_rate"];
        $total_amount += $r["calculated_salary"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
            <td><?php echo unslash($r["name"]); ?></td>
            <td><?php echo unslash(date_convert($r["date"])); ?></td>
            <td align="right"><?php echo curr_format($r["salary_rate"]); ?></td>
            <td align="right"><?php echo curr_format($r["over_time_rate"]); ?></td>
            <td align="right"><?php echo curr_format($r["calculated_salary"]); ?></td>
        </tr>
		<?php
	}
}
?>
<tr>
    <th align="right" colspan="3">Total</th>
    <th align="right"><?php echo $salary_rate?></th>
    <th align="right"><?php echo $over_time_rate?></th>
    <th align="right"><?php echo $total_amount?></th>
</tr>
</table>
<?php
die;