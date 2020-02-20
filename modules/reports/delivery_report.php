<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["delivery_report"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["delivery_report"]["date_from"]))
	$date_from=$_SESSION["reports"]["delivery_report"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["delivery_report"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["delivery_report"]["date_to"]))
	$date_to=$_SESSION["reports"]["delivery_report"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if(isset($_GET["customer_id"])){
	$customer_id=slash($_GET["customer_id"]);
	$_SESSION["reports"]["delivery_report"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["reports"]["delivery_report"]["customer_id"]))
	$customer_id=$_SESSION["reports"]["delivery_report"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Delivery Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="report_manage.php?tab=delivery_report_print"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="delivery_report" />
                <span class="col-sm-1">Customer</span>
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
                <span class="col-sm-1 text-to">From</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off" />
                </div>
                <span class="col-sm-1 text-to">To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off" />
                </div>                
                <div class="col-sm-3 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Incoming</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from incoming a left join incoming_items b on a.id = b.incoming_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Incoming Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
    <table class="table table-hover list">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Washing</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from washing a left join washing_items b on a.id = b.washing_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Washing Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
    <table class="table table-hover list">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Delivery</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from delivery a left join delivery_items b on a.id = b.delivery_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Delivery Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
