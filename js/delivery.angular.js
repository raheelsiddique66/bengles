angular.module('delivery', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('deliveryController', 
	function ($scope, $http, $interval, $filter) {
		$scope.customers = [];
		$scope.labours = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.delivery_id = 0;
		$scope.delivery = {
			id: 0,
			date: '',
			customer_id: 0,
			claim: '',
			labour_id: 0,
			delivery_items: [],
			quantity: '0',
			total: '0'
		};
		$scope.delivery_item = {
			"id": "",
			"color_id":"",
			"size_id": "",
			"design_id": "",
			"quantity": 0,
			"extra": 0,
			"unit_price": 0,
			"total_quantity": 0,
			"total_price": 0
		};
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_customer'}, function( response ){
				$scope.customers = response;
			});
			$scope.wctAJAX( {action: 'get_labour'}, function( response ){
				$scope.labours = response;
			});
			$scope.wctAJAX( {action: 'get_color'}, function( response ){
				$scope.colors = response;
			});
			$scope.wctAJAX( {action: 'get_size'}, function( response ){
				$scope.sizes = response;
			});
			$scope.wctAJAX( {action: 'get_design'}, function( response ){
				$scope.designs = response;
			});
			if( $scope.delivery_id > 0 ) {
				$scope.wctAJAX( {action: 'get_delivery', id: $scope.delivery_id}, function( response ){
					$scope.delivery = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
                    $scope.delivery.date = JSON.parse( response );
                });
				$scope.delivery.delivery_items.push( angular.copy( $scope.delivery_item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.delivery_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.delivery.delivery_items.splice(position+1, 0, angular.copy( $scope.delivery_item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.delivery.delivery_items.length > 1 ){
				$scope.delivery.delivery_items.splice( position, 1 );
			}
			else {
				$scope.delivery.delivery_items = [];
				$scope.delivery.delivery_items.push( angular.copy( $scope.delivery_item ) );
			}
			$scope.update_grand_total();
		}
		$scope.update_total = function( position ) {
			var quantity = parseFloat( $scope.delivery.delivery_items[ position ].quantity?$scope.delivery.delivery_items[ position ].quantity:0 );
			$scope.delivery.delivery_items[ position ].total = parseFloat( $scope.delivery.delivery_items[ position ].unit_price ) * quantity;
			$scope.update_grand_total();
		}
		$scope.update_grand_total = function(){
			total = 0;
			quantity = 0;
			for( i = 0; i < $scope.delivery.delivery_items.length; i++ ) {
				quantity += parseFloat( $scope.delivery.delivery_items[ i ].quantity?$scope.delivery.delivery_items[ i ].quantity:0 );
				total += parseFloat( $scope.delivery.delivery_items[ i ].total );
			}
			$scope.delivery.total = total;
			$scope.delivery.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'delivery_manage.php',
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
		$scope.save_delivery = function () {
            //console.log($scope.incoming.incoming_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_delivery', delivery: JSON.stringify( $scope.delivery )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='delivery_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		
	}
);