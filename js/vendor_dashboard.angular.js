angular.module('vendorDashboard', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('vendorDashboardController',
	function ($scope, $http, $interval, $filter, $timeout) {
		$scope.incomings = [];
		$scope.deliveries = [];
		$scope.washing = [];
		$scope.vendors = [];
		$scope.vendor_payment = [];
		$scope.invoices = [];
		$scope.vendor_id = '';
		$scope.errors = [];
		$scope.processing = false;
		$scope.msg = '';
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_session'}, function( response ){
				$scope.vendor_id = response.vendor;
				$timeout(function(){
					$('.fancybox_iframe').fancybox({type: 'iframe', width: '90%'});
				}, 200);
				$scope.get_records();
			});
			$scope.wctAJAX( {action: 'get_vendor'}, function( response ){
				$scope.vendors = response;
			});
		});
		$scope.get_records = function(){
			$scope.wctAJAX( {action: 'get_incoming', vendor_id: $scope.vendor_id}, function( response ){
				if(response.status==1){
					$scope.incomings = response.incoming;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_delivery', vendor_id: $scope.vendor_id}, function( response ){
				if(response.status==1){
					$scope.deliveries = response.delivery;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_washing', vendor_id: $scope.vendor_id}, function( response ){
				if(response.status==1){
					$scope.washing = response.washing;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_vendor_payment', vendor_id: $scope.vendor_id}, function( response ){
				if(response.status==1){
					$scope.vendor_payment = response.vendor_payment;
				}
				else{
					$scope.msg = response.msg;
				}
			});
			$scope.wctAJAX( {action: 'get_invoice', vendor_id: $scope.vendor_id}, function( response ){
				if(response.status==1){
					$scope.invoices = response.invoice;
				}
				else{
					$scope.msg = response.msg;
				}
			});
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'vendor_dashboard';
			wctRequest = {
				method: 'POST',
				url: 'vendor_manage.php',
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
