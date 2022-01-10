<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["vendor_payment_manage"]["add"])){
	extract($_SESSION["vendor_payment_manage"]["add"]);	
}
else{
	$vendor_id="";
	$datetime_added=date("d/m/Y H:i A");
	$amount="";
	$discount="";
	$account_id="";
	$details="";
	$machine_id="";
	$claim="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Vendor Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Vendor Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="vendor_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="vendor_payment_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="vendor_id">Vendor Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="vendor_id" title="Choose Option">
                    <option value="0">Select Vendor</option>
                    <?php
                    $res=doquery("select * from vendor where status=1 order by vendor_name", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($vendor_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["vendor_name"]); ?></option>
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
                <label class="form-label" for="datetime">Datetime</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter datetime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="discount">Discount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Discount" value="<?php echo $discount; ?>" name="discount" id="discount" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="amount">Amount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="claim">Claim</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Claim" value="<?php echo $claim; ?>" name="claim" id="claim" class="form-control" >
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="account_id">Paid To </label>
            </div>
            <div class="col-sm-10">
                <select name="account_id" title="Choose Option">
                    <option value="">Select Account</option>
                    <?php
                    $res=doquery("select * from account where status=1 order by title", $dblink);
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
            	<label class="form-label" for="details">Details </label>
            </div>
            <div class="col-sm-10">
                 <textarea title="Enter Details" value="" name="details" id="details" class="form-control" /><?php echo $details; ?></textarea>
            </div>
        </div>
    </div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="vendor_payment_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>