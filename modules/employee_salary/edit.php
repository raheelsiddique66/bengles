<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Employee Salary</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee Salary</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_salary_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>        	
<form class="form-horizontal form-horizontal-left" role="form" action="employee_salary_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="name">Name Employee</label>
            </div>
            <div class="col-sm-10">
                <select name="employee_id" title="Choose Option" class="select_multiple">
                    <option value="0">Select Employee</option>
                    <?php
                    $res=doquery("select * from employees where status=1 order by id", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                            ?>
                            <option value="<?php echo $rec["id"]?>"<?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="machine_id">Machine</label>
            </div>
            <div class="col-sm-10">
                <select name="machine_id" title="Choose Option">
                    <option value="0">All Machine</option>
                    <?php
                    $res=doquery("select * from machine where status=1 order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                            ?>
                            <option value="<?php echo $rec["id"]?>"<?php echo($machine_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="date">Date</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo date_convert($date); ?>" name="date" id="date" class="form-control date-picker" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="salary_rate">Salary Rate </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Salary Rate" value="<?php echo $salary_rate; ?>" name="salary_rate" id="salary_rate" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="over_time_rate">Over Time Rate </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Over Time Rate" value="<?php echo $over_time_rate; ?>" name="over_time_rate" id="over_time_rate" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="calculated_salary">Calculated Salary </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Calculated Salary" value="<?php echo $calculated_salary; ?>" name="calculated_salary" id="calculated_salary" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="Update" class="btn btn-default btn-l" name="employee_salary_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>