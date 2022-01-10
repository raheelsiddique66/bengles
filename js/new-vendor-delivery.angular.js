angular.module('vendordelivery', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('vendordeliveryController',
	function ($scope, $http, $interval, $filter) {
		$scope.vendors = [];
		$scope.labours = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.machines = [];
		$scope.errors = [];
		$scope.accounts = [];
		$scope.processing = false;
		$scope.vendor_delivery_id = 0;
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
			vendor_name_urdu: "",
			machine_id: ""
		};

		$scope.vendor_placeholder = {
			id: "",
			vendor_name: "",
			vendor_name_urdu: "",
			machine_id: ""
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
			title_urdu: "",
			rate: ""
		};

		$scope.color_placeholder = {
			id: "",
			title: "",
			title_urdu: "",
			rate: ""
		};
		$scope.machine = {
			id: "",
			title: "",
		};
		$scope.machine_placeholder = {
			id: "",
			title: "",
		};
		// $scope.vendor_delivery = {
		// 	id: 0,
		// 	date: '',
		// 	vendor_id: 0,
		// 	gatepass_id: '',
		// 	vendor_delivery_items: [],
		// };
		// $scope.vendor_delivery_item = {
		// 	"color_id":"5",
		// 	"design_id": "",
		// 	"machine_id": "7",
		// 	"quantity": [],
		// 	"extra": 0,
		// 	"unit_price": 0
		// };
		
		$scope.deliveries= [];
        
        $scope.vendor_delivery = {
            "id": 0,
            "date": 0,
            "vendor_id": 0,
            "gatepass_id": 0,
			"vendor_delivery_items": []
        };
		$scope.vendor_delivery_item = {
            "color_id":"5",
			"design_id": "",
			"machine_id": "7",
			"quantity": [],
			"extra": 0,
			"unit_price": 0
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
			$scope.wctAJAX( {action: 'get_accounts'}, function( response ){
				$scope.accounts = response;
			});
			$scope.wctAJAX({action: 'date_vendor_delivery'}, function (response) {
                $scope.date = response.date;
                $scope.get_vendor_delivery();
            });
			$scope.get_vendor_delivery = function(){
				$scope.wctAJAX( {action: 'get_vendor_delivery', date: $scope.date}, function( response ) {
					$scope.deliveries = response;
					if($scope.deliveries.length == 0) {
						$scope.deliveries.push( angular.copy( $scope.vendor_delivery ) );
						$scope.deliveries[0].vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
						//$scope.add(-1);
						//$scope.add_item(-1);
						//$scope.deliveries[0].vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
					}
					setTimeout(function(){init_date_picker();}, 100);
				});
			};
			// $scope.wctAJAX( {action: 'get_vendor_delivery', date: $scope.vendor_delivery.date}, function( response ){
			// 	$scope.vendor_delivery = response;
			// });
			// $scope.vendor_delivery.vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
		});
		
		$scope.get_action = function(){
			if( $scope.vendor_delivery_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		// $scope.add = function( position ){
        //     $scope.deliver.deliveries.splice(position+1, 0, angular.copy( $scope.vendor_delivery ) );
		// 	$scope.deliver.deliveries[position+1].vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
        //    $scope.update_grand_total(position);
        // }

        // $scope.remove = function( position ){
        //     if( $scope.deliver.deliveries.length > 1 ){
        //         $scope.deliver.deliveries.splice( position, 1 );
        //     }
        //     else {
        //         $scope.deliver.deliveries = [];
        //         $scope.deliver.deliveries.push( angular.copy( $scope.vendor_delivery ) );
        //     }
        //     $scope.update_grand_total(position);
        // }
		$scope.add_item = function( position, parent ){
            $scope.deliveries[parent].vendor_delivery_items.splice(position+1, 0, angular.copy( $scope.vendor_delivery_item ) );
        }

        $scope.remove_item = function( position, parent ){
            if( $scope.deliveries[ parent ].vendor_delivery_items.length > 1 ){
                $scope.deliveries[ parent ].vendor_delivery_items.splice( position, 1 );
            }
            else {
                $scope.deliveries[ parent ].vendor_delivery_items = [];
                $scope.deliveries[ parent ].vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
            }
        }
		$scope.add = function( position ){
			$scope.deliveries.splice(position+1, 0, angular.copy( $scope.vendor_delivery ) );
			$scope.deliveries[position+1].vendor_delivery_items.push( angular.copy( $scope.vendor_delivery_item ) );
			setTimeout(function(){init_date_picker();}, 100);
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.deliveries.length > 1 ){
				$scope.deliveries.splice( position, 1 );
			}
			else {
				$scope.deliveries = [];
				$scope.deliveries.push( angular.copy( $scope.vendor_delivery ) );
			}
			$scope.update_grand_total();
		}
		$scope.update_total = function( position, parent ) {
			var quantity = parseFloat( $scope.deliveries[ parent ].vendor_delivery_items[ position ].quantity?scope.deliveries[ parent ].vendor_delivery_items[ position ].quantity:0 );
			$scope.deliveries[ parent ].vendor_delivery_items[ position ].total = ( parseFloat($scope.deliveries[ parent ].vendor_delivery_items[ position ].unit_price )) * quantity;
			$scope.update_grand_total();
		}
		$scope.update_color_rate = function(position, parent){
			var id = $scope.deliveries[ parent ].vendor_delivery_items[ position ].color_id
			var item = $filter('filter')($scope.colors, {id: id}, true );
			if( item.length > 0 ) {
				item = item[0];
				$scope.deliveries[ parent ].vendor_delivery_items[ position ].color_id = item.id;
				$scope.deliveries[ parent ].vendor_delivery_items[ position ].unit_price = item.rate;
				$scope.update_total(position, parent);
			}
		}
		$scope.getTotal = function(index, size_id, parent){
			var total = 0;
			for(var i = 0; i < $scope.deliveries[parent].vendor_delivery_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.deliveries[parent].vendor_delivery_items[i].quantity[$scope.sizes[j].id] ){
							total += parseFloat( $scope.deliveries[parent].vendor_delivery_items[ i ].unit_price ) * Number($scope.deliveries[parent].vendor_delivery_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		$scope.getTotalQty = function( index, size_id, parent ){
			var total = 0;
			//for(var a = 0; a < $scope.deliveries.length; a++){
				for(var i = 0; i < $scope.deliveries[parent].vendor_delivery_items.length; i++){
					if( index == -1 || index == i ){
						for( var j = 0; j < $scope.sizes.length; j++ ){
							if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.deliveries[parent].vendor_delivery_items[i].quantity[$scope.sizes[j].id] ){
								total += Number($scope.deliveries[parent].vendor_delivery_items[i].quantity[$scope.sizes[j].id]);
							}
						}
					}
				}
			//}
			return total;
		}
        $scope.update_grand_total = function(){
			total = 0;
			quantity = 0;
			for( i = 0; i < $scope.deliveries.vendor_delivery_items.length; i++ ) {
				total += parseFloat( $scope.deliveries.vendor_delivery_items[ i ].total );
				quantity += parseFloat( $scope.deliveries.vendor_delivery_items[ i ].quantity?$scope.deliveries.vendor_delivery_items[ i ].quantity:0 );
			}
			$scope.vendor_delivery.total = total;
			$scope.vendor_delivery.quantity = quantity;
		}
		
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'new_vendor_delivery';
			wctRequest = {
				method: 'POST',
				url: 'vendor_delivery_manage.php',
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
		$scope.save_vendor_delivery = function () {
            //console.log($scope.vendor_outgoing.vendor_outgoing_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_vendor_delivery', vendor_delivery: JSON.stringify( $scope.deliveries ), date: $scope.date};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='vendor_delivery_manage.php?tab=new_vendor_delivery&id='+response.id;
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
		$scope.save_machine = function () {
			$scope.box_errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_machine', machine: JSON.stringify( $scope.machine )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.machines.push(response.machine);
						$scope.showPopupMachine = !$scope.showPopupMachine;
						$scope.machine = angular.copy( $scope.machine_placeholder );
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
		$scope.togglePopupMachine = function() {
			$scope.showPopupMachine = !$scope.showPopupMachine;
			setTimeout(function(){focus();}, 100);
		}
	}
);