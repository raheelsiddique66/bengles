<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title">Employee Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employees Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="employee_payment_manage.php?tab=add" class="btn btn-light editproject">Add New Employee Payment</a>
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="employee_payment_manage.php?tab=print"><i class="fa fa-print" aria-hidden="true"></i></a>
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-2">
                	<select name="employee_id" id="employee_id" class="select_multiple custom_select">
                        <option value=""<?php echo ($employee_id=="")? " selected":"";?>>Select Employee</option>
                        <?php
                            $res=doquery("select * from employees order by name",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"])?></option>
                            	<?php
                                }
                            }	
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                	<select name="account_id" id="account_id" class="select_multiple custom_select">
                        <option value=""<?php echo ($account_id=="")? " selected":"";?>>Select Account</option>
                        <?php
                            $res=doquery("select * from account order by title",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                            	<?php
                                }
                            }	
                        ?>
                    </select>
                </div>
                <div class="col-sm-2 margin-btm-5">
                  <input type="text" title="Enter Date From" value="<?php echo $date_from;?>" placeholder="Date From" name="date_from" id="date_from" class="form-control date-picker" autocomplete="off" />  
                </div>
                <div class="col-sm-2 margin-btm-5">
                  <input type="text" title="Enter Date To" value="<?php echo $date_to;?>" placeholder="Date To" name="date_to" id="date_to" class="form-control date-picker" autocomplete="off" />  
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
                <th width="5%" class="text-center">S.No</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="20%">Date</th>
                <th width="30%">Employee Name</th>
                <th width="20%">Account</th>
                <th width="10%">Amount</th>
                <th width="10%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
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
                        <td><?php echo get_field($r["employee_id"], "employees", "name"); ?></td>
                        <td><?php echo get_field($r["account_id"], "account", "title"); ?></td>
                        <td><?php echo unslash($r["amount"]); ?></td>
                        <td class="text-center">
                            <a href="employee_payment_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="employee_payment_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="4" class="actions">
                        <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "employees", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="7"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
     </table>
</div>