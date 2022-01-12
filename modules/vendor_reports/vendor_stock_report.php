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
if($color_id!=""){
    $extra.=" and color_id='".$color_id."'";
}
if(isset($_GET["design_id"])){
    $_SESSION["reports"]["vendor_stock_report"]["design_id"]=slash($_GET["design_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_report"]["design_id"]))
    $design_id=$_SESSION["reports"]["vendor_stock_report"]["design_id"];
else
    $design_id="";
if($design_id!=""){
    $extra.=" and design_id='".$design_id."'";
}
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
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Vendor Stock Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="vendor_report_manage.php?tab=vendor_stock_report_print"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter" style="display: block">
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="vendor_stock_report" />
                <div class="col-sm-1">
                    <select name="color_id">
                        <option value=""<?php echo ($color_id=="")? " selected":"";?>>Select Color</option>
                    	<?php
                        $rs=doquery( "select * from color order by sortorder", $dblink );
						if( numrows( $rs ) > 0 ) {
							while( $r = dofetch( $rs ) ) {
								?>
								<option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$color_id?' selected':''?>><?php echo unslash( $r[ "title" ] )?></option>
								<?php
							}
						}
						?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <select name="design_id">
                        <option value=""<?php echo ($design_id=="")? " selected":"";?>>Select Design</option>
                        <?php
                        $rs=doquery( "select * from design order by title", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            while( $r = dofetch( $rs ) ) {
                                ?>
                                <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$design_id?' selected':''?>><?php echo unslash( $r[ "title" ] )?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <select name="machine_id">
                        <option value=""<?php echo ($machine_id=="")? " selected":"";?>>Select Machine</option>
                        <?php
                        $rs=doquery( "select * from machine order by title", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            while( $r = dofetch( $rs ) ) {
                                ?>
                                <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$machine_id?' selected':''?>><?php echo unslash( $r[ "title" ] )?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="vendor_id">
                        <option value=""<?php echo ($vendor_id=="")? " selected":"";?>>Select Vendor</option>
                        <?php
                        $rs=doquery( "select * from vendor where status=1 order by vendor_name", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            while( $r = dofetch( $rs ) ) {
                                ?>
                                <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$vendor_id?' selected':''?>><?php echo unslash( $r[ "vendor_name" ] )?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off" />
                </div>
                <div class="col-sm-1">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off" />
                </div>                
                <div class="col-sm-1 text-left">
                    <select name="report_type">
                        <option value=""<?php echo $report_type==''?" selected":""?>>Detailed</option>
                        <option value="1"<?php echo $report_type=='1'?" selected":""?>>Summary</option>
                    </select>
                </div>
                <div class="col-sm-2 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
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
    	<thead>
            <tr>
                <th width="2%" class="text-center" rowspan="2">S.no</th>
                <?php if($report_type==""){?>
                    <th width="5%" rowspan="2">Date</th>
                <?php }?>
                <?php if(empty($_GET["vendor_id"])){?>
                <th width="14%" rowspan="2" class="text-right">Vendor</th>
                <?php }?>
                <th width="10%" colspan="3" class="text-center">Item</th>
                <th width="23%" colspan="<?php echo $colspan?>" class="text-center">Received</th>
                <th width="23%" colspan="<?php echo $colspan?>" class="text-center">Sent</th>
                <th width="23%" colspan="<?php echo $colspan?>" class="text-center">Balance</th>
            </tr>
            <tr>
                <td align="right">Design</td>
                <td>Gatepass</td>
                <td align="right">Color</td>
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
    	</thead>
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
</div>
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
