<?php
if(!defined("APP_START")) die("No Direct Access");
$is_search=true;
?>
<style>
    .summary-tb th{
        font-size: 13px !important;
    }
</style>
<div ng-app="vendorDashboard" ng-controller="vendorDashboardController" id="vendorDashboardController">
    <div class="page-header">
        <h1 class="title">Vendor Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Vendor</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="...">
                <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
                <div class="btn-group" role="group" aria-label="..."> <a href="vendor_manage.php" class="btn btn-light editproject">Back to List</a> </div>
            </div>
        </div>
    </div>
    <ul class="topstats clearfix search_filter" style="display: block">
        <li class="col-xs-12 col-lg-12 col-sm-12">
            <div>
                <form class="form-horizontal" action="" method="get">
                    <div class="col-sm-3 col-xs-8">
                        <select data-ng-model="vendor_id" class="form-control" chosen>
                            <option value="0">Select Vendor</option>
                            <option ng-repeat="vendor in vendors" value="{{ vendor.id }}">{{ vendor.vendor_name }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3 text-left">
                        <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                        <input data-ng-click="get_records()" type="button" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                    </div>
                </form>
            </div>
        </li>
    </ul>
    <div class="panel-body summary-tb">
        <div class="clearfix">
            <div class="col-md-3">
                <table class="table table-hover list">
                    <tr>
                        <th colspan="2">Summary</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Item</th>
                                    <th width="22%" class="text-right">Rate</th>
                                    <th width="18%" class="text-right">Qty</th>
                                    <th width="22%" class="text-right">Total</th>
                                </tr>

                                <tr>
                                    <td>CalMag</td>
                                    <td align="right">23.25</td>
                                    <td align="right">10000.00</td>
                                    <td align="right">232500.00</td>
                                </tr>
                                <tr>
                                    <td>RAPID GROW</td>
                                    <td align="right">23.25</td>
                                    <td align="right">10000.00</td>
                                    <td align="right">232500.00</td>
                                </tr>
                                <tr>
                                    <td>SILKO</td>
                                    <td align="right">23.25</td>
                                    <td align="right">10000.00</td>
                                    <td align="right">232500.00</td>
                                </tr>
                                <tr>
                                    <td>AMINO FULVATE</td>
                                    <td align="right">23.25</td>
                                    <td align="right">10000.00</td>
                                    <td align="right">232500.00</td>
                                </tr>
                                <tr>
                                    <td>ZINC MAX MICRO</td>
                                    <td align="right">23.25</td>
                                    <td align="right">10000.00</td>
                                    <td align="right">232500.00</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>Estimated Revenue:</td>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <td>Invoice Sent:</td>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <td>Payment Received:</td>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <td>Balance:</td>
                        <th class="text-right">1,554,300</th>
                    </tr>

                    <tr>
                        <th>Expenses</th>
                        <th class="text-right">0</th>
                    </tr>
                    <tr>
                        <th>Net Profit/Loss:</th>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <td>expense:</td>
                        <th class="text-right">0</th>
                    </tr>
                    <tr>
                        <th colspan="2">Accounts</th>
                    </tr>
                    <tr>
                        <td>url bank:</td>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <th>Account's Balance:</th>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <th>Project Balance:</th>
                        <th class="text-right">1,554,300</th>
                    </tr>
                    <tr>
                        <th>Difference:</th>
                        <th class="text-right">1,554,300</th>
                    </tr>
                </table>
            </div>
            <div class="col-md-9">
                <div id="total-sale">
                    <h2 class="total-heading" style="margin-top:0">Incoming</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <div class="panel-body table-responsive">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="2%" class="text-center">SN</th>
                                        <th width="10%">DATE</th>
                                        <th width="10%">GATEPASS</th>
                                        <th width="15%">VENDOR</th>
                                        <th width="10%">LABOUR</th>
                                        <th>ITEMS</th>
                                        <th width="5%" class="text-right">Actions</th>
                                    </tr>
                                    <tr data-ng-repeat="incoming in incomings">
                                        <td class="text-center">{{ $index+1 }}</td>
                                        <td>{{ incoming.date }}</td>
                                        <td>{{ incoming.gatepass_id }}</td>
                                        <td>{{ incoming.vendor_name }}</td>
                                        <td>{{ incoming.labour }}</td>
                                        <td></td>
                                        <td class="text-right"><a href="incoming_manage.php?tab=addedit&id={{ incoming.id }}" class="fancybox_iframe"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr data-ng-if="incomings.length==0">
                                        <th colspan="7">{{ msg }}</th>
                                    </tr>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="incoming_manage.php?vendor_id={{ vendor_id }}" class="btn fancybox_iframe btn-default btn-l">View All Incoming</a>
                                    <a href="incoming_manage.php?tab=addedit" class="btn f-iframe btn-danger btn-l">Add Incoming</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Vendor Delivery</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="2%" class="text-center">SN</th>
                                        <th width="10%">DATE</th>
                                        <th width="10%">GATEPASS</th>
                                        <th width="15%">VENDOR</th>
                                        <th width="10%">LABOUR</th>
                                        <th>ITEMS</th>
                                        <th width="5%" class="text-right">Actions</th>
                                    </tr>
                                    <tr data-ng-repeat="vendor_delivery in deliveries">
                                        <td class="text-center">{{ $index+1 }}</td>
                                        <td>{{ vendor_delivery.date }}</td>
                                        <td>{{ vendor_delivery.gatepass_id }}</td>
                                        <td>{{ vendor_delivery.vendor_name }}</td>
                                        <td>{{ vendor_delivery.labour }}</td>
                                        <td></td>
                                        <td class="text-right"><a class="fancybox_iframe" href="vendor_delivery_manage.php?tab=addedit&id={{ vendor_delivery.id }}"><img title="Edit Record" alt="Edit" src="images/edit.png"></a></td>
                                    </tr>
                                    <tr data-ng-if="deliveries.length==0">
                                        <th colspan="7">{{ msg }}</th>
                                    </tr>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="vendor_delivery_manage.php?vendor_id={{ vendor_id }}" class="btn fancybox_iframe btn-default btn-l">View All Vendor Delivery</a>
                                    <a href="vendor_delivery_manage.php?tab=addedit" class="btn f-iframe btn-danger btn-l">Add Vendor Delivery</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Washing</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="3%" class="text-center">SN</th>
                                        <th width="10%">Date</th>
                                        <th width="20%">Vendor</th>
                                        <th>Items</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr data-ng-repeat="wash in washing">
                                        <td class="text-center">{{ $index+1 }}</td>
                                        <td>{{ wash.date }}</td>
                                        <td>{{ wash.vendor_name }}</td>
                                        <td></td>
                                        <td class="text-right"><a class="fancybox_iframe" href="washing_manage.php?tab=addedit&id={{ wash.id }}"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr data-ng-if="washing.length==0">
                                        <th colspan="5">{{ msg }}</th>
                                    </tr>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="washing_manage.php?vendor_id={{ vendor_id }}" class="btn fancybox_iframe btn-default btn-l">View All Washing</a>
                                    <a href="washing_manage.php?tab=addedit" class="btn f-iframe btn-danger btn-l">Add Washing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Vendor Payment</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="2%" class="text-center">SN</th>
                                        <th width="3%" class="text-center">ID</th>
                                        <th width="15%">Vendor Name</th>
                                        <th width="10%">Machine</th>
                                        <th width="12%">Date/Time</th>
                                        <th width="8%" class="text-right">Discount</th>
                                        <th width="8%" class="text-right">Amount</th>
                                        <th width="8%" class="text-right">Claim</th>
                                        <th width="10%">Paid By</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr data-ng-repeat="payment in vendor_payment">
                                        <td class="text-center">{{ $index+1 }}</td>
                                        <td class="text-center">{{ payment.id }}</td>
                                        <td>{{ payment.vendor }}</td>
                                        <td>{{ payment.machine }}</td>
                                        <td>{{ payment.date }}</td>
                                        <td class="text-right">{{ payment.discount }}</td>
                                        <td class="text-right">{{ payment.amount }}</td>
                                        <td class="text-right">{{ payment.claim }}</td>
                                        <td>{{ payment.account }}</td>
                                        <td class="text-right"><a class="fancybox_iframe" href="vendor_payment_manage.php?tab=edit&id={{ payment.id }}"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr data-ng-if="vendor_payment.length==0">
                                        <th colspan="10">{{ msg }}</th>
                                    </tr>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="vendor_payment_manage.php?vendor_id={{ vendor_id }}" class="btn fancybox_iframe btn-default btn-l">View Vendor Payment</a>
                                    <a href="vendor_payment_manage.php?tab=add" class="btn f-iframe btn-danger btn-l">Add Vendor Payment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Invoice</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <h2 class="total-heading" style="margin-top:0"></h2>
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="2%" class="text-center">SN</th>
                                        <th width="3%" class="text-center">ID</th>
                                        <th width="15%">Vendor Name</th>
                                        <th width="10%">Machine</th>
                                        <th width="12%">Date/Time</th>
                                        <th width="10%">Date From</th>
                                        <th width="10%">Date To</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr data-ng-repeat="invoice in invoices">
                                        <td class="text-center">{{ $index+1 }}</td>
                                        <td class="text-center">{{ invoice.id }}</td>
                                        <td>{{ invoice.vendor }}</td>
                                        <td>{{ invoice.machine }}</td>
                                        <td>{{ payment.date }}</td>
                                        <td>{{ invoice.date_from }}</td>
                                        <td>{{ invoice.date_to }}</td>
                                        <td class="text-right">
                                            <a class="fancybox_iframe" href="invoice_manage.php?tab=edit&id={{ invoice.id }}"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                                            <a href="invoice_manage.php?tab=print&id={{ invoice.id }}" class="download-icon" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                        </td>
                                    </tr>
                                    <tr data-ng-if="invoices.length==0">
                                        <th colspan="10">{{ msg }}</th>
                                    </tr>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="invoice_manage.php?vendor_id={{ vendor_id }}" class="btn fancybox_iframe btn-default btn-l">View Invoices</a>
                                    <a href="invoice_manage.php?tab=add" class="btn f-iframe btn-danger btn-l">Add Invoice</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>