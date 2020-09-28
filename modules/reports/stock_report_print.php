<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if(isset($_GET["date_from"])){
	$_SESSION["reports"]["stock_report"]["date_from"]=slash($_GET["date_from"]);
}

if(isset($_SESSION["reports"]["stock_report"]["date_from"]))
	$date_from=$_SESSION["reports"]["stock_report"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."'";
}
if(isset($_GET["date_to"])){
	$_SESSION["reports"]["stock_report"]["date_to"]=slash($_GET["date_to"]);
}

if(isset($_SESSION["reports"]["stock_report"]["date_to"]))
	$date_to=$_SESSION["reports"]["stock_report"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'";
}
if(isset($_GET["color_id"])){
	$_SESSION["reports"]["stock_report"]["color_id"]=slash($_GET["color_id"]);
}
if(isset($_SESSION["reports"]["stock_report"]["color_id"]))
	$color_id=$_SESSION["reports"]["stock_report"]["color_id"];
else
    $color_id="";
if(isset($_GET["design_id"])){
    $_SESSION["reports"]["stock_report"]["design_id"]=slash($_GET["design_id"]);
}
if(isset($_SESSION["reports"]["stock_report"]["design_id"]))
    $design_id=$_SESSION["reports"]["stock_report"]["design_id"];
else
    $design_id="";
if(isset($_GET["machine_id"])){
    $_SESSION["reports"]["stock_report"]["machine_id"]=slash($_GET["machine_id"]);
}
if(isset($_SESSION["reports"]["stock_report"]["machine_id"]))
    $machine_id=$_SESSION["reports"]["stock_report"]["machine_id"];
else
    $machine_id="";
if($machine_id!=""){
    $extra.=" and machine_id='".$machine_id."'";
}
if(isset($_GET["customer_id"])){
    $_SESSION["reports"]["stock_report"]["customer_id"]=slash($_GET["customer_id"]);
}
if(isset($_SESSION["reports"]["stock_report"]["customer_id"]))
    $customer_id=$_SESSION["reports"]["stock_report"]["customer_id"];
else
    $customer_id="";
if($customer_id!=""){
    $extra.=" and customer_id='".$customer_id."'";
}
if(isset($_GET["report_type"])){
    $_SESSION["reports"]["stock_report"]["report_type"]=slash($_GET["report_type"]);
}
if(isset($_SESSION["reports"]["stock_report"]["report_type"]))
    $report_type=$_SESSION["reports"]["stock_report"]["report_type"];
else
    $report_type="";
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
.text-right{ text-align:right}
.text-left{ text-align:left}
.text-center{ text-align:center}
</style>
<table cellspacing="0" cellpadding="0">
        <tr class="head">
            <th colspan="40">
                <h1><?php echo get_config( 'site_title' )?></h1>
                <h2>Stock Report</h2>
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
                    if( !empty( $color_id ) ){
                        echo " Color: ".get_field($color_id, "color", "title" );
                    }
                    if( !empty( $design_id ) ){
                        echo " Design: ".get_field($design_id, "design", "title" );
                    }
                    if( !empty( $customer_id ) ){
                        echo " Customer: ".get_field($customer_id, "customer", "customer_name" );
                    }
                    if( !empty( $machine_id ) ){
                        echo " Machine: ".get_field($machine_id, "machine", "title" );
                    }
                    ?>
                </p>
            </th>
        </tr>
        <?php
        $sizes = [];
        $rs = doquery("select * from size order by sortorder", $dblink);
        if(numrows($rs)>0){
            while($r = dofetch($rs)){
                $sizes[$r["id"]] = unslash( $r["title"] );
            }
        }
        $colspan = count($sizes)+1;
        ?>
            <tr>
                <th width="2%" class="text-center" rowspan="2">S.no</th>
                <?php if($report_type==""){?>
                    <th width="5%" rowspan="2">Date</th>
                <?php }?>
                <th width="10%" colspan="2" class="text-center">Item</th>
                <?php if(empty( $customer_id ) ){?>
                <th width="11%" rowspan="2">Customer</th>
                <?php }?>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Received</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Sent</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Balance</th>
            </tr>
            <tr>
                <td>Color</td>
                <td>Design</td>
                <?php
                for($i = 0; $i < 3; $i++){
                    foreach($sizes as $size){
                        ?>
                        <td class="text-center"><?php echo $size?></td>
                        <?php
                    }
                    ?>
                    <th class="text-center color-<?php echo $i?>">Total</th>
                    <?php
                }
                ?>
            </tr>
            <?php
            $totals = [];
            for($i = 0; $i < 3; $i++){
                foreach($sizes as $size_id => $size){
                    $totals[$i][$size_id] = 0;
                }
                $totals[$i]['t'] = 0;
            }
            $rs = doquery("select a.*, b.id as color_id, b.title as color from design a cross join color b where 1 ".((!empty($color_id)?" and b.id='".$color_id."'":"").(!empty($design_id)?" and a.id='".$design_id."'":""))." order by a.title, b.sortorder", $dblink);
            if(numrows($rs) > 0){
                $sn = 1;
                while($r = dofetch($rs)){
                   // $records = doquery("select date, customer_id, max(`incoming`) as incoming, max(`outgoing`) as outgoing from (select a.date, a.customer_id, group_concat(concat(size_id, 'x', quantity)) as incoming, '' as outgoing from incoming a left join incoming_items b on a.id = b.incoming_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id union select a.date, a.customer_id, '' as incoming, group_concat(concat(size_id, 'x', quantity)) as outgoing from delivery a left join delivery_items b on a.id = b.delivery_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id) as records group by customer_id".($report_type==""?",date":"")." order by date", $dblink);
                    //$records = doquery("select date, customer_id, ".($report_type=="asd"?"max":"group_concat")."(`incoming`) as incoming, ".($report_type=="asd"?"max":"group_concat")."(`outgoing`) as outgoing from (select a.date, a.customer_id, group_concat(concat(size_id, 'x', quantity)) as incoming, '' as outgoing from incoming a left join incoming_items b on a.id = b.incoming_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id union select a.date, a.customer_id, '' as incoming, group_concat(concat(size_id, 'x', quantity)) as outgoing from delivery a left join delivery_items b on a.id = b.delivery_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id) as records group by customer_id".($report_type==""?",date":"")." order by customer_id", $dblink);
                    $sql = "select date, customer_id, concat(size_id, 'x', sum(incoming)) as incoming, concat(size_id, 'x', sum(outgoing)) as outgoing from (select a.date, a.customer_id, size_id, sum(quantity) as incoming, 0 as outgoing from incoming a left join incoming_items b on a.id = b.incoming_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.date union select a.date, a.customer_id, size_id, 0 as incoming, sum(quantity) as outgoing from delivery a left join delivery_items b on a.id = b.delivery_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.date) as records group by customer_id".($report_type==""?",date":"")." order by customer_id";
                    $records = doquery($sql, $dblink);
                    if( numrows($records) > 0 ){
                        while($record = dofetch($records)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $sn; ?></td>
                                <?php if(empty( $report_type ) ){?>
                                    <td><?php echo date_convert($record["date"]); ?></td>
                                <?php }?>
                                <td><?php echo unslash($r["color"])?></td>
                                <td><?php echo unslash($r["title"]) ?></td>
                                <?php if(empty( $customer_id ) ){?>
                                <td><?php echo get_field($record["customer_id"], "customer", "customer_name" ); ?></td>
                                <?php }?>
                                <?php
                                $incoming = [];
                                $outgoing = [];
                                for($i = 0; $i < 2; $i++){
                                    $type = $i==0?"incoming":"outgoing";
                                    $quantities = [];
                                    $t = 0;
                                    if(!empty($record[$type])) {
                                        foreach (explode(",", $record[$type]) as $size) {
                                            if(!empty($size)) {
                                                $size = explode("x", $size);
                                                if(!isset($quantities[$size[0]])){
                                                    $quantities[$size[0]] = 0;
                                                }
                                                $quantities[$size[0]] += $size[1];
                                                $t += $size[1];
                                            }
                                           // $size = explode("x", $size);
                                            //$quantities[$size[0]] = $size[1];
                                            //$t += $size[1];
                                        }
                                    }
                                    $$type = $quantities;
                                    foreach($sizes as $size_id => $size){
                                        $totals[$i][$size_id] += isset($quantities[$size_id])?$quantities[$size_id]:0;
                                        ?>
                                        <td class="text-right"><?php echo isset($quantities[$size_id])?$quantities[$size_id]:"--";?></td>
                                        <?php
                                    }
                                    $totals[$i]['t'] += $t;
                                    ?>
                                    <th class="text-center color-<?php echo $i?>"><?php echo $t?></th>
                                    <?php
                                }
                                $t = 0;
                                foreach($sizes as $size_id => $size){
                                    $b = (isset($incoming[$size_id])?$incoming[$size_id]:0)-(isset($outgoing[$size_id])?$outgoing[$size_id]:0);
                                    $t += $b;
                                    $totals[2][$size_id] += $b;
                                    ?>
                                    <td class="text-right"><?php echo $b;?></td>
                                    <?php
                                }
                                $totals[2]['t'] += $t;
                                ?>
                                <th class="text-center color-2"><?php echo $t?></th>
                            </tr>
                            <?php
                            $sn++;
                        }
                    }
                    ?>
                    <?php
                }
                $colspan = 5;
                if(!empty($customer_id)){
                    $colspan--;
                }
                if(!empty($report_type)){
                    $colspan--;
                }
                ?>
                <tr>
                    <th class="text-right" colspan="<?php echo $colspan;?>">Total</th>
                    <?php
                    for($i = 0; $i < 3; $i++){
                        foreach($sizes as $size_id => $size){
                            ?>
                            <th class="text-center"><?php echo $totals[$i][$size_id]?></th>
                            <?php
                        }
                        ?>
                        <th class="text-center color-<?php echo $i?>"><?php echo $totals[$i]['t']?></th>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
  	</table>
<style>
    .color-0{
        background-color:lightblue !important;
    }
    .color-1{
        background-color:lightgreen !important;
    }
    .color-2{
        background-color:antiquewhite !important;
    }

</style>
<?php
die;
