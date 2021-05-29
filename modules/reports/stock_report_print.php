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
                <?php if(empty( $customer_id ) ){?>
                    <th width="11%" rowspan="2">Customer</th>
                <?php }?>
                <th width="10%" colspan="3" class="text-center">Item</th>

                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Received</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Sent</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Balance</th>
            </tr>
            <tr>
                <td>Design</td>
                <td>Gatepass</td>
                <td>Color</td>
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
    <tbody>
    <?php
    $customers = [];
    $records = doquery("select customer.customer_name, design.title as design, color.title as color, size.title as size, combined.* from (select a.date, a.gatepass_id, a.customer_id, design_id, color_id, size_id, 0 as type, sum(quantity) as incoming, 0 as outgoing from incoming a inner join incoming_items b on a.id = b.incoming_id where 1 $extra group by date, customer_id, design_id, color_id union select a.date, a.gatepass_id, a.customer_id, design_id, color_id, size_id, 1 as type, 0 as incoming, sum(quantity) as outgoing from delivery a inner join delivery_items b on a.id = b.delivery_id where 1 $extra group by ".($report_type!=1?'date, ':'')."customer_id, design_id, color_id) as combined inner join customer on combined.customer_id = customer.id inner join design on combined.design_id = design.id inner join color on combined.color_id = color.id inner join size on combined.size_id = size.id order by customer_name, date, color_id, design_id, size_id", $dblink);
    if(numrows($records) > 0){
        while($record = dofetch($records)){
            $key1 = $record["customer_id"]."_".$record["date"];
            if(!isset($customers[$key1])){
                $customers[$key1] = [
                    "id" => $record["customer_id"],
                    "name" => unslash($record["customer_name"]),
                    "date" => $record["date"],
                    "gatepass_id" => $record["gatepass_id"],
                    "design" => unslash($record["design"]),
                    "color" => unslash($record["color"]),
                ];
            }
            $key = "size_".$record["size_id"];
            $key2 = $key.'i';
            $key3 = $key.'o';
            if(!isset($customers[$key1][$key2])){
                $customers[$key1][$key2] = 0;
            }
            if(!isset($customers[$key1][$key3])){
                $customers[$key1][$key3] = 0;
            }
            $customers[$key1][$key2] += $record["incoming"];
            $customers[$key1][$key3] += $record["outgoing"];
        }
    }
    $sn = 1;
    $loop_customer_id = 0;
    $colspan = 6;
    if(!empty($customer_id)){
        $colspan--;
    }
    if(!empty($report_type)){
        $colspan--;
    }
    $totals = [];
    $grand_totals = [];
    if(count($customers) > 0){
        foreach($customers as $customer){
            ?>
            <tr>
                <td class="text-center"><?php echo $sn; ?></td>
                <?php if($report_type==""){?>
                    <td><?php echo date_convert($customer["date"]); ?></td>
                <?php } ?>
                <?php if(empty($customer_id)){?>
                    <td><?php echo $customer["name"]; ?></td>
                    <?php
                }
                ?>
                <td><?php echo $customer["design"] ?></td>
                <td><?php echo $customer["gatepass_id"] ?></td>
                <td><?php echo $customer["color"]?></td>
                <?php
                $balance = [];
                foreach(['i', 'o'] as $k => $type){
                    $t = 0;
                    foreach($sizes as $size_id => $size){
                        if(!isset($totals[$k]["size_".$size_id])){
                            $totals[$k]["size_".$size_id] = 0;
                        }
                        if(isset($customer["size_".$size_id.$type])){
                            if(!isset($balance["size_".$size_id])){
                                $balance["size_".$size_id] = 0;
                            }
                            $balance["size_".$size_id] += $customer["size_".$size_id.$type] * ($k?-1:1);
                            $t += $customer["size_".$size_id.$type];
                            $totals[$k]["size_".$size_id] += $customer["size_".$size_id.$type];
                        }
                        ?>
                        <td class="text-right"><?php echo curr_format($customer["size_".$size_id.$type] ?? "--");?></td>
                        <?php
                    }
                    if(!isset($totals[$k]["t"])){
                        $totals[$k]["t"] = 0;
                    }
                    $totals[$k]["t"] += $t;
                    ?>
                    <th class="text-center color-<?php echo $k?>"><?php echo curr_format($t)?></th>
                    <?php
                }
                $t = 0;
                foreach($sizes as $size_id => $size){
                    $t += $balance["size_".$size_id] ?? 0;
                    if(!isset($totals[2]["size_".$size_id])){
                        $totals[2]["size_".$size_id] = 0;
                    }
                    $totals[2]["size_".$size_id] += $balance["size_".$size_id] ?? 0;
                    ?>
                    <td class="text-right"><?php echo curr_format($balance["size_".$size_id] ?? '--');?></td>
                    <?php
                }
                if(!isset($totals[2]["t"])){
                    $totals[2]["t"] = 0;
                }
                $totals[2]["t"] += $t;
                ?>
                <th class="text-center color-2"><?php echo curr_format($t)?></th>
            </tr>
            <?php
            $sn++;
            if($loop_customer_id != $customer["id"]){
                if($loop_customer_id != 0){
                    ?>
                    <?php
                }
                $colspan = 6;
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
