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
        $quantity = 0;
		$unit_price = 0;
		$payment_total = 0;
		$salary_total = 0;
        $sql=doquery("select *, sum(quantity) as sum_quantity from delivery a left join delivery_items b on a.id = b.delivery_id where status = 1 and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."' group by delivery_id, color_id, design_id",$dblink);
        if( numrows( $sql ) > 0 ) {
            while( $r1 = dofetch( $sql ) ) {
                $quantity = $r1["sum_quantity"];
                //$q=dofetch(doquery("select sum(quantity) as quantity from delivery_items where delivery_id = '".$r1["delivery_id"]."' group by design_id,color_id",$dblink));
                $unit_price = $r1["unit_price"];
                //$payment_total += $unit_price*$q["quantity"];
                $payment_total += $unit_price*$quantity;
                //echo $unit_price."<br>";
                //echo $r1["quantity"]."<br>";
            }
        }
        //$q=dofetch(doquery("select sum(quantity) as quantity from delivery a left join delivery_items b on a.id = b.delivery_id where status = 1 and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'",$dblink));
		//$payment_total += $unit_price*$q["quantity"];
	?>
    <?php if($_SERVER['SERVER_NAME'] != 'star.burhanpk.com'){?>
    <tr>
        <th align="right">Income from <?php echo $date_from?> to <?php echo $date_to?></th>
        <th align="right">Rs. <?php echo curr_format($payment_total)?></th>
    </tr>
    <?php }?>
    <?php if($_SERVER['SERVER_NAME'] != 'star.burhanpk.com'){?>
    <tr>
        <th align="right">Salary from <?php echo $date_from?> to <?php echo $date_to?></th>
        <th align="right">Rs. <?php
            $rs = dofetch( doquery( "select sum(amount) from employee_payment where date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."' and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'", $dblink ) );
            echo curr_format( $rs[ "sum(amount)" ] );
            $salary_total += $rs[ "sum(amount)" ];
        ?></th>
    </tr>
    <?php }?>
    <?php
    $total = 0;
    $rs = doquery( "select title, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 $extra group by expense_category_id", $dblink );
    if( numrows( $rs ) > 0 ) {
        while( $r = dofetch( $rs ) ) {
            if( $r[ "total" ] > 0 ){
                $total += $r[ "total" ];
                ?>
                <tr>
                    <th align="right"><?php echo unslash( $r[ "title" ] )?></th>
                    <th align="right">Rs.<?php echo curr_format($r[ "total" ])?></th>
                </tr>	
                <?php
            }
        }
    }
    ?>
    <tr>
        <th align="right">Total Expense</th>
        <th align="right">Rs. <?php echo curr_format($total+$salary_total)?></th>
    </tr>
    <?php if($_SERVER['SERVER_NAME'] != 'star.burhanpk.com'){?>
    <tr>
        <th align="right">Net Income</th>
        <th align="right">Rs. <?php echo curr_format($payment_total-$salary_total-$total)?></th>
    </tr>	
    <?php }?>
</table>
<?php
die;
