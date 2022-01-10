angular.module('vendoroutgoing', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('vendoroutgoingController',
	function ($scope, $http, $interval, $filter) {
		$scope.vendors = [];
		$scope.labours = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.machines = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.vendor_outgoing_id = 0;
		$scope.item_id = '';
		$scope.showPopup = false;
		$scope.showPopupVendor = false;
		$scope.showPopupDesign = false;
		$scope.date = '';
		$scope.labour = {
			id: "",
			name: ""
		};
        $scope.labour_placeholder = {
            id: "",
			name: ""
        };
		$scope.vendor = {
			id: "",
			vendor_name: "",
			vendor_name_urdu: ""
		};

		$scope.vendor_placeholder = {
			id: "",
			vendor_name: "",
			vendor_name_urdu: ""
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
		$scope.vendor_outgoings= [],
        $scope.vendor_outgoing = {
            "id": 0,
            "date": 0,
            "vendor_id": 0,
            "gatepass_id": 0,
			"vendor_outgoing_items": []
        };
		$scope.vendor_outgoing_item = {
            "color_id":"5",
			"design_id": "",
			"machine_id": "7",
			"quantity": [],
		}
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_vendor'}, function( response ){
				$scope.vendors = response;
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
			$scope.wctAJAX({action: 'date_vendor_outgoing'}, function (response) {
                $scope.date = response.date;
                $scope.get_vendor_outgoing();
            });
			$scope.get_vendor_outgoing = function(){
				$scope.wctAJAX( {action: 'get_vendor_outgoing', date: $scope.date}, function( response ) {
					$scope.vendor_outgoings = response;
					if($scope.vendor_outgoings.length == 0) {
						$scope.vendor_outgoings.push( angular.copy( $scope.vendor_outgoing ) );
						$scope.vendor_outgoings[0].vendor_outgoing_items.push( angular.copy( $scope.vendor_outgoing_item ) );
					}
					setTimeout(function(){init_date_picker();}, 100);
				});
			};
		});
		
		$scope.get_action = function(){
			if( $scope.vendor_outgoing_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		$scope.add_item = function( position, parent ){
            $scope.vendor_outgoings[parent].vendor_outgoing_items.splice(position+1, 0, angular.copy( $scope.vendor_outgoing_item ) );
        }

        $scope.remove_item = function( position, parent ){
            if( $scope.vendor_outgoings[ parent ].vendor_outgoing_items.length > 1 ){
                $scope.vendor_outgoings[ parent ].vendor_outgoing_items.splice( position, 1 );
            }
            else {
                $scope.vendor_outgoings[ parent ].vendor_outgoing_items = [];
                $scope.vendor_outgoings[ parent ].vendor_outgoing_items.push( angular.copy( $scope.vendor_outgoing_item ) );
            }
        }
		$scope.add = function( position ){
			$scope.vendor_outgoings.splice(position+1, 0, angular.copy( $scope.vendor_outgoing ) );
			$scope.vendor_outgoings[position+1].vendor_outgoing_items.push( angular.copy( $scope.vendor_outgoing_item ) );
			setTimeout(function(){init_date_picker();}, 100);
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.vendor_outgoings.length > 1 ){
				$scope.vendor_outgoings.splice( position, 1 );
			}
			else {
				$scope.vendor_outgoings = [];
				$scope.vendor_outgoings.push( angular.copy( $scope.vendor_outgoing ) );
			}
			$scope.update_grand_total();
		}
		$scope.getTotalQty = function( index, size_id, parent ){
			var total = 0;
			for(var i = 0; i < $scope.vendor_outgoings[parent].vendor_outgoing_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.vendor_outgoings[parent].vendor_outgoing_items[i].quantity[$scope.sizes[j].id] ){
							total += Number($scope.vendor_outgoings[parent].vendor_outgoing_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		
		$scope.update_grand_total = function(){
			quantity = 0;
			for( i = 0; i < $scope.vendor_outgoings.vendor_outgoing_items.length; i++ ) {
				quantity += parseFloat( $scope.vendor_outgoings.vendor_outgoing_items[ i ].quantity?$scope.vendor_outgoings.vendor_outgoing_items[ i ].quantity:0 );
			}
			$scope.vendor_outgoing.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'new_vendor_outgoing';
			wctRequest = {
				method: 'POST',
				url: 'vendor_outgoing_manage.php',
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
		$scope.save_vendor_outgoing = function () {
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_vendor_outgoing', vendor_outgoing: JSON.stringify( $scope.vendor_outgoings ), date: $scope.date};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='vendor_outgoing_manage.php?tab=new_delivery&id='+response.id;
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
		$scope.save_vendor = function () {
			$scope.box_errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_vendor', vendor: JSON.stringify( $scope.vendor )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.vendors.push(response.vendor);
						$scope.showPopupVendor = !$scope.showPopupVendor;
						$scope.vendor = angular.copy( $scope.vendor_placeholder );
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
		$scope.togglePopupVendor = function() {
			$scope.showPopupVendor = !$scope.showPopupVendor;
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