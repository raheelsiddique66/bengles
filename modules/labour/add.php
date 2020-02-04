<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["labour_manage"]["add"])){
	extract($_SESSION["labour_manage"]["add"]);	
}
else{
	$name="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Labour</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Labour</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="labour_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="labour_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
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
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="labour_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>