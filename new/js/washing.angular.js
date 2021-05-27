angular.module('washing', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('washingController', 
	function ($scope, $http, $interval, $filter) {
		$scope.customers = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.washing_id = 0;
		$scope.item_id = '';
		$scope.washing = {
			id: 0,
			date: '',
			customer_id: '0',
			washing_items: [],
		};
		$scope.washing_item = {
			"color_id":"",
			"design_id": "",
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
			if( $scope.washing_id > 0 ) {
				$scope.wctAJAX( {action: 'get_washing', id: $scope.washing_id}, function( response ){
					$scope.washing = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
                    $scope.washing.date = JSON.parse( response );
                });
				$scope.washing.washing_items.push( angular.copy( $scope.washing_item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.washing_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.washing.washing_items.splice(position+1, 0, angular.copy( $scope.washing_item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.washing.washing_items.length > 1 ){
				$scope.washing.washing_items.splice( position, 1 );
			}
			else {
				$scope.washing.washing_items = [];
				$scope.washing.washing_items.push( angular.copy( $scope.washing_item ) );
			}
			$scope.update_grand_total();
		}
		$scope.getTotalQty = function( index, size_id ){
			var total = 0;
			for(var i = 0; i < $scope.washing.washing_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.washing.washing_items[i].quantity[$scope.sizes[j].id] ){
							total += Number($scope.washing.washing_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		$scope.update_grand_total = function(){
			quantity = 0;
			for( i = 0; i < $scope.washing.washing_items.length; i++ ) {
				quantity += parseFloat( $scope.washing.washing_items[ i ].quantity?$scope.washing.washing_items[ i ].quantity:0 );
			}
			$scope.washing.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'washing_manage.php',
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
		$scope.save_washing = function () {
            //console.log($scope.incoming.incoming_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_washing', washing: JSON.stringify( $scope.washing )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='washing_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		
	}
);