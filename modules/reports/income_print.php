<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["income"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["income"]["date_from"]))
	$date_from=$_SESSION["reports"]["income"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["income"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["income"]["date_to"]))
	$date_to=$_SESSION["reports"]["income"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and datetime_added<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if( empty( $extra ) ) {
	$extra = ' and 1=0 ';
}
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
<table  cellspacing="0" cellpadding="0">
    <tr class="head">
        <th colspan="2">
            <h1><?php echo get_config( 'site_title' )?></h1>
            <h2>Income Report</h2>
            <p>
                <?php
                if( !empty( $date_from ) || !empty( $date_to ) ){
                    echo "<br />Date";
                }
                if( !empty( $date_from ) ){
                    echo " from ".$date_from;
                }
                if( !empty( $date_to ) ){
                    echo " to ".$date_to;
                }
                ?>
            </p>
        </th>
    </tr>
    <?php
		$sql=doquery("select sum(unit_price)*sum(quantity) as total, b.id as did from delivery_items a inner join delivery b on a.delivery_id = b.id where status = 1 and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."' ",$dblink);
		$payment=dofetch($sql);
	?>
    <tr>
        <th align="right">Income from <?php echo $date_from?> to <?php echo $date_to?></th>
        <th align="right"><?php echo curr_format($payment[ "total" ])?></th>
    </tr>
    <?php
    $total = 0;
    $rs = doquery( "select title, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 $extra group by expense_category_id", $dblink );
    if( numrows( $rs ) > 0 ) {
        while( $r = dofetch( $rs ) ) {
            if( $r[ "total" ] > 0 ){
                $total += $r[ "total" ];
                ?>
                <tr class="">
                    <th align="right"><?php echo unslash( $r[ "title" ] )?></th>
                    <th align="right">Rs. <?php echo curr_format($r[ "total" ])?></th>
                </tr>	
                <?php
            }
        }
    }
    ?>
     <tr class="">
        <th align="right">Total Expense</th>
        <th align="right">Rs. <?php echo curr_format($total)?></th>
    </tr>
    <tr class="">
        <th align="right">Net Income</th>
        <th align="right">Rs. <?php echo curr_format($payment[ "total" ]-$total)?></th>
    </tr>	
</table>
<?php
die;
