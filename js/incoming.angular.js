angular.module('incoming', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('incomingController', 
	function ($scope, $http, $interval, $filter) {
		$scope.customers = [];
		$scope.labours = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.machines = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.incoming_id = 0;
		$scope.item_id = '';
		$scope.showPopup = false;
		$scope.showPopupCustomer = false;
		$scope.showPopupDesign = false;
		$scope.labour = {
			id: "",
			name: ""
		};
        $scope.labour_placeholder = {
            id: "",
			name: ""
        };
		$scope.customer = {
			id: "",
			customer_name: "",
			customer_name_urdu: ""
		};

		$scope.customer_placeholder = {
			id: "",
			customer_name: "",
			customer_name_urdu: ""
		};
		$scope.design = {
			id: "",
			title: "",
			title_urdu: ""
		};

		$scope.design_placeholder = {
			id: "",
			title: "",
			title_urdu: ""
		};
		$scope.color = {
			id: "",
			title: "",
			title_urdu: ""
		};

		$scope.color_placeholder = {
			id: "",
			title: "",
			title_urdu: ""
		};
		$scope.incoming = {
			id: 0,
			date: '',
			customer_id: '0',
			gatepass_id: '',
			labour_id: '',
			incoming_items: [],
		};
		$scope.incoming_item = {
			"color_id":"",
			"design_id": "",
			"machine_id": 0,
			"quantity": [],
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
			$scope.wctAJAX( {action: 'get_machines'}, function( response ){
				$scope.machines = response;
			});
			if( $scope.incoming_id > 0 ) {
				$scope.wctAJAX( {action: 'get_incoming', id: $scope.incoming_id}, function( response ){
					$scope.incoming = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
                    $scope.incoming.date = JSON.parse( response );
                });
				$scope.incoming.incoming_items.push( angular.copy( $scope.incoming_item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.incoming_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.incoming.incoming_items.splice(position+1, 0, angular.copy( $scope.incoming_item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.incoming.incoming_items.length > 1 ){
				$scope.incoming.incoming_items.splice( position, 1 );
			}
			else {
				$scope.incoming.incoming_items = [];
				$scope.incoming.incoming_items.push( angular.copy( $scope.incoming_item ) );
			}
			$scope.update_grand_total();
		}
		$scope.getTotalQty = function( index, size_id ){
			var total = 0;
			for(var i = 0; i < $scope.incoming.incoming_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.incoming.incoming_items[i].quantity[$scope.sizes[j].id] ){
							total += Number($scope.incoming.incoming_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		
		$scope.update_grand_total = function(){
			quantity = 0;
			for( i = 0; i < $scope.incoming.incoming_items.length; i++ ) {
				quantity += parseFloat( $scope.incoming.incoming_items[ i ].quantity?$scope.incoming.incoming_items[ i ].quantity:0 );
			}
			$scope.incoming.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'incoming_manage.php',
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
		$scope.save_incoming = function () {
            //console.log($scope.incoming.incoming_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_incoming', incoming: JSON.stringify( $scope.incoming )};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='incoming_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
		$scope.save_labour = function () {
            $scope.box_errors = [];
            if( $scope.processing == false ){
                $scope.processing = true;
                data = {action: 'save_labour', labour: JSON.stringify( $scope.labour )};
                $scope.wctAJAX( data, function( response ){
                    $scope.processing = false;
                    if( response.status == 1 ) {
						$scope.labours.push(response.labour);
                        $scope.showPopup = !$scope.showPopup;
                        $scope.labour = angular.copy( $scope.labour_placeholder );
                    }
                    else{
                        $scope.box_errors = response.error;
                    }
                });
            }
        }
		$scope.save_customer = function () {
			$scope.box_errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_customer', customer: JSON.stringify( $scope.customer )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.customers.push(response.customer);
						$scope.showPopupCustomer = !$scope.showPopupCustomer;
						$scope.customer = angular.copy( $scope.customer_placeholder );
					}
					else{
						$scope.box_errors = response.error;
					}
				});
			}
		}
		$scope.save_design = function () {
			$scope.box_errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_design', design: JSON.stringify( $scope.design )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.designs.push(response.design);
						$scope.showPopupDesign = !$scope.showPopupDesign;
						$scope.design = angular.copy( $scope.design_placeholder );
					}
					else{
						$scope.box_errors = response.error;
					}
				});
			}
		}
		$scope.save_color = function () {
			$scope.box_errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_color', color: JSON.stringify( $scope.color )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.colors.push(response.color);
						$scope.showPopupColor = !$scope.showPopupColor;
						$scope.color = angular.copy( $scope.color_placeholder );
					}
					else{
						$scope.box_errors = response.error;
					}
				});
			}
		}
        $scope.togglePopup = function() {
            $scope.showPopup = !$scope.showPopup;
            setTimeout(function(){focus();}, 100);
        }
		$scope.togglePopupCustomer = function() {
			$scope.showPopupCustomer = !$scope.showPopupCustomer;
			setTimeout(function(){focus();}, 100);
		}
		$scope.togglePopupDesign = function() {
			$scope.showPopupDesign = !$scope.showPopupDesign;
			setTimeout(function(){focus();}, 100);
		}
		$scope.togglePopupColor = function() {
			$scope.showPopupColor = !$scope.showPopupColor;
			setTimeout(function(){focus();}, 100);
		}
		
	}
);