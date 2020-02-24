<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "id" ] ) ) {
	$id = slash( $_GET[ "id" ] );
}
else {
	$id = 0;
}
?>
<style>
    .labour-add {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 300px;
        background-color: #fff;
        border: solid 1px;
        transform: translate(-50%,-50%);
        z-index: 999;
    }
    .fancybox-close-small {
        position: absolute;
        top: 0;
        right: 0;
        width: 44px;
        height: 44px;
        padding: 0;
        margin: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        z-index: 10;
        cursor: pointer;
    }
</style>
<div ng-app="incoming" ng-controller="incomingController" id="incomingController">
    <div style="display:none">{{incoming_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Incoming</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Incoming</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> <a href="incoming_manage.php" class="btn btn-light editproject">Back to List</a> </div>
        </div>
    </div>
	<?php
        $i=0;
    ?>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="incoming_date">Date *</label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="incoming.date" class="form-control date-picker" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="customer_id">Customer *</label>
                </div>
                <div class="col-sm-10">
                    <select id="customer_id" ng-model="incoming.customer_id" chosen>
                        <option value="0" selected="false">Select Customer</option>
                        <option ng-repeat="customer in customers" value="{{ customer.id }}">{{ customer.customer_name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="labour-add" ng-show="showPopup">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopup()"></button>
            <div style="padding: 20px;">
                Labour Name: <span><input id="cursor_focus" type="text" placeholder="Enter Labour Name" ng-model="labour.name"></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_labour()" title="Submit Labour"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="labour_id">Labour </label>
                </div>
                <div class="col-sm-10">
                    <select id="labour_id" ng-model="incoming.labour_id" chosen>
                        <option value="0" selected="false">Select Labour</option>
                        <option ng-repeat="labour in labours" value="{{ labour.id }}">{{ labour.name }}</option>
                    </select>
                    <a href="" class="add_customer_link" ng-click="togglePopup()">+</a>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="incoming.gatepass_id">Gatepass </label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="incoming.gatepass_id" class="form-control" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label">Incoming Items <span class="manadatory">*</span></label>
                </div>
                <div class="col-sm-10">
                    <div class="panel-body table-responsive">
                        <table class="table table-hover list">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center" rowspan="2">S.no</th>
                                    <th width="10%" rowspan="2">Color</th>
                                    <th width="10%" rowspan="2">Design</th>
                                    <th class="text-center" width="30%" colspan="{{ sizes.length+1 }}">Sizes</th>
                                    <th class="text-center" width="5%">Actions</th>
                                </tr>
                                <tr>
                                    <td ng-repeat="size in sizes">{{ size.title }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="incoming_item in incoming.incoming_items">
                                    <td class="text-center serial_number">{{ $index+1 }}</td>
                                    <td>
                                        <select title="Choose Option" ng-model="incoming.incoming_items[$index].color_id">
                                            <option value="">Select Color</option>
                                            <option ng-repeat="color in colors" value="{{ color.id }}">{{ color.title }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select title="Choose Option" ng-model="incoming.incoming_items[$index].design_id">
                                            <option value="">Select Design</option>
                                            <option ng-repeat="design in designs" value="{{ design.id }}">{{ design.title }}</option>
                                        </select>
                                    </td>
                                    <td class="text-right" ng-repeat="size in sizes"><input type="text" ng-model="incoming.incoming_items[$parent.$index].quantity[size.id]" /></td>                        
                                    <th class="text-right" style="background: #c36868;color: #fff;">{{ getTotalQty($index,-1) }}</th>
                                    <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a></td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Total Items</th>
                                    <th class="text-right" style="background: rgb(178, 219, 239);" ng-repeat="size in sizes">{{ getTotalQty(-1,size.id) }}</th>
                                    <th class="text-right" style="background: rgba(61, 165, 145, 0.89);color: #fff;">{{ getTotalQty(-1,-1) }}</th>
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
                    <button type="submit" ng-disabled="processing" class="btn btn-default btn-l" ng-click="save_incoming()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>input,select{ width:100%;}</style>