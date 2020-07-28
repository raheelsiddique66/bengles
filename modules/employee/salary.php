<?php
if(!defined("APP_START")) die("No Direct Access");
$is_search=true;
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
                <div class="col-sm-2">
                    <select data-ng-model="salary_type">
                        <option value="">Select Salary Type</option>
                        <option value="1">Weekly</option>
                        <option value="2">Daily</option>
                        <option value="0">Monthly</option>
                        <option value="3">Staff</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select data-ng-model="machine_id">
                        <option value="0">All Machine</option>
                        <option ng-repeat="machine in machines" value="{{ machine.id }}">{{ machine.title }}</option>

                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date" value="" class="form-control date-picker" autocomplete="off" data-ng-model="salary_date" />
                </div>
                <div class="col-sm-3 text-left">
                    <input type="button" data-ng-click="get_records()" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
            </div>
        </li>
    </ul>
    <input type="submit" value="Save Record" class="btn btn-default btn-l" name="save_record" data-ng-click="save_record()" title="Update Record" />
    <div class="panel-body table-responsive">
        <table class="table table-hover list">
            <thead>
                <tr>
                    <th width="2%" class="text-center" rowspan="2">S.No</th>
                    <th width="8%" rowspan="2">Employee Name</th>
                    <th width="8%" rowspan="2">Father Name</th>
                    <th width="5%" data-ng-repeat="date in dates"  rowspan="2" class="text-center">{{ date.date }}</th>
                    <th width="3%" rowspan="2">Absent</th>
                    <th width="5%" colspan="6" class="text-center">Salary</th>
                    <th width="5%" colspan="3" class="text-center">Payment</th>
                </tr>
                <tr>
                    <th class="text-center">Fixed</th>
                    <th class="text-center">Per Hour</th>
                    <th class="text-center">Overtime</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Salary</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Advance</th>
                    <th class="text-center">Remaining</th>
                    <th class="text-center">Payment</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="employee in employees">
                    <td class="text-center">{{ $index+1 }}</td>
                    <td>{{employee.name}}</td>
                    <td>{{employee.father_name}}</td>
                    <td data-ng-repeat="date in dates" class="text-center"><input type="text" style="width: 30px;" data-ng-model="employee.attendance[date.value]" data-ng-change="update_caculated($parent.$index)" /></td>
                    <td class="text-right">{{get_total($index).total_absent}}</td>
                    <td class="text-center"><input type="text" style="width: 60px;" data-ng-model="employee.salary" data-ng-change="update_caculated($index)"/></td>
                    <td class="text-center"><input type="text" style="width: 60px;" data-ng-model="employee.over_time_rate" data-ng-change="update_caculated($index)"/></td>
                    <td class="text-right">{{ get_total($index).hours }}</td>
                    <td class="text-right">{{ get_total($index).hours*employee.over_time_rate|currency:'':0 }}</td>
                    <td class="text-right">{{ employee.calculated_salary-(salary_type!="0"?get_total($index).hours*employee.over_time_rate:0)|currency:'':0 }}</td>
                    <td class="text-center"><input type="text" style="width: 60px;" data-ng-model="employee.calculated_salary" /> <i class="fa fa-refresh" data-ng-click="update_caculated($index)" style="font-size: 16px; cursor: pointer"></i> </td>
                    <td class="text-right">{{employee.balance}}</td>
                    <td class="text-right">{{employee.calculated_salary-employee.balance|currency:'':0}}</td>
                    <td class="text-center"><input type="text" style="width: 60px;" data-ng-model="employee.payment" /></td>
                </tr>
                <tr>
                    <td colspan="{{ dates.length+6}}" class="text-right">Total</td>
                    <td class="text-right">{{ total_hours() }}</td>
                    <td class="text-right">{{ total_hours_amount()|currency:'':0 }}</td>
                    <td class="text-right">{{ sum(employees, 'calculated_salary')-(salary_type!="0"?total_hours_amount():0)|currency:'':0 }}</td>
                    <td class="text-right">{{ sum(employees, 'calculated_salary')|currency:'':0 }}</td>
                    <td class="text-right">{{ sum(employees, 'balance')|currency:'':0 }}</td>
                    <td class="text-right">{{ sum(employees, 'calculated_salary')|currency:'':0 }}</td>
                    <td class="text-right">{{ sum(employees, 'payment')|currency:'':0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-10">
                <input type="submit" value="Save Record" class="btn btn-default btn-l" name="save_record" data-ng-click="save_record()" title="Update Record" />
                <a href="employee_manage.php?tab=salary_print" class="btn btn-primary btn-l">Print</a>
            </div>
        </div>
    </div>
</div>
