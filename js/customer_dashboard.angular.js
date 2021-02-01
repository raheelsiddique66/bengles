angular.module('customerDashboard', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('customerDashboardController',
	function ($scope, $http, $interval, $filter) {
		$scope.incomings = [];
		$scope.customers = [];
		$scope.customer_id = '';
		$scope.errors = [];
		$scope.processing = false;
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_session'}, function( response ){
				$scope.customer_id = response.customer;
				$scope.get_records();
			});
			$scope.wctAJAX( {action: 'get_customer'}, function( response ){
				$scope.customers = response;
			});
		});
		$scope.get_records = function(){
			$scope.wctAJAX( {action: 'get_incoming', customer_id: $scope.customer_id}, function( response ){
				$scope.incomings = response.incomings;
			});
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'customer_dashboard';
			wctRequest = {
				method: 'POST',
				url: 'customer_manage.php',
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
	}
);
