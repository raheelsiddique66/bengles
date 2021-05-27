<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["scheduled_transaction_manage"]["add"])){
	extract($_SESSION["scheduled_transaction_manage"]["add"]);	
}
else{
	$type = 0;
	$schedule = 0;
	$day_number = 0;
	$account_id="";
	$reference_id="";
	$datetime_added=date("d/m/Y H:i A");
	$amount="";
	$details="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Scheduled Transaction</h1>
  	<ol class="breadcrumb">
    	<li class="active">
        	<?php
            if( !isset( $_SESSION["scheduled_transaction"]["list"]["project_id"] ) || $_SESSION["scheduled_transaction"]["list"]["project_id"] == "" ) {
				echo "All Scheduled Transactions";
			}
			else if( $_SESSION["scheduled_transaction"]["list"]["project_id"] == "0" ) {
				echo "Administrative Scheduled Transaction";
			}
			else {
				echo "Project: ".get_field( $_SESSION["scheduled_transaction"]["list"]["project_id"], "project" );
			}
			?>
        </li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="scheduled_transaction_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="scheduled_transaction_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="type">Type<span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="type" title="Choose Option">
                    <option value="0"<?php echo($type==0)?"selected":"";?>>Transaction</option>
                    <option value="1"<?php echo($type==1)?"selected":"";?>>Expense</option>
                    <option value="2"<?php echo($type==2)?"selected":"";?>>Salary Payment</option>
                </select>
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="schedule">Schedule<span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="schedule" title="Choose Option">
                    <?php
                    foreach( $schedule_array as $k => $v ) {
                        ?>
                        <option value="<?php echo $k?>"<?php echo($schedule==$k)?"selected":"";?>><?php echo $v; ?></option>
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
                <label class="form-label" for="day_number">Which Day<span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="day_number" title="Choose Option">
                    <option value="">No Value</option>
                    <?php
                    foreach( $day_name as $k => $v ) {
                        ?>
                        <option value="<?php echo $k+1?>"<?php echo($day_number==$k+1)?"selected":"";?>>First <?php echo $v; ?></option>
                     	<?php			
                    }
                    for( $i = 1; $i <= 30; $i++ ) {
                        ?>
                        <option value="<?php echo $i+100?>"<?php echo($day_number==($i+100))?"selected":"";?>>After <?php echo $i; ?> Day<?php echo $i>1?"s":""?></option>
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
                <label class="form-label" for="account_id">Account To/Expense Category/Employee<span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="account_id" title="Choose Option">
                    <option value="0">Select Account To/Expense Category/Employee</option>
                    <optgroup label="Accounts">
					<?php
                    $res=doquery("select * from account where status=1".(!empty($_SESSION["scheduled_transaction"]["list"]["project_id"])?" and id in (select account_id from project_2_account where project_id = '".$_SESSION["scheduled_transaction"]["list"]["project_id"]."')":"")." order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($type==0&&$account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                     	<?php			
                        }			
                    }
					?>
					</optgroup>
                    <optgroup label="Expense Categories">
					<?php
					$res=doquery("select * from expense_category where status=1".(!empty($_SESSION["scheduled_transaction"]["list"]["project_id"])?" and id in (select expense_category_id from project_2_expense_category where project_id = '".$_SESSION["expense"]["list"]["project_id"]."')":"")." order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($type==1&&$account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                     	<?php			
                        }			
                    }
                    ?>
                    <optgroup label="Employees">
                    <?php
					$res=doquery("select * from admin where status=1 and monthly_salary>0", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($type==2&&$account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
                     	<?php			
                        }			
                    }
                    ?>
                    </optgroup>
                </select>
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="reference_id">Account From/Payment Account <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
            	<select name="reference_id" id="reference_id" title="Choose Option">
                    <option value="0">Select Account From/Payment Account</option>
                    <?php
                    $res=doquery("select * from account where status=1".(!empty($_SESSION["scheduled_transaction"]["list"]["project_id"])?" and id in (select account_id from project_2_account where project_id = '".$_SESSION["scheduled_transaction"]["list"]["project_id"]."')":"")." order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($reference_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
            	<label class="form-label" for="datetime_added">Date/Time</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date/Time" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" />
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
            	<label class="form-label" for="details">Details </label>
            </div>
            <div class="col-sm-10">
                <textarea title="Enter Details" name="details" id="details" class="form-control"><?php echo $details;?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="scheduled_transaction_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>