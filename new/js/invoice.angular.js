angular.module('invoice', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker']).controller('invoiceController', 
	function ($scope, $http, $interval, $filter, $window) {
		$scope.projects = [];
		$scope.accounts = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.invoice_id = 0;
		$scope.invoice = {
			id: 0,
			due_date: '',
			invoice_date: '',
			project_id: '',
			work_order_number: '',
			type: '',
			items: [],
			total_amount: 0,
			discount: 0,
			net_amount: 0,
			sales_tax: 0,
			wht: 0,
			payment_amount: 0,
			payment_account_id: '',
		};
		$scope.item = {
			"id": 0,
			"details": "",
			"rate": 0,
			"quantity": 0,
			"quantity_unit": "0",
			"amount": 0,
		};
		$scope.updateDate = function(){
			$scope.invoice.datetime_added = $(".angular-datetimepicker").val();
			$scope.$apply();
		}
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_projects'}, function( response ){
				$scope.projects = response;
			});
			$scope.wctAJAX( {action: 'get_accounts'}, function( response ){
				$scope.accounts = response;
			});
			if( $scope.invoice_id > 0 ) {
				$scope.wctAJAX( {action: 'get_invoice', id: $scope.invoice_id}, function( response ){
					$scope.invoice = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
					$scope.invoice.invoice_date = JSON.parse( response );
					$scope.invoice.due_date = JSON.parse( response );
				});
				$scope.wctAJAX( {action: 'get_project_id'}, function( response ){
					$scope.invoice.project_id = JSON.parse( response );
				});
				$scope.invoice.items.push( angular.copy( $scope.item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.invoice_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.invoice.items.splice(position+1, 0, angular.copy( $scope.item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.invoice.items.length > 1 ){
				$scope.invoice.items.splice( position, 1 );
			}
			else {
				$scope.invoice.items = [];
				$scope.invoice.items.push( angular.copy( $scope.item ) );
			}
			$scope.update_grand_total();
		}	
		$scope.update_total = function( position ) {
			$scope.invoice.items[ position ].amount = parseFloat( $scope.invoice.items[ position ].rate )*parseFloat( $scope.invoice.items[ position ].quantity );
			$scope.update_grand_total();
		}
		$scope.update_grand_total = function(){
			total = 0;
			for( i = 0; i < $scope.invoice.items.length; i++ ) {
				total += parseFloat( $scope.invoice.items[ i ].amount );
			}
			$scope.invoice.total_amount = total;
			$scope.update_net_total();
		}
		$scope.update_net_total = function(){
			$scope.invoice.sales_tax = parseFloat( $scope.invoice.total_amount )*0.13;
			$scope.invoice.wht = parseFloat( $scope.invoice.total_amount )*0.10;
			$scope.invoice.net_amount = parseFloat( $scope.invoice.total_amount )+parseFloat( $scope.invoice.sales_tax ) - parseFloat( $scope.invoice.discount );
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'invoice_manage.php',
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
		$scope.save_invoice = function () {
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_invoice', invoice: JSON.stringify( $scope.invoice )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='invoice_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
	}
);