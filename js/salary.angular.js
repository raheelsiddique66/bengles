angular.module('salary', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('salaryController', 
	function ($scope, $http, $interval, $filter) {
		$scope.employees = [];
		$scope.errors = [];
		$scope.processing = false;
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_employees'}, function( response ){
				$scope.employees = response;
			});
		});
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
				data = {action: 'save_record', incoming: JSON.stringify( $scope.incoming )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='employee_manage.php?tab=salary&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		
	}
);