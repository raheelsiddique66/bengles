<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "date" ] ) ) {
    $_SESSION["incoming_manage"]["new_incoming"]["date"] = slash( $_GET[ "date" ] );
}
if(isset($_SESSION["incoming_manage"]["new_incoming"])){
    extract($_SESSION["incoming_manage"]["new_incoming"]);
}
else{
    $date=date_convert( date( "Y-m-d" ) );
}
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
    .form-group .chosen-container {
        margin: 0 0 0px 0;
    }
    .items-deliver.table > tbody > tr > th{vertical-align: bottom;}
</style>
<div ng-app="customerincoming" ng-controller="customerincomingController" id="customerincomingController">
    <div style="display:none">{{incoming_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Incoming</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Incoming</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> 
                <a href="incoming_manage.php?tab=addedit" class="btn btn-light editproject">Add New Incoming</a>
                <a href="incoming_manage.php" class="btn btn-light editproject">Back to List</a> 
            </div>
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
                    <input ng-model="date" class="form-control date-picker" ng-change="get_incoming()" />
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
        <div class="labour-add" ng-show="showPopupCustomer">
            <button data-fancybox-close="" class="fancybox-close-small popup_close" title="Close" ng-click="togglePopupCustomer()"></button>
            <div style="padding: 20px;">
                Customer Name: <span><input id="cursor_focus" type="text" placeholder="Enter Customer Name" ng-model="customer.customer_name"></span>
                Customer Name urdu: <span><input id="cursor_focus" type="text" placeholder="Enter Customer Name" class="nastaleeq" ng-model="customer.customer_name_urdu"></span>
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_customer()" title="Submit Customer"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
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
                <br><br><button ng-disabled="processing" type="submit" class="btn btn-default btn-l" ng-click="save_color()" title="Submit Color"> <i class="fa fa-spin fa-gear" ng-show="processing"></i>Add</button>
                <br><br><div class="alert alert-danger" ng-show="box_errors.length > 0">
                    <p ng-repeat="error in box_errors">{{error}}</p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-1 control-label">
                    <label class="form-label">Incoming Items <span class="manadatory">*</span></label>
                </div>
                <div class="col-sm-11">
                    <div class="panel-body table-responsive">
                    <table class="table table-hover list">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">S.no</th>
                                    <th width="10%">Customer</th>
                                    <th width="80%">Items</th>
                                    <th class="text-center" width="5%">Actions</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr ng-repeat="incoming in incomings">
                                    <td class="text-center serial_number">{{ $index+1 }}</td>
                                    <td>
                                        <table class="table table-hover list">
                                            <tr>
                                                <th>Customer</th>
                                                <td style="position:relative">
                                                    <select id="customer_id" ng-model="incoming.customer_id" chosen>
                                                        <option value="0" selected="false">Select Customer</option>
                                                        <option ng-repeat="customer in customers" value="{{ customer.id }}">{{ customer.customer_name }}</option>
                                                    </select>
                                                    <a href="" class="add_customer_link" ng-click="togglePopupCustomer()">+</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>GatePass</th>
                                                <td style="position:relative">
                                                    <input ng-model="incoming.gatepass_id" class="form-control" />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table table-hover list items-deliver">
                                            <tr>
                                                <th width="10%" rowspan="2">Machine</th>
                                                <th width="10%" rowspan="2" style="position: relative">Design <a href="" class="add_customer_link design" ng-click="togglePopupDesign()">+</a></th>
                                                <th width="10%" rowspan="2" style="position: relative">Color <a href="" class="add_customer_link design" ng-click="togglePopupColor()">+</a></th>
                                                <th class="text-center" width="64%" colspan="{{ sizes.length+1 }}">Sizes</th>
                                                <th class="text-center" width="6%">Actions</th>
                                            </tr>
                                            <tr>
                                                <td ng-repeat="size in sizes">{{ size.title }}</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr ng-repeat="incoming_item in incoming.incoming_items">
                                                <td>
                                                    <select title="Choose Option" ng-model="incoming_item.machine_id">
                                                        <option value="0">Select Machine</option>
                                                        <option ng-repeat="machine in machines" value="{{ machine.id }}">{{ machine.title }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select title="Choose Option" ng-model="incoming_item.design_id">
                                                        <option value="">Select Design</option>
                                                        <option ng-repeat="design in designs" value="{{ design.id }}">{{ design.title }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select title="Choose Option" ng-model="incoming_item.color_id">
                                                        <option value="">Select Color</option>
                                                        <option ng-repeat="color in colors" value="{{ color.id }}">{{ color.title }}</option>
                                                    </select>
                                                </td>
                                                <td class="text-right" ng-repeat="size in sizes"><input type="text" ng-model="incoming_item.quantity[size.id]" /></td>                        
                                                <th class="text-right" style="background: #c36868;color: #fff;">{{ getTotalQty($index,-1,$parent.$index) }}</th>
                                                <td class="text-center"><a href="" ng-click="add_item( $index, $parent.$index )">Add</a> - <a href="" ng-click="remove_item( $index,$parent.$index )">Delete</a></td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Total Items</th>
                                                <th class="text-right" style="background: rgb(178, 219, 239);" ng-repeat="size in sizes">{{ getTotalQty(-1,size.id,$parent.$index) }}</th>
                                                <th class="text-right" style="background: rgba(61, 165, 145, 0.89);color: #fff;">{{ getTotalQty(-1,-1,$index) }}</th>
                                                <th class="text-right">&nbsp;</th>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="text-center">
                                        <a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a>
                                    </td>
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
                    <button type="submit" ng-disabled="processing" class="btn btn-l <?php echo empty($id)?'btn-danger':"btn-default"?>" ng-click="save_incoming()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>input,select{ width:100%;}</style>