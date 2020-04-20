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
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Stock Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="report_manage.php?tab=stock_report_print"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter" style="display: block">
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="stock_report" />
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
                <div class="col-sm-2">
                    <select name="customer_id">
                        <option value=""<?php echo ($customer_id=="")? " selected":"";?>>Select Customer</option>
                        <?php
                        $rs=doquery( "select * from customer where status=1 order by customer_name", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            while( $r = dofetch( $rs ) ) {
                                ?>
                                <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$customer_id?' selected':''?>><?php echo unslash( $r[ "customer_name" ] )?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off" />
                </div>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off" />
                </div>                
                <div class="col-sm-2 text-left">
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
                <?php if(empty($_GET["customer_id"])){?>
                <th width="11%" rowspan="2">Customer</th>
                <?php }?>
                <th width="10%" colspan="2" class="text-center">Item</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Received</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Sent</th>
                <th width="24%" colspan="<?php echo $colspan?>" class="text-center">Balance</th>
            </tr>
            <tr>
                <td>Design</td>
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
            $rs = doquery("select a.*, b.id as color_id, b.title as color from design a cross join color b where 1 ".((!empty($color_id)?" and b.id='".$color_id."'":"").(!empty($design_id)?" and a.id='".$design_id."'":""))." order by a.title, b.sortorder", $dblink);
            if(numrows($rs) > 0){
                $sn = 1;
                while($r = dofetch($rs)){
                    $records = doquery("select date, customer_id, ".($report_type==""?"max":"group_concat")."(`incoming`) as incoming, ".($report_type==""?"max":"group_concat")."(`outgoing`) as outgoing from (select a.date, a.customer_id, group_concat(concat(size_id, 'x', quantity)) as incoming, '' as outgoing from incoming a left join incoming_items b on a.id = b.incoming_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id union select a.date, a.customer_id, '' as incoming, group_concat(concat(size_id, 'x', quantity)) as outgoing from delivery a left join delivery_items b on a.id = b.delivery_id where 1 $extra and design_id = '".$r["id"]."' and color_id = '".$r["color_id"]."' group by a.id) as records group by customer_id".($report_type==""?",date":"")." order by customer_id", $dblink);
                    if( numrows($records) > 0 ){
                        while($record = dofetch($records)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $sn; ?></td>
                                <?php if($report_type==""){?>
                                    <td><?php echo date_convert($record["date"]); ?></td>
                                <?php } ?>
                                <?php if(empty($_GET["customer_id"])){?>
                                <td><?php echo get_field($record["customer_id"], "customer", "customer_name" ); ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo unslash($r["title"]) ?></td>
                                <td><?php echo unslash($r["color"])?></td>
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
