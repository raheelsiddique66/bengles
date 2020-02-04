<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["size_manage"]["add"])){
	extract($_SESSION["size_manage"]["add"]);	
}
else{
	$title="";
	$title_urdu="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Size</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Size</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="size_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="size_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
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
            	<label class="form-label" for="title">Title In Urdu</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Title in Urdu" value="<?php echo $title_urdu; ?>" name="title_urdu" id="title_urdu" class="form-control nastaleeq" />
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="size_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>