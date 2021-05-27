<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["account_manage"]["add"])){
	extract($_SESSION["account_manage"]["add"]);	
}
else{
	$title="";
	$description="";
	$balance="";
	$is_petty_cash="";
	$type="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Account</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Account</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="account_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="account_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Title </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label for="type" class="form-label">Type </label>
            </div>
            <div class="col-sm-10">
                <select name="type" id="type" title="Choose Option">
                    <option value="">Select Type</option>
                    <?php
                    foreach ($account_type as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$type?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="description">Description </label>
            </div>
            <div class="col-sm-10">
                <textarea title="Enter Description" name="description" id="description" class="form-control"><?php echo $description; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="balance">Balance </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Balance" value="<?php echo $balance; ?>" name="balance" id="balance" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="is_petty_cash">Is Pretty Cash </label>
            </div>
            <div class="col-sm-10">
                <select name="is_petty_cash" id="is_petty_cash">
                    <option value="0"<?php echo $is_petty_cash=="0"?' selected="selected"':''?>>No</option>
                    <option value="1"<?php echo $is_petty_cash=="1"?' selected="selected"':''?>>Yes</option>
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
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="account_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>