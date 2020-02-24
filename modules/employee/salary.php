<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=false;
if( isset($_GET["date_from"]) ){
	$_SESSION["employee"]["salary"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["employee"]["salary"]["date_from"]) && !empty($_SESSION["employee"]["salary"]["date_from"])){
	$date_from = $_SESSION["employee"]["salary"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["employee"]["salary"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["employee"]["salary"]["date_to"]) && !empty($_SESSION["employee"]["salary"]["date_to"])){
	$date_to = $_SESSION["employee"]["salary"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and date<='".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
?>
<div ng-app="salary" ng-controller="salaryController" id="salaryController">
    <div class="page-header">
        <h1 class="title">Employees</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Employees Salary</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> 
                <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
                <div class="btn-group" role="group" aria-label="..."> <a href="employee_manage.php" class="btn btn-light editproject">Back to List</a> </div>
            </div> 
        </div> 
    </div>
    <ul class="topstats clearfix search_filter" style="display: block">
        <li class="col-xs-12 col-lg-12 col-sm-12">
            <div>
                <form class="form-horizontal" action="" method="get">
                    <input type="hidden" name="tab" value="salary" />
                    <div class="col-sm-3">
                        <input type="text" title="Enter String" value="<?php echo $date_from?>" name="date_from" id="search" class="form-control date-picker" autocomplete="off" />  
                    </div>
                    <div class="col-sm-3">
                        <input type="text" title="Enter String" value="<?php echo $date_to?>" name="date_to" id="search" class="form-control date-picker" autocomplete="off" />  
                    </div>
                    <div class="col-sm-3 text-left">
                        <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                        <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                    </div>
                </form>
            </div>
        </li>
    </ul>
    <div class="panel-body table-responsive">
        <table class="table table-hover list">
            <thead>
                <tr>
                    <th width="5%" class="text-center">S.No</th>
                    <th width="15%">Employee Name</th>
                    <th width="15%">Father Name</th>
                    <th width="10%">01/02/2020</th>
                    <th width="10%">01/02/2020</th>
                    <th width="10%">01/02/2020</th>
                    <th width="10%">01/02/2020</th>
                    <th width="10%">01/02/2020</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="employee in employees">
                    <td class="text-center">{{ $index+1 }}</td>
                    <td>{{employee.name}}</td>
                    <td>{{employee.father_name}}</td>
                    <td><input type="text" /></td>
                    <td><input type="text" /></td>
                    <td><input type="text" /></td>
                    <td><input type="text" /></td>
                    <td><input type="text" /></td>
                </tr>  
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-10">
                <input type="submit" value="Save Record" class="btn btn-default btn-l" name="save_record" title="Update Record" />
            </div>
        </div>
    </div>
</div>