angular.module('salary', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('salaryController', 
	function ($scope, $http, $interval, $filter) {
		$scope.employees = [];
		$scope.salary_type = "0";
		$scope.salary_date = '';
		$scope.errors = [];
		$scope.processing = false;
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_session'}, function( response ){
				$scope.salary_date = response.date;
				$scope.salary_type = response.type;
				$scope.get_records();
			});
		});
		$scope.get_records = function(){
			$scope.wctAJAX( {action: 'get_records', salary_date: $scope.salary_date, salary_type: $scope.salary_type}, function( response ){
				$scope.employees = response.employees;
				$scope.dates = response.dates;
			});
		}
		$scope.update_caculated = function(index){
			$scope.employees[index].calculated_salary = Math.round(Number(($scope.employees[index].salary/$scope.get_total(index).total_days) * $scope.get_total(index).days + $scope.employees[index].over_time_rate * $scope.get_total(index).hours));
		}
		$scope.get_total = function(index){
			var days = 0;
			var hours = 0;
			var total_days = 0;
			for (var i in $scope.employees[index].attendance) {
				if( $scope.employees[index].attendance[i] == 'P' || Number($scope.employees[index].attendance[i] ) > 0 ){
					days++;
					if(Number($scope.employees[index].attendance[i] ) > 0){
						hours += Number($scope.employees[index].attendance[i] );
					}
				}
				if( $scope.employees[index].attendance[i] != 'F' ){
					total_days++;
				}
			}
			return {
				days: days,
				hours: hours,
				total_days: total_days
			};
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'salary';
			wctRequest = {
				method: 'POST',
				url: 'employee_manage.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
					var str = [];
					for(var p in obj){
						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					}
					return str.join("&");
				},
				data: wctData
			}
			$http(wctRequest).then(function(wctResponse){
				wctCallback(wctResponse.data);
			}, function () {
				console.log("Error in fetching data");
			});
		}
		$scope.save_record = function () {
            //console.log($scope.incoming.incoming_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_record', employees: JSON.stringify( $scope.employees )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='employee_manage.php?tab=salary';
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		$scope.sum = function (items, prop) {
			if (items == null) {
				return 0;
			}
			return items.reduce(function (a, b) {
				return b[prop] == null ? a : a + b[prop];
			}, 0);
		};
	}
);
