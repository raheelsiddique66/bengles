<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title">Manage Delivery</h1>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="...">
        	<a href="delivery_manage.php?tab=addedit" class="btn btn-light editproject">Add New Delivery</a>
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
            <a class="btn print-btn" href="delivery_manage.php?tab=report"><i class="fa fa-print" aria-hidden="true"></i></a>
            <a href="delivery_manage.php?tab=report_total" class="btn btn-light editproject">Report Total</a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-2 margin-btm-5">
                    <input placeholder="Date From" type="text" title="Date From" value="<?php echo $date_from;?>" name="date_from" id="date_from" class="date-picker form-control" autocomplete="off" />
                </div>
                <div class="col-sm-2 margin-btm-5">
                    <input placeholder="Date To" type="text" title="Date To" value="<?php echo $date_to;?>" name="date_to" id="date_to" class="date-picker form-control" autocomplete="off" />
                </div>
                <div class="col-sm-2">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-2 col-xs-8">
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option value=""<?php echo ($customer_id=="")? " selected":"";?>>Select Customer</option>
                        <?php
                            $res=doquery("select * from customer order by customer_name",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($customer_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["customer_name"])?></option>
                                <?php
                                }
                            }	
                        ?>
                    </select>
                </div>
                <div class="col-sm-2 col-xs-8">
                    <select name="machine_id" id="machine_id" class="form-control">
                        <option value=""<?php echo ($machine_id=="")? " selected":"";?>>Select Machine</option>
                        <?php
                            $res=doquery("select * from machine order by title",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($machine_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                                <?php
                                }
                            }	
                        ?>
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
    	<thead>
            <tr>
                <th width="2%" class="text-center">S.no</th>
                <th class="text-center" width="3%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="10%">Date</th>
                <th width="10%">Gatepass</th>
                <th width="12%">Customer</th>
                <th width="8%">Claim</th>
                <th width="10%">Labour</th>
                <th width="30%">Items</th>
                <th width="10%" class="text-right">Payment</th>
                <th class="text-center" width="3%">Status</th>
                <th class="text-center" width="10%">Actions</th>
            </tr>
    	</thead>
    	<tbody>
			<?php
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $colors = [];
                $rs2 = doquery("select * from color order by sortorder", $dblink);
                while($r2=dofetch($rs2)){
                    $colors[$r2["id"]] = unslash($r2["title"]);
                }
                $designs = [];
                $rs3 = doquery("select * from design order by sortorder", $dblink);
                while($r3=dofetch($rs3)){
                    $designs[$r3["id"]] = unslash($r3["title"]);
                }
                $sizes = [];
                $rs4 = doquery("select * from size order by sortorder", $dblink);
                while($r4=dofetch($rs4)){
                    $sizes[$r4["id"]] = unslash($r4["title"]);
                }
                $sn=1;
                while($r=dofetch($rs)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo $r["gatepass_id"]?></td>
                        <td><?php echo get_field( unslash($r["customer_id"]), "customer", "customer_name" ); ?></td>
                        <td><?php echo  unslash($r["claim"]); ?></td>
                        <td><?php echo get_field( unslash($r["labour_id"]), "labour", "name" ); ?></td>
                        <td>
                            <table class="table table-hover list">
                                <thead>
                                <tr>
                                    <td>Machine</td>
                                    <td>Design</td>
                                    <td>Color</td>
                                    <?php
                                    foreach($sizes as $size){
                                        ?>
                                        <th class="text-center"><?php echo $size;?></th>
                                        <?php  
                                    }
                                    ?>
                                    <th class="color3-bg text-center">Total</th>
                                    <th class="color3-bg text-center">Price</th>
                                    <th class="color3-bg text-center">Total</th>
                                </tr>
                                </thead>
                                <?php
                                $rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from delivery_items where delivery_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
                                if(numrows($rs1)>0){
                                    $price = 0;
                                    $total_price = 0;
                                    $totals = [];
                                    foreach($sizes as $size_id => $size){
                                        $totals[$size_id] = 0;
                                    }
                                    while($r1=dofetch($rs1)){
                                        $price += $r1["unit_price"];
                                        ?>
                                        <tr>
                                            <td><?php echo get_field($r1["machine_id"], "machine", "title" ); ?></td>
                                            <td><?php echo $designs[$r1["design_id"]]?></td>
                                            <td><?php echo $colors[$r1["color_id"]]?></td>
                                            <?php
                                            $quantities = [];
                                            $t = 0;
                                            foreach(explode(",", $r1["sizes"]) as $size){
                                                $size = explode("x", $size);
                                                $quantities[$size[0]]=$size[1];
                                                $t += $size[1];
                                            }
                                            foreach($sizes as $size_id => $size){
                                                $totals[$size_id] += isset($quantities[$size_id])?$quantities[$size_id]:0;
                                                ?>
                                                <td class="text-right"><?php echo isset($quantities[$size_id])?$quantities[$size_id]:"--";?></td>
                                                <?php
                                            }
                                            $total_price +=  $t * $r1["unit_price"];
                                            ?>
                                            <td class="text-right color3-bg"><?php echo $t?></td>
                                            <td class="text-right color3-bg"><?php echo $r1["unit_price"]?></td>
                                            <td class="text-right color3-bg"><?php echo $t * $r1["unit_price"]?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <?php
                                        $t = 0;
                                        foreach($totals as $total){
                                            $t += $total;
                                            ?>
                                            <td class="text-right color3-bg"><?php echo $total?></td>
                                            <?php
                                        }
                                        ?>
                                        <td class="text-right color3-bg"><?php echo $t?></td>
                                        <td class="text-right color3-bg"><?php echo $price?></td>
                                        <td class="text-right color3-bg"><?php echo $total_price?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </td>
                        <td class="text-right color3-bg"><?php echo get_field($r["customer_payment_id"], "customer_payment", "amount" ); ?></td>
                        <td class="text-center">
                            <a href="delivery_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                                <?php
                                if($r["status"]==0){
                                    ?>
                                    <img src="images/offstatus.png" alt="Off" title="Set Status On">
                                    <?php
                                }
                                else{
                                    ?>
                                    <img src="images/onstatus.png" alt="On" title="Set Status Off">
                                    <?php
                                }
                                ?>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="delivery_manage.php?tab=addedit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="delivery_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                            <a href="delivery_manage.php?tab=print_receipt&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/view.png"></a>
                        </td>
                    </tr>
                    <?php
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="7" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "delivery", $sql, $pageNum)?></td>
                </tr>
                <?php
            }
            else{
                ?>
                <tr>
                    <td colspan="11"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
