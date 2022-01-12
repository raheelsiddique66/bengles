<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if(isset($_GET["date_from"])){
	$_SESSION["reports"]["vendor_stock_report"]["date_from"]=slash($_GET["date_from"]);
}

if(isset($_SESSION["reports"]["vendor_stock_report"]["date_from"]))
	$date_from=$_SESSION["reports"]["vendor_stock_report"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."'";
}
if(isset($_GET["date_to"])){
	$_SESSION["reports"]["vendor_stock_report"]["date_to"]=slash($_GET["date_to"]);
}

if(isset($_SESSION["reports"]["vendor_stock_report"]["date_to"]))
	$date_to=$_SESSION["reports"]["vendor_stock_report"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'";
}
if(isset($_GET["color_id"])){
	$_SESSION["reports"]["vendor_stock_report"]["color_id"]=slash($_GET["color_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["color_id"]))
	$color_id=$_SESSION["reports"]["vendor_stock_report"]["color_id"];
else
    $color_id="";
if(isset($_GET["design_id"])){
    $_SESSION["reports"]["vendor_stock_report"]["design_id"]=slash($_GET["design_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["design_id"]))
    $design_id=$_SESSION["reports"]["vendor_stock_report"]["design_id"];
else
    $design_id="";
if(isset($_GET["machine_id"])){
    $_SESSION["reports"]["vendor_stock_report"]["machine_id"]=slash($_GET["machine_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["machine_id"]))
    $machine_id=$_SESSION["reports"]["vendor_stock_report"]["machine_id"];
else
    $machine_id="";
if($machine_id!=""){
    $extra.=" and machine_id='".$machine_id."'";
}
if(isset($_GET["vendor_id"])){
    $_SESSION["reports"]["vendor_stock_report"]["vendor_id"]=slash($_GET["vendor_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["vendor_id"]))
    $vendor_id=$_SESSION["reports"]["vendor_stock_report"]["vendor_id"];
else
    $vendor_id="";
if($vendor_id!=""){
    $extra.=" and vendor_id='".$vendor_id."'";
}
if(isset($_GET["report_type"])){
    $_SESSION["reports"]["vendor_stock_report"]["report_type"]=slash($_GET["report_type"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["report_type"]))
    $report_type=$_SESSION["reports"]["vendor_stock_report"]["report_type"];
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
.nastaleeq, #name_in_urdu_text{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 18px;  }
</style>
<table cellspacing="0" cellpadding="0">
        <tr class="head">
            <th colspan="40">
                <h1><?php echo get_config( 'site_title' )?></h1>
                <h2>Vendor Stock Report</h2>
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
                    if( !empty( $vendor_id ) ){
                        echo " Vendor: ".get_field($vendor_id, "vendor", "vendor_name" );
                    }
                    if( !empty( $machine_id ) ){
                        echo " Machine: ".get_field($machine_id, "machine", "title" );
                    }
                    ?>
                </p>
            </th>
        </tr>
        <?php
        $colors = [];
        $rs = doquery("select * from color order by sortorder", $dblink);
        if(numrows($rs)>0){
            while($r = dofetch($rs)){
                $sizes[$r["id"]] = unslash( $r["title"] );
            }
        }
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
                <?php if(empty( $vendor_id ) ){?>
                    <th width="11%" rowspan="2" align="right">Vendor</th>
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
    $vendors = [];
    $records = doquery("select vendor.vendor_name, vendor.vendor_name_urdu, design.title as design, design.title_urdu as design_urdu, color.title as color, color.title_urdu as color_urdu, size.title as size, combined.* from (select a.date, a.gatepass_id, a.vendor_id, design_id, color_id, size_id, 0 as type, sum(quantity) as vendor_outgoing, 0 as outgoing from vendor_outgoing a inner join vendor_outgoing_items b on a.id = b.vendor_outgoing_id where 1 $extra group by ".($report_type!=1?'date, ':'')."vendor_id, design_id, color_id, size_id union select a.date, a.gatepass_id, a.vendor_id, design_id, color_id, size_id, 1 as type, 0 as vendor_outgoing, sum(quantity) as outgoing from vendor_delivery a inner join vendor_delivery_items b on a.id = b.vendor_delivery_id where 1 $extra group by ".($report_type!=1?'date, ':'')."vendor_id, design_id, color_id, size_id) as combined inner join vendor on combined.vendor_id = vendor.id inner join design on combined.design_id = design.id inner join color on combined.color_id = color.id inner join size on combined.size_id = size.id order by vendor_name, date, color_id, design_id, size_id", $dblink);
    if(numrows($records) > 0){
        while($record = dofetch($records)){
            $key1 = $record["vendor_id"]."_".$record["date"];
            if(!isset($vendors[$key1])){
                $vendors[$key1] = [
                    "id" => $record["vendor_id"],
                    "name" => unslash($record["vendor_name_urdu"]),
                    "date" => $record["date"],
                    "gatepass_id" => $record["gatepass_id"],
                    "design" => unslash($record["design_urdu"]),
                    "color" => unslash($record["color_urdu"]),
                ];
            }
            $key = "size_".$record["size_id"];
            $key2 = $key.'i';
            $key3 = $key.'o';
            if(!isset($vendors[$key1][$key2])){
                $vendors[$key1][$key2] = 0;
            }
            if(!isset($vendors[$key1][$key3])){
                $vendors[$key1][$key3] = 0;
            }
            $vendors[$key1][$key2] += $record["vendor_outgoing"];
            $vendors[$key1][$key3] += $record["outgoing"];
        }
    }
    $sn = 1;
    $loop_vendor_id = 0;
    $colspan = 6;
    if(!empty($vendor_id)){
        $colspan--;
    }
    if(!empty($report_type)){
        $colspan--;
    }
    $totals = [];
    $grand_totals = [];
    if(count($vendors) > 0){
        foreach($vendors as $vendor){
            ?>
            <tr>
                <td class="text-center"><?php echo $sn; ?></td>
                <?php if($report_type==""){?>
                    <td><?php echo date_convert($vendor["date"]); ?></td>
                <?php } ?>
                <?php if(empty($vendor_id)){?>
                    <td class="nastaleeq"><?php echo $vendor["name"]; ?></td>
                    <?php
                }
                ?>
                <td class="nastaleeq" align="right"><?php echo $vendor["design"] ?></td>
                <td><?php echo $vendor["gatepass_id"] ?></td>
                <td class="nastaleeq" align="right"><?php echo $vendor["color"]?></td>
                <?php
                $balance = [];
                foreach(['i', 'o'] as $k => $type){
                    $t = 0;
                    foreach($sizes as $size_id => $size){
                        if(!isset($totals[$k]["size_".$size_id])){
                            $totals[$k]["size_".$size_id] = 0;
                        }
                        if(isset($vendor["size_".$size_id.$type])){
                            if(!isset($balance["size_".$size_id])){
                                $balance["size_".$size_id] = 0;
                            }
                            $balance["size_".$size_id] += $vendor["size_".$size_id.$type] * ($k?-1:1);
                            $t += $vendor["size_".$size_id.$type];
                            $totals[$k]["size_".$size_id] += $vendor["size_".$size_id.$type];
                        }
                        ?>
                        <td class="text-right"><?php echo curr_format($vendor["size_".$size_id.$type] ?? "--");?></td>
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
            if($loop_vendor_id != $vendor["id"]){
                if($loop_vendor_id != 0){
                    ?>
                    <tr>
                        <th class="text-right" colspan="<?php echo $colspan;?>">Total</th>
                        <?php
                        for($i = 0; $i < 3; $i++){
                            foreach($sizes as $size_id => $size){
                                if(!isset($grand_totals[$i]["size_".$size_id])){
                                    $grand_totals[$i]["size_".$size_id] = 0;
                                }
                                $grand_totals[$i]["size_".$size_id] += $totals[$i]["size_".$size_id];
                                ?>
                                <th class="text-right"><?php echo curr_format($totals[$i]["size_".$size_id])?></th>
                                <?php
                            }
                            if(!isset($grand_totals[$i]["t"])){
                                $grand_totals[$i]["t"] = 0;
                            }
                            $grand_totals[$i]["t"] += $totals[$i]["t"];
                            ?>
                            <th class="text-center color-<?php echo $i?>"><?php echo curr_format($totals[$i]['t'])?></th>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $totals = [];
                    $total_calculated = true;
                }
                $loop_vendor_id = $vendor["id"];
            }
        }
        ?>
        <tr>
            <th class="text-right" colspan="<?php echo $colspan;?>">Grand Total</th>
            <?php
            if(!isset($total_calculated)){
                for($i = 0; $i < 3; $i++){
                    foreach($sizes as $size_id => $size){
                        if(!isset($grand_totals[$i]["size_".$size_id])){
                            $grand_totals[$i]["size_".$size_id] = 0;
                        }
                        $grand_totals[$i]["size_".$size_id] += $totals[$i]["size_".$size_id];
                    }
                    if(!isset($grand_totals[$i]["t"])){
                        $grand_totals[$i]["t"] = 0;
                    }
                    $grand_totals[$i]["t"] += $totals[$i]["t"];
                }
            }
            for($i = 0; $i < 3; $i++){
                foreach($sizes as $size_id => $size){
                    ?>
                    <th class="text-right"><?php echo curr_format($grand_totals[$i]["size_".$size_id])?></th>
                    <?php
                }
                ?>
                <th class="text-center color-<?php echo $i?>"><?php echo curr_format($grand_totals[$i]['t'])?></th>
                <?php
            }
            ?>
        </tr>
        <?php
    }
    else{
        ?>
        <tr>
            <td colspan="8">No records found.</td>
        </tr>
        <?php
    }
    ?>
    </tbody>
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
