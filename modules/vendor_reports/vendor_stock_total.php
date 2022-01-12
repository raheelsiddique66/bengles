<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
if(isset($_GET["date_from"])){
	$_SESSION["reports"]["vendor_stock_total"]["date_from"]=slash($_GET["date_from"]);
}

if(isset($_SESSION["reports"]["vendor_stock_total"]["date_from"]))
	$date_from=$_SESSION["reports"]["vendor_stock_total"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))."'";
}
if(isset($_GET["date_to"])){
	$_SESSION["reports"]["vendor_stock_total"]["date_to"]=slash($_GET["date_to"]);
}

if(isset($_SESSION["reports"]["vendor_stock_total"]["date_to"]))
	$date_to=$_SESSION["reports"]["vendor_stock_total"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))."'";
}

if(isset($_GET["machine_id"])){
    $_SESSION["reports"]["vendor_stock_total"]["machine_id"]=slash($_GET["machine_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_total"]["machine_id"]))
    $machine_id=$_SESSION["reports"]["vendor_stock_total"]["machine_id"];
else
    $machine_id="";
if($machine_id!=""){
    $extra.=" and machine_id='".$machine_id."'";
}
if(isset($_GET["vendor_id"])){
    $_SESSION["reports"]["vendor_stock_total"]["vendor_id"]=slash($_GET["vendor_id"]);
}
if(isset($_SESSION["reports"]["vendor_stock_total"]["vendor_id"]))
    $vendor_id=$_SESSION["reports"]["vendor_stock_total"]["vendor_id"];
else
    $vendor_id="";
if($vendor_id!=""){
    $extra.=" and vendor_id='".$vendor_id."'";
}
?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Vendor Stock Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="vendor_report_manage.php?tab=vendor_stock_total_print"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter" style="display: block">
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="vendor_stock_total" />
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
                <th width="5%" rowspan="2">Date</th>
                <th width="11%" rowspan="2">Vendor</th>
                <th width="24%" colspan="" class="text-center">Received</th>
                <th width="24%" colspan="" class="text-center">Sent</th>
                <th width="24%" colspan="" class="text-center">Balance</th>
            </tr>
    	</thead>
    	<tbody>
            <?php
            $totals = [];
            for($i = 0; $i < 3; $i++){
                foreach($sizes as $size_id => $size){
                    $totals[$i][$size_id] = 0;
                }
                $totals[$i]['t'] = 0;
            }
            $vendors = doquery("select * from vendor where status = 1".($vendor_id!=""?" and id='".$vendor_id."'":"")." order by vendor_name", $dblink);
            if(numrows($vendors)>0){
                while($vendor = dofetch($vendors)){
                        $sn = 1;
                            $sql = "select date, vendor_id, concat(size_id, 'x', sum(vendor_outgoing)) as vendor_outgoing, concat(size_id, 'x', sum(outgoing)) as outgoing from (select a.date, a.vendor_id, size_id, sum(quantity) as vendor_outgoing, 0 as outgoing from vendor_outgoing a inner join vendor_outgoing_items b on a.id = b.vendor_outgoing_id where 1 $extra and a.vendor_id='".$vendor["id"]."' group by a.vendor_id union select a.date, a.vendor_id, size_id, 0 as vendor_outgoing, sum(quantity) as outgoing from vendor_delivery a inner join vendor_delivery_items b on a.id = b.vendor_delivery_id where 1 $extra and a.vendor_id='".$vendor["id"]."' group by a.vendor_id) as records group by vendor_id order by vendor_id";
                            $records = doquery($sql, $dblink);
                            if( numrows($records) > 0 ){
                                while($record = dofetch($records)){
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sn++; ?></td>
                                            <td><?php echo date_convert($record["date"]); ?></td>
                                            <td><?php echo get_field($record["vendor_id"], "vendor", "vendor_name" ); ?></td>
                                        <?php
                                        $vendor_outgoing = [];
                                        $outgoing = [];
                                        for($i = 0; $i < 2; $i++){
                                            $type = $i==0?"vendor_outgoing":"outgoing";
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
                                                }
                                            }
                                            $$type = $quantities;
                                            $totals[$i]['t'] += $t;
                                            ?>
                                            <th class="text-center color-<?php echo $i?>"><?php echo $t?></th>
                                            <?php
                                        }
                                        $t = 0;
                                        foreach($sizes as $size_id => $size){
                                            $b = (isset($vendor_outgoing[$size_id])?$vendor_outgoing[$size_id]:0)-(isset($outgoing[$size_id])?$outgoing[$size_id]:0);
                                            $t += $b;
                                            $totals[2][$size_id] += $b;
                                            ?>
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
                        $colspan = 3;
                        ?>
                        <tr>
                            <th class="text-right" colspan="<?php echo $colspan;?>">Total</th>
                            <?php
                            for($i = 0; $i < 3; $i++){
                                foreach($sizes as $size_id => $size){
                                    ?>
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
