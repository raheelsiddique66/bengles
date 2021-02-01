<?php
if(!defined("APP_START")) die("No Direct Access");
$is_search=true;
?>
<style>
    .summary-tb th{
        font-size: 13px !important;
    }
</style>
<div ng-app="customerDashboard" ng-controller="customerDashboardController" id="customerDashboardController">
    <div class="page-header">
        <h1 class="title">Customer Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Customer</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="...">
                <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
                <div class="btn-group" role="group" aria-label="..."> <a href="customer_manage.php" class="btn btn-light editproject">Back to List</a> </div>
            </div>
        </div>
    </div>
    <ul class="topstats clearfix search_filter" style="display: block">
        <li class="col-xs-12 col-lg-12 col-sm-12">
            <div>
                <form class="form-horizontal" action="" method="get">
                    <div class="col-sm-3">
                      <input type="text" title="Enter String" value="" name="q" id="search" class="form-control" >
                    </div>
                    <div class="col-sm-3 col-xs-8">
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">Select Customer</option>

                        </select>
                    </div>
                    <div class="col-sm-3 text-left">
                        <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                        <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
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
                            <h2 class="total-heading" style="margin-top:0"></h2>
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="3%">SN</th>
                                        <th width="22%">DATE</th>
                                        <th width="25%">GATEPASS</th>
                                        <th width="30%">CUSTOMER</th>
                                        <th width="30%">LABOUR</th>
                                        <th width="15%">ITEMS</th>
                                        <th width="10%" class="text-right">STATUS</th>
                                        <th width="5%" class="text-right">Actions</th>
                                    </tr>
                                    <?php
                                        $rs = doquery( "select a.*,c.customer_name as customer_name,d.name as labour from incoming a left join incoming_items b on a.id = b.incoming_id left join customer c on a.customer_id = c.id left join labour d on a.labour_id = d.id where a.customer_id = '".$client_id."' and a.status = 1", $dblink );
                                        if( numrows( $rs ) > 0 ) {
                                        $sn = 1;
                                        while( $r = dofetch( $rs ) ) {
                                    ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><?php echo unslash($r["date"]); ?></td>
                                        <td><?php echo unslash($r["gatepass_id"]); ?></td>
                                        <td><?php echo unslash($r["customer_name"]); ?></td>
                                        <td><?php echo unslash($r["labour"]); ?></td>
                                        <td class="text-center"><?php echo unslash($r["status"]); ?></td>
                                        <td class="text-right"><a class="fancybox_iframe" href="expense_manage.php?tab=addedit&id=1"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>
                                    <?php
                                    $sn++;
                                        }}
                                ?>
                                </table>
                                <div class="fancybox-btn">
                                    <a href="expense_manage.php?project_id=1" class="btn f-iframe btn-default btn-l">View All Expenses</a>
                                    <a href="expense_manage.php?project_id=1&tab=addedit" class="btn f-iframe btn-danger btn-l">Add Expense</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Delivery</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <h2 class="total-heading" style="margin-top:0"></h2>
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="3%">SN</th>
                                        <th width="22%">Date/Time</th>
                                        <th width="20%">Account To</th>
                                        <th width="15%">Account From</th>
                                        <th width="30%">Detail</th>
                                        <th width="10%" class="text-right">Amount</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>datetime_added</td>
                                        <td>account_to</td>
                                        <td>account_from</td>
                                        <td>details</td>
                                        <td class="text-right">amount</td>
                                        <td class="text-right"><a class="fancybox_iframe" href="transaction_manage.php?tab=edit&id=5"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>

                                </table>
                                <div class="fancybox-btn">
                                    <a href="transaction_manage.php?project_id=3" class="btn f-iframe btn-default btn-l">View All Transactions</a>
                                    <a href="transaction_manage.php?project_id=3&tab=add" class="btn f-iframe btn-danger btn-l">Add Transaction</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Washing</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <h2 class="total-heading" style="margin-top:0"></h2>
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="3%">SN</th>
                                        <th width="22%">Date/Time</th>
                                        <th width="20%">Account To</th>
                                        <th width="15%">Account From</th>
                                        <th width="30%">Detail</th>
                                        <th width="10%" class="text-right">Amount</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>datetime_added</td>
                                        <td>account_to</td>
                                        <td>account_from</td>
                                        <td>details</td>
                                        <td class="text-right">amount</td>
                                        <td class="text-right"><a class="fancybox_iframe" href="transaction_manage.php?tab=edit&id=5"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>

                                </table>
                                <div class="fancybox-btn">
                                    <a href="transaction_manage.php?project_id=3" class="btn f-iframe btn-default btn-l">View All Transactions</a>
                                    <a href="transaction_manage.php?project_id=3&tab=add" class="btn f-iframe btn-danger btn-l">Add Transaction</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="total-heading" style="margin-top:0">Customer Payment</h2>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <h2 class="total-heading" style="margin-top:0"></h2>
                            <div class="panel-body">
                                <table width="100%" class="table table-hover list">
                                    <tr>
                                        <th width="3%">SN</th>
                                        <th width="22%">Date/Time</th>
                                        <th width="20%">Account To</th>
                                        <th width="15%">Account From</th>
                                        <th width="30%">Detail</th>
                                        <th width="10%" class="text-right">Amount</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>datetime_added</td>
                                        <td>account_to</td>
                                        <td>account_from</td>
                                        <td>details</td>
                                        <td class="text-right">amount</td>
                                        <td class="text-right"><a class="fancybox_iframe" href="transaction_manage.php?tab=edit&id=5"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>

                                </table>
                                <div class="fancybox-btn">
                                    <a href="transaction_manage.php?project_id=3" class="btn f-iframe btn-default btn-l">View All Transactions</a>
                                    <a href="transaction_manage.php?project_id=3&tab=add" class="btn f-iframe btn-danger btn-l">Add Transaction</a>
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
                                        <th width="3%">SN</th>
                                        <th width="22%">Date/Time</th>
                                        <th width="20%">Account To</th>
                                        <th width="15%">Account From</th>
                                        <th width="30%">Detail</th>
                                        <th width="10%" class="text-right">Amount</th>
                                        <th width="5%" class="text-right">Action</th>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>datetime_added</td>
                                        <td>account_to</td>
                                        <td>account_from</td>
                                        <td>details</td>
                                        <td class="text-right">amount</td>
                                        <td class="text-right"><a class="fancybox_iframe" href="transaction_manage.php?tab=edit&id=5"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;</td>
                                    </tr>

                                </table>
                                <div class="fancybox-btn">
                                    <a href="transaction_manage.php?project_id=3" class="btn f-iframe btn-default btn-l">View All Transactions</a>
                                    <a href="transaction_manage.php?project_id=3&tab=add" class="btn f-iframe btn-danger btn-l">Add Transaction</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>