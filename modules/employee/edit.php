<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Employee</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>        	
<form class="form-horizontal form-horizontal-left" role="form" action="employee_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="name">Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="father_name">Father Name</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Father Name" value="<?php echo $father_name; ?>" name="father_name" id="father_name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="phone_number">Phone Number </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Phone Number" value="<?php echo $phone_number; ?>" name="phone_number" id="phone_number" class="form-control" />
            </div>
        </div>
    </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="salary_type">Salary Type </label>
                </div>
                <div class="col-sm-10">

                    <select name="salary_type" id="salary_type" chosen>
                        <option value="0" <?php echo ($salary_type == 0?"selected":"") ?> >monthly</option>
                        <option value="1" <?php echo ($salary_type == 1?"selected":"") ?>>weekly</option>
                        <option value="2" <?php echo ($salary_type == 2?"selected":"") ?>>daily</option>
                        <option value="3" <?php echo ($salary_type == 3?"selected":"") ?>>Staff</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="salary">Salary </label>
                </div>
                <div class="col-sm-10">
                    <input type="text" title="Enter Salary" value="<?php echo $salary; ?>" name="salary" id="salary" class="form-control" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="over_time_per_hour">Over Time Per Hour </label>
                </div>
                <div class="col-sm-10">
                    <input type="text" title="Enter Over Time Per Hour" value="<?php echo $over_time_per_hour; ?>" name="over_time_per_hour" id="over_time_per_hour" class="form-control" />
                </div>
            </div>
        </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="Update" class="btn btn-default btn-l" name="employee_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>