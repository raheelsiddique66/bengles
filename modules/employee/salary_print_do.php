<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<?php include("include/header.php");?>
<div ng-app="salary" ng-controller="salaryController" id="salaryController">
    <h1 style="margin-bottom: 30px; font-size: 20px; text-align: center">Salary Sheet<br>Date: {{ dates[0].formatted }} - {{ dates[dates.length-1].formatted }}</h1>
    <table>
        <thead>
        <tr>
            <th width="2%" class="text-center">S.No</th>
            <th width="8%">Name</th>
            <th width="3%" data-ng-repeat="date in dates" class="text-center">{{ date.date }}</th>
            <th width="5%" class="text-center">Hours</th>
            <th width="5%" class="text-center">Amount</th>
            <th width="5%" class="text-center">Salary</th>
            <th width="5%" class="text-center">Total</th>
            <th class="text-center">Advance</th>
            <th class="text-center">Remaining</th>
            <th class="text-center">Payment</th>
            <th class="text-center">Signature</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="employee in employees">
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{employee.name}} {{ employee.father_name }}</td>
            <td data-ng-repeat="date in dates" class="text-center" data-ng-class="{'greyed': employee.attendance[date.value]=='A'||employee.attendance[date.value]=='F'}">{{ employee.attendance[date.value] }}</td>
            <td class="text-right">{{ get_total($index).hours }}</td>
            <td class="text-right">{{ get_total($index).hours*employee.over_time_rate|currency:'':0 }}</td>
            <td class="text-right">{{ employee.calculated_salary-get_total($index).hours*employee.over_time_rate|currency:'':0 }}</td>
            <td class="text-right">{{ employee.calculated_salary|currency:'':0 }}</td>
            <td class="text-right">{{employee.balance|currency:'':0}}</td>
            <td class="text-right">{{employee.calculated_salary-employee.balance}}</td>
            <td class="text-right">{{employee.payment|currency:'':0}}</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="{{ dates.length+2 }}" class="text-right">Total</td>
            <td class="text-right">{{ total_hours() }}</td>
            <td class="text-right">{{ total_hours_amount()|currency:'':0 }}</td>
            <td class="text-right">{{ sum(employees, 'calculated_salary')-total_hours_amount()|currency:'':0 }}</td>
            <td class="text-right">{{ sum(employees, 'calculated_salary')|currency:'':0 }}</td>
            <td class="text-right">{{ sum(employees, 'balance')|currency:'':0 }}</td>
            <td class="text-right">{{ sum(employees, 'calculated_salary')|currency:'':0 }}</td>
            <td class="text-right">{{ sum(employees, 'payment')|currency:'':0 }}</td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>
<?php include("include/footer.php");?>
<style>
    body{
        background: white !important;
    }
    .content {
        margin: 0 !important;
        padding: 0 !important;
        background: none;
    }
    #top, .sidebar, #footer, .alert-message{
        display: none !important;
    }
    td, th {
        border: solid 1px;
        padding: 5px;
    }

    table {
        width: 100%;
    }
    table td{
        font-size:14px;
        font-weight:bold;
    }
    .greyed{
        background-color: #000 !important;
        color: #fff !important;
    }
</style>
<?php
die;
?>
