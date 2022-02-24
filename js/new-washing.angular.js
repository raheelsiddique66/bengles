angular.module('customerwashing', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('customerwashingController', 
	function ($scope, $http, $interval, $filter) {
		$scope.customers = [];
		$scope.colors = [];
		$scope.sizes = [];
		$scope.designs = [];
		$scope.machines = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.washing_id = 0;
		$scope.item_id = '';
		$scope.showPopup = false;
		$scope.showPopupCustomer = false;
		$scope.showPopupDesign = false;
		$scope.date = '';
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
		$scope.washings= [],
        $scope.washing = {
            "id": 0,
            "date": 0,
            "customer_id": 0,
            "gatepass_id": 0,
			"washing_items": []
        };
		$scope.washing_item = {
            "color_id":"5",
			"design_id": "",
			"machine_id": "7",
			"quantity": [],
		}
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
			$scope.wctAJAX({action: 'date_washing'}, function (response) {
                $scope.date = response.date;
                $scope.get_washing();
            });
			$scope.get_washing = function(){
				$scope.wctAJAX( {action: 'get_washing', date: $scope.date}, function( response ) {
					$scope.washings = response;
					if($scope.washings.length == 0) {
						$scope.washings.push( angular.copy( $scope.washing ) );
						$scope.washings[0].washing_items.push( angular.copy( $scope.washing_item ) );
					}
					setTimeout(function(){init_date_picker();}, 100);
				});
			};
		});
		
		$scope.get_action = function(){
			if( $scope.washing_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		$scope.add_item = function( position, parent ){
            $scope.washings[parent].washing_items.splice(position+1, 0, angular.copy( $scope.washing_item ) );
        }

        $scope.remove_item = function( position, parent ){
            if( $scope.washings[ parent ].washing_items.length > 1 ){
                $scope.washings[ parent ].washing_items.splice( position, 1 );
            }
            else {
                $scope.washings[ parent ].washing_items = [];
                $scope.washings[ parent ].washing_items.push( angular.copy( $scope.washing_item ) );
            }
        }
		$scope.add = function( position ){
			$scope.washings.splice(position+1, 0, angular.copy( $scope.washing ) );
			$scope.washings[position+1].washing_items.push( angular.copy( $scope.washing_item ) );
			setTimeout(function(){init_date_picker();}, 100);
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.washings.length > 1 ){
				$scope.washings.splice( position, 1 );
			}
			else {
				$scope.washings = [];
				$scope.washings.push( angular.copy( $scope.washing ) );
			}
			$scope.update_grand_total();
		}
		$scope.getTotalQty = function( index, size_id, parent ){
			var total = 0;
			for(var i = 0; i < $scope.washings[parent].washing_items.length; i++){
				if( index == -1 || index == i ){
					for( var j = 0; j < $scope.sizes.length; j++ ){
						if( ( size_id == -1 || size_id == $scope.sizes[j].id ) && $scope.washings[parent].washing_items[i].quantity[$scope.sizes[j].id] ){
							total += Number($scope.washings[parent].washing_items[i].quantity[$scope.sizes[j].id]);
						}
					}
				}
			}
			return total;
		}
		
		$scope.update_grand_total = function(){
			quantity = 0;
			for( i = 0; i < $scope.washings.washing_items.length; i++ ) {
				quantity += parseFloat( $scope.washings.washing_items[ i ].quantity?$scope.washings.washing_items[ i ].quantity:0 );
			}
			$scope.washing.quantity = quantity;
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'new_washing';
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
            //console.log($scope.washing.washing_items);
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_washing', washing: JSON.stringify( $scope.washings ), date: $scope.date};
                console.log(data);
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='washing_manage.php?tab=new_delivery&id='+response.id;
					}
					else{
						$scope.errors = response.error;
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