<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["invoice_manage"]["add"])){
	extract($_SESSION["invoice_manage"]["add"]);	
}
else{
	$customer_id="";
	$datetime_added=date("d/m/Y H:i A");
	$date_from=date("01/m/Y");
	$date_to=date("d/m/Y");
	$notes="";
	$machine_id="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Invoice</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Invoice</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="invoice_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="invoice_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="customer_id">Customer Name</label>
            </div>
            <div class="col-sm-10">
                <select name="customer_id" title="Choose Option">
                <?php if($site_url!='http://idreesandatif.burhanpk.com'){?><option value="0">All Customer</option><?php }?>
                    <?php
                    $res=doquery("select * from customer where status=1 order by customer_name", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($customer_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["customer_name"]); ?></option>
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
                    <option value="0">Select Machine</option>
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
                <input type="text" title="Enter datetime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" autocomplete="off" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="date_from">Date From</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date From" value="<?php echo $date_from; ?>" name="date_from" id="date_from" class="form-control date-picker" autocomplete="off" />
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="date_to">Date To</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date To" value="<?php echo $date_to; ?>" name="date_to" id="date_to" class="form-control date-picker" autocomplete="off" />
            </div>
        </div>
  	</div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="notes">Notes </label>
            </div>
            <div class="col-sm-10">
                 <textarea title="Enter Notes" value="" name="notes" id="notes" class="form-control" /><?php echo $notes; ?></textarea>
            </div>
        </div>
    </div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="invoice_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>
