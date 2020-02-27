<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Employee Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>        	
<form class="form-horizontal form-horizontal-left" role="form" action="employee_payment_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
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
            	<label class="form-label" for="amount">Amount </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" />
            </div>
        </div>
    </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="salary_type">Account </label>
                </div>
                <div class="col-sm-10">

                    <select name="account_id" title="Choose Option" class="select_multiple">
                    <option value="0">Select Account</option>
                        <?php
                        $res=doquery("select * from account where status=1 order by id", $dblink);
                        if(numrows($res)>0){
                            while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>"<?php echo($account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="Update" class="btn btn-default btn-l" name="employee_payment_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>