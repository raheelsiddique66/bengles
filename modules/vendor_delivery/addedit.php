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
        z-index: 9999;
    }
    .add_customer_link.design {
        position: absolute;
        background-color: #399bff;
        width: 22px;
        height: 22px;
        z-index: 999;
        text-align: center;
        line-height: 22px;
        font-size: 22px;
        color: #fff;
        border-radius: 50%;
        right: 5px;
        top: 25px;
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
<div ng-app="vendor_delivery" ng-controller="vendorDeliveryController" id="vendorDeliveryController">
    <div style="display:none">{{vendor_delivery_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Vendor Delivery</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Vendor Delivery</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> 
                <a href="vendor_delivery_manage.php?tab=addedit" class="btn btn-light editproject">Add New Vendor Delivery</a>
                <a href="vendor_delivery_manage.php" class="btn btn-light editproject">Back to List</a>
            </div>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="vendor_delivery_date">Date *</label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="vendor_delivery.date" class="form-control date-picker" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="vendor_id">Vendor *</label>
                </div>
                <div class="col-sm-10">
                    <select id="vendor_id" ng-model="vendor_delivery.vendor_id" chosen>
                        <option value="0" selected="false">Select Vendor</option>
                        <option ng-repeat="vendor in vendors" value="{{ vendor.id }}">{{ vendor.vendor_name }}</option>
                    </select>
                    <a href="" class="add_customer_link" ng-click="togglePopupVendor()">+</a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="claim">Claim</label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="vendor_delivery.claim" class="form-control" />
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
        <div class="labour-add" ng-show="showPopupVendor">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopupVendor()"></button>
            <div style="padding: 20px;">
                Vendor Name: <span><input id="cursor_focus" type="text" placeholder="Enter Vendor Name" ng-model="vendor.vendor_name"></span>
                Vendor Name urdu: <span><input id="cursor_focus" type="text" placeholder="Enter Vendor Name" class="nastaleeq" ng-model="vendor.vendor_name_urdu"></span>
                Machine: <span><select title="Choose Option" ng-model="vendor.machine_id">
                                            <option value="0">Select Machine</option>
                                            <option ng-repeat="machine in machines" value="{{ machine.id }}">{{ machine.title }}</option>
                                        </select></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_vendor()" title="Submit Vendor"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="labour-add" ng-show="showPopupDesign">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopupDesign()"></button>
            <div style="padding: 20px;">
                Design: <span><input id="cursor_focus" type="text" placeholder="Enter Design" ng-model="design.title"></span>
                Design Urdu: <span><input id="cursor_focus" type="text" placeholder="Enter Design" class="nastaleeq" ng-model="design.title_urdu"></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_design()" title="Submit Design"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="labour-add" ng-show="showPopupColor">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopupColor()"></button>
            <div style="padding: 20px;">
                Color: <span><input id="cursor_focus" type="text" placeholder="Enter Color" ng-model="color.title"></span>
                Color Urdu: <span><input id="cursor_focus" type="text" placeholder="Enter Color" class="nastaleeq" ng-model="color.title_urdu"></span>
                Rate: <span><input id="cursor_focus" type="text" placeholder="Enter Rate" ng-model="color.rate"></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_color()" title="Submit Color"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="labour-add" ng-show="showPopupMachine">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopupMachine()"></button>
            <div style="padding: 20px;">
                Machine: <span><input id="cursor_focus" type="text" placeholder="Enter Machine" ng-model="machine.title"></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_machine()" title="Submit Machine"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label" for="labour_id">Labour</label>
                </div>
                <div class="col-sm-10">
                    <select id="labour_id" ng-model="vendor_delivery.labour_id" chosen>
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
                    <label class="form-label" for="vendor_delivery.gatepass_id">Gatepass ID</label>
                </div>
                <div class="col-sm-10">
                    <input ng-model="vendor_delivery.gatepass_id" class="form-control" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2 control-label">
                    <label class="form-label">Vendor Delivery Items <span class="manadatory">*</span></label>
                </div>
                <div class="col-sm-10">
                    <div class="panel-body table-responsive">
                        <table class="table table-hover list">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center" rowspan="2">S.no</th>
                                    <th width="10%" rowspan="2" style="position: relative">Machine <a href="" class="add_customer_link design" ng-click="togglePopupMachine()">+</a></th>
                                    <th width="10%" rowspan="2" style="position: relative">Design <a href="" class="add_customer_link design" ng-click="togglePopupDesign()">+</a></th>
                                    <th width="12%" rowspan="2" style="position: relative">Color <a href="" class="add_customer_link design" ng-click="togglePopupColor()">+</a></th>
                                    <th class="text-center" width="30%" colspan="{{ sizes.length+1 }}">Sizes</th>
                                    <th class="text-right" width="5%">Extra</th>
                                    <th class="text-right" width="6%">Price</th>
                                    <th class="text-right" width="5%">Total</th>
                                    <th class="text-center" width="5%">Actions</th>
                                </tr>
                                <tr>
                                    <td ng-repeat="size in sizes">{{ size.title }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="vendor_delivery_item in vendor_delivery.vendor_delivery_items">
                                    <td class="text-center serial_number">{{ $index+1 }}</td>
                                    <td>
                                        <select title="Choose Option" ng-model="vendor_delivery.vendor_delivery_items[$index].machine_id">
                                            <option value="0">Select Machine</option>
                                            <option ng-repeat="machine in machines" value="{{ machine.id }}">{{ machine.title }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select title="Choose Option" ng-model="vendor_delivery.vendor_delivery_items[$index].design_id">
                                            <option value="">Select Design</option>
                                            <option ng-repeat="design in designs" value="{{ design.id }}">{{ design.title }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select title="Choose Option" ng-model="vendor_delivery.vendor_delivery_items[$index].color_id" ng-change='update_color_rate( $index )'>
                                            <option value="">Select Color</option>
                                            <option ng-repeat="color in colors" value="{{ color.id }}">{{ color.title }}</option>
                                        </select>
                                    </td>
                                    <td class="text-right" ng-repeat="size in sizes"><input type="text" ng-model="vendor_delivery.vendor_delivery_items[$parent.$index].quantity[size.id]" /></td>
                                    <th class="text-right" style="background: #c36868;color: #fff;">{{ getTotalQty($index,-1) }}</th>
                                    <td class="text-right"><input type="text" ng-model="vendor_delivery.vendor_delivery_items[$index].extra" /></td>
                                    <td class="text-right"><input type="text" ng-model="vendor_delivery.vendor_delivery_items[$index].unit_price" /></td>
                                    <td class="text-right">{{getTotalQty($index,-1) * vendor_delivery.vendor_delivery_items[$index].unit_price}}</td>
                                    <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a></td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total Items</th>
                                    <th class="text-right" style="background: rgb(178, 219, 239);" ng-repeat="size in sizes">{{ getTotalQty(-1,size.id) }}</th>
                                    <th class="text-right" style="background: rgba(61, 165, 145, 0.89);color: #fff;">{{ getTotalQty(-1,-1) }}</th>
                                    <th class="text-right">&nbsp;</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">{{ getTotal(-1,-1) }}</th>
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
                    <button type="submit" ng-disabled="processing" class="btn btn-l <?php echo empty($id)?'btn-danger':"btn-default"?>" ng-click="save_vendor_delivery()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>input,select{ width:100%;}</style>
