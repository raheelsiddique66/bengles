<?php
if(!defined("APP_START")) die("No Direct Access");
$sql="select * from color_field order by title";
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
    font-size: 11px;
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
	<th colspan="8">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>color field List</h2>
        <p>
        	<?php
			echo "List of";
            if( !empty( $q ) ){
                ?>
                color_field:  <span class="nastaleeq"><?php echo $q."<br>";?></span>
                <?php
            }
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="15%">Title Urdu</th>
    <th width="15%">Title</th>
    <th width="2%" class="text-center nastaleeq">سیریل</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
            <td align="left"><span class="nastaleeq"><?php echo unslash($r["title_urdu"]); ?></span></td>
            <td align="left"><?php echo unslash($r["title"]); ?></td>
            <td align="center"><?php echo $sn++?></td>
        </tr>
		<?php
	}
}
?>
</table>
<?php
die;