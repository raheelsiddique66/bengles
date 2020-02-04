<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["customer_manage"]["add"])){
	extract($_SESSION["customer_manage"]["add"]);	
}
else{
    $customer_name="";
    $phone="";
    $address="";
	$customer_name_urdu="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Customer</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Customer</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="customer_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="customer_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Customer Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $customer_name; ?>" name="customer_name" id="customer_name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Customer Name In Urdu</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Customer in Urdu" value="<?php echo $customer_name_urdu; ?>" name="customer_name_urdu" id="customer_name_urdu" class="form-control nastaleeq" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="phone">Phone </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Phone #" value="<?php echo $phone; ?>" name="phone" id="phone" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="address">Address </label>
            </div>
            <div class="col-sm-10">
                <textarea title="Enter Address" name="address" id="address" class="form-control"><?php echo $address; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="customer_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>