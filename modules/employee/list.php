<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["salary_type"])){
	$salary_type=slash($_GET["salary_type"]);
	$_SESSION["employee_manage"]["list"]["salary_type"]=$salary_type;
}
if(isset($_SESSION["employee_manage"]["list"]["salary_type"]))
	$salary_type=$_SESSION["employee_manage"]["list"]["salary_type"];
else
	$salary_type="";
if($salary_type!=""){
	$extra.=" and salary_type='".$salary_type."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["employee_manage"]["list"]["q"]=$q;
}
if(isset($_SESSION["employee_manage"]["list"]["q"]))
	$q=$_SESSION["employee_manage"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and name like '%".$q."%' or father_name like '%".$q."%' ";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Employee</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employees</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
            <a href="employee_manage.php?tab=salary" class="btn btn-light editproject">Salary</a>
        	<a href="employee_manage.php?tab=add" class="btn btn-light editproject">Add New Employee</a>
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-3">
                	<select name="salary_type" id="salary_type" title="Choose Option">
                        <option value="">Select Salary Type</option>
                        <?php
						foreach ($salary_types as $key=>$value) {
                            ?>
                            <option value="<?php echo $key?>"<?php echo ($salary_type!="" && $key==$salary_type)?' selected="selected"':""?>><?php echo $value ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
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
                <th width="5%" class="text-center">S.No</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="15%">Name</th>
                <th width="15%">Father Name</th>
                <th width="10%">Phone Number</th>
                <th width="10%">Salary Type</th>
                <th width="10%">Salary</th>
                <th width="11%">Over Time Per hour</th>
                <th width="8%">Advance</th>
                <th width="5%" class="text-center">Status</th>
                <th width="15%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql="select * from employees where 1 $extra order by name";
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
                        <td><?php echo unslash($r["name"]); ?></td>
                        <td><?php echo unslash($r["father_name"]); ?></td>
                        <td><?php echo unslash($r["phone_number"]); ?></td>
                        <td><?php
                            if($r["salary_type"] == 0) {
                                echo "Monthly";
                            }
                            elseif($r["salary_type"] == 1) {
                                echo "Weekly";
                            }
                            elseif($r["salary_type"] == 2) {
                                echo "Daily";
                            }
                         ?></td>
                        <td><?php echo unslash($r["salary"]); ?></td>
                        <td><?php echo unslash($r["over_time_per_hour"]); ?></td>
                        <td>
                            <a class="btn btn-default btn-l fancybox_iframe" href="employee_payment_manage.php?tab=add&employee_id=<?php echo $r['id'];?>">Advance</a>
                        </td>
                        <td class="text-center">
                            <a href="employee_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                            <a href="employee_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="employee_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                            <a href="employee_manage.php?tab=employee_report&id=<?php echo $r['id'];?>" class="download-icon"><i class="fa fa-download" aria-hidden="true"></i></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="7" class="actions">
                        <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="4" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "employees", $sql, $pageNum)?></td>
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