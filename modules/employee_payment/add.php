<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_payment_manage"]["add"])){
	extract($_SESSION["employee_payment_manage"]["add"]);
}
else{
    $accounts = doquery("select id from account where is_petty_cash = 1",$dblink);
    if(numrows($accounts)>0){
        $account=dofetch($accounts);
        $account_id=$account["id"];
    }
    if(isset($_GET["employee_id"])){
        $employee_id = $_GET["employee_id"];
    }
    else{
        $employee_id ="";
    }

    $date=date("d/m/Y");
    $amount="";

}
?>
<div class="page-header">
	<h1 class="title">Add New Employee Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="employee_payment_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Employee Name </label>
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
                <label class="form-label" for="title">Date </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo $date; ?>" name="date" id="date" class="form-control date-picker" >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Amount </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Accounts </label>
            </div>
            <div class="col-sm-10">
                <select name="account_id" title="Choose Option"class="select_multiple">
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
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="employee_payment_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>