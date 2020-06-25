<?php
if(!defined("APP_START")) die("No Direct Access");
include("general_journal_do.php");
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
            <h2>General Journal</h2>
            <p>
                <?php
                if( !empty( $date_from ) || !empty( $date_to ) ){
                    echo "<br />Date";
                }
                if( !empty( $date_from ) ){
                    echo " from ".$date_from;
                }
                if( !empty( $date_to ) ){
                    echo " to ".$date_to."<br>";
                }
                if( !empty( $account_id ) ){
                    echo " Account: ".get_field($account_id, "account", "title");
                }
                ?>
            </p>
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
            <td><?php echo $order == 'desc'?'Closing':'Opening'?> Balance</td>
            <td></td>
            <td></td>
            <td align="right"><?php echo curr_format( $balance )?></td>
        </tr>
		<?php
		if( numrows( $rs ) > 0 ) {
		$sn = 1;
        	while($r=dofetch($rs)){             
				?>
				<tr>
					<td align="center"><?php echo $sn;?></td>
					<td><?php echo datetime_convert($r["date"]); ?></td>
					<td><?php echo unslash($r["details"]); ?></td>
					<td align="right"><?php echo curr_format($r["debit"]); ?></td>
					<td align="right"><?php echo curr_format($r["credit"]); ?></td>
					<td align="right"><?php if($order == 'asc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} echo curr_format( $balance ); if($order == 'desc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} ?></td>
				</tr>
                <?php
                $sn++;
            }
            ?>
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
