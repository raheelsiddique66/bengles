angular.module('customerDashboard', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('customerDashboardController',
	function ($scope, $http, $interval, $filter, $timeout) {
		$scope.incomings = [];
		$scope.deliveries = [];
		$scope.washing = [];
		$scope.customers = [];
		$scope.customer_payment = [];
		$scope.invoices = [];
		$scope.customer_id = '';
		$scope.errors = [];
		$scope.processing = false;
		$scope.msg = '';
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_session'}, function( response ){
				$scope.customer_id = response.customer;
				$timeout(function(){
					$('.fancybox_iframe').fancybox({type: 'iframe', width: '90%'});
				}, 200);
				$scope.get_records();
			});
			$scope.wctAJAX( {action: 'get_customer'}, function( response ){
				$scope.customers = response;
			});
		});
		$scope.get_records = function(){
			$scope.wctAJAX( {action: 'get_incoming', customer_id: $scope.customer_id}, function( response ){
				if(response.status==1){
					$scope.incomings = response.incoming;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_delivery', customer_id: $scope.customer_id}, function( response ){
				if(response.status==1){
					$scope.deliveries = response.delivery;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_washing', customer_id: $scope.customer_id}, function( response ){
				if(response.status==1){
					$scope.washing = response.washing;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_customer_payment', customer_id: $scope.customer_id}, function( response ){
				if(response.status==1){
					$scope.customer_payment = response.customer_payment;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_invoice', customer_id: $scope.customer_id}, function( response ){
				if(response.status==1){
					$scope.invoices = response.invoice;
				}
				else{
					$scope.msg = response.msg;
				}
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
