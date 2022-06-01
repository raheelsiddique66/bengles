angular.module('production', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('productionController', 
	function ($scope, $http, $interval, $filter) {
		$scope.customers = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.machines = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.production_id = 0;
		$scope.item_id = '';
		$scope.production = {
			id: 0,
			date: '',
			customer_id: '0',
			gatepass_id: '0',
			production_items: [],
		};
		$scope.production_item = {
			"color_id":"",
			"design_id": "",
			"machine_id": 0,
			"quantity": [],
		};
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_customer'}, function( response ){
				$scope.customers = response;
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
			$scope.wctAJAX( {action: 'get_machines'}, function( response ){
				$scope.machines = response;
			});
			if( $scope.production_id > 0 ) {
				$scope.wctAJAX( {action: 'get_production', id: $scope.production_id}, function( response ){
					$scope.production = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
                    $scope.production.date = JSON.parse( response );
                });
				$scope.production.production_items.push( angular.copy( $scope.production_item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.production_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.production.production_items.splice(position+1, 0, angular.copy( $scope.production_item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.production.production_items.length > 1 ){
				$scope.production.production_items.splice( position, 1 );
			}
			else {
				$scope.production.production_items = [];
				$scope.production.production_items.push( angular.copy( $scope.production_item ) );
			}
			$scope.update_grand_total();
		}
		$scope.getTotalQty = function( index, size_id ){
			var total = 0;
			for(var i = 0; i < $scope.production.production_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.production.production_items[i].quantity[$scope.sizes[j].id] ){
							total += Number($scope.production.production_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		$scope.update_grand_total = function(){
			quantity = 0;
			for( i = 0; i < $scope.production.production_items.length; i++ ) {
				quantity += parseFloat( $scope.production.production_items[ i ].quantity?$scope.production.production_items[ i ].quantity:0 );
			}
			$scope.production.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'production_manage.php',
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
		$scope.save_production = function () {
            //console.log($scope.incoming.incoming_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_production', production: JSON.stringify( $scope.production )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='production_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		
	}
);