<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "id" ] ) ) {
	$id = slash( $_GET[ "id" ] );
}
else {
	$id = 0;
}
?>
<div ng-app="washing" ng-controller="washingController" id="washingController">
    <div style="display:none">{{washing_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Washing</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Washing</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> <a href="washing_manage.php" class="btn btn-light editproject">Back to List</a> </div>
        </div>
    </div>
	<?php
        $i=0;
    ?>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="washing_date">Date *</label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="washing.date" class="form-control date-picker" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="customer_id">Customer *</label>
                </div>
                <div class="col-sm-10">
                    <select id="customer_id" ng-model="washing.customer_id" chosen>
                        <option value="0" selected="false">Select Customer</option>
                        <option ng-repeat="customer in customers" value="{{ customer.id }}">{{ customer.customer_name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label">Washing Items <span class="manadatory">*</span></label>
                </div>
                <div class="col-sm-10">
                    <div class="panel-body table-responsive">
                        <table class="table table-hover list">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">S.no</th>
                                    <th width="15%">Color</th>
                                    <th width="15%">Size</th>
                                    <th width="15%">Design</th>
                                    <th class="text-right" width="10%">Quantity</th>
                                    <th class="text-center" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="washing_item in washing.washing_items">
                                    <td class="text-center serial_number">{{ $index+1 }}</td>
                                    <td>
                                        <select title="Choose Option" ng-model="washing.washing_items[$index].color_id">
                                            <option value="">Select Color</option>
                                            <option ng-repeat="color in colors" value="{{ color.id }}">{{ color.title }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select title="Choose Option" ng-model="washing.washing_items[$index].size_id">
                                            <option value="">Select Size</option>
                                            <option ng-repeat="size in sizes" value="{{ size.id }}">{{ size.title }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select title="Choose Option" ng-model="washing.washing_items[$index].design_id">
                                            <option value="">Select Design</option>
                                            <option ng-repeat="design in designs" value="{{ design.id }}">{{ design.title }}</option>
                                        </select>
                                    </td>
                                    <td class="text-right"><input type="text" ng-change="update_grand_total( $index )" ng-model="washing.washing_items[$index].quantity" /></td>                        
                                    <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a></td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total Items</th>
                                    <th class="text-right">{{ washing.quantity }}</th>
                                    <th class="text-right">&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="alert alert-danger" ng-show="errors.length > 0">
                        <p ng-repeat="error in errors">{{error}}</p>
                    </div>
                    <button type="submit" ng-disabled="processing" class="btn btn-default btn-l" ng-click="save_washing()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>input,select{ width:100%;}</style>