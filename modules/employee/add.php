<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_manage"]["add"])){
	extract($_SESSION["employee_manage"]["add"]);
}
else{
    $name="";
    $father_name="";
    $phone_number="";
    $salary_type="";
    $salary="";
    $over_time_per_hour="40";
}
?>
<div class="page-header">
	<h1 class="title">Add New Employee</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="employee_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Father Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Father Name" value="<?php echo $father_name; ?>" name="father_name" id="father_name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Phone Number </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Phone Number" value="<?php echo $phone_number; ?>" name="phone_number" id="phone_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Salary Type </label>
            </div>
            <div class="col-sm-10">
                <select name="salary_type" id="salary_type" chosen>
                    <option value="0" selected="false">Select Salary Type</option>
                    <option value="0">monthly</option>
                    <option value="1">weekly</option>
                    <option value="2">daily</option>
                </select>
<!--                <input type="text" title="Enter Salary Type" value="--><?php //echo $salary_type; ?><!--" name="salary_type" id="salary_type" class="form-control" />-->
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Salary </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Salary" value="<?php echo $salary; ?>" name="salary" id="salary" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Over Time Per Hour </label>
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
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="employee_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>