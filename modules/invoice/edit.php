<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Invoice</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Invoice</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="invoice_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="invoice_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="customer_id">Customer Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="customer_id" title="Choose Option">
                    <option value="0">Select Customer</option>
                    <?php
                    $res=doquery("select * from customer where status=1 order by id", $dblink);
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
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="invoice_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>