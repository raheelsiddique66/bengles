<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_manage"]["add"])){
	extract($_SESSION["employee_manage"]["add"]);
}
else{
    $name="";
    $name_in_urdu="";
    $father_name="";
    $phone_number="";
    $salary_type="";
    $salary="";
    $over_time_per_hour="40";
    $machine_id = "";
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
            	<label class="form-label" for="name_in_urdu">Name In Urdu</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name in Urdu" value="<?php echo $name_in_urdu; ?>" name="name_in_urdu" id="name_in_urdu" class="form-control nastaleeq" />
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
                    <option value="0">monthly</option>
                    <option value="1">weekly</option>
                    <option value="2">daily</option>
                    <option value="3">Staff</option>
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