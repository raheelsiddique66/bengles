<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case "get_customer":
			$rs = doquery( "select * from customer where status=1 order by customer_name", $dblink );
			$customers = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$customers[] = array(
						"id" => $r[ "id" ],
						"customer_name" => unslash($r[ "customer_name" ]),
					);
				}
			}
			$response = $customers;
		break;
		case "get_labour":
			$rs = doquery( "select * from labour where status=1 order by name", $dblink );
			$labours = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$labours[] = array(
						"id" => $r[ "id" ],
						"name" => unslash($r[ "name" ]),
					);
				}
			}
			$response = $labours;
		break;
		case "get_color":
			$rs = doquery( "select * from color where status=1 order by title", $dblink );
			$colors = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$colors[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $colors;
		break;
		case "get_size":
			$rs = doquery( "select * from size where status=1 order by title", $dblink );
			$sizes = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$sizes[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $sizes;
		break;
		case "get_design":
			$rs = doquery( "select * from design where status=1 order by title", $dblink );
			$designs = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$designs[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $designs;
		break;
		case "get_delivery":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from delivery where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$delivery = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] ),
					"claim" => unslash( $r[ "claim" ] ),
					"labour_id" => unslash( $r[ "labour_id" ] )
				);
				$delivery_items = array();
				$rs1 = doquery( "select * from delivery_items where delivery_id='".$r[ "id" ]."'", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$delivery_items[] = array(
							"id" => $r1["id"],
							"delivery_id" => $r1[ "delivery_id" ],
							"color_id" => $r1["color_id"],
							"size_id" => $r1[ "size_id" ],
							"design_id" => $r1[ "design_id" ],
							"quantity" => $r1[ "quantity" ],
							"extra" => $r1[ "extra" ],
							"unit_price" => $r1[ "unit_price" ]
                        );
					}
				}
				$delivery[ "delivery_items" ] = $delivery_items;
			}
			$response = $delivery;
		break;
		case "save_delivery":
			$err = array();
			$delivery = json_decode( $_POST[ "delivery" ] );
			if( empty( $delivery->date ) || empty( $delivery->customer_id ) ) {
				$err[] = "Fields with * are mandatory";
			}
			if( count( $delivery->delivery_items ) == 0 ) {
				$err[] = "Add some Items first.";
			}
			else {
				$i=1;
				foreach( $delivery->delivery_items as $delivery_item ) {
					if( empty( $delivery_item->size_id ) || empty( $delivery_item->color_id ) ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $delivery->id ) ) {
					doquery( "update delivery set `date`='".slash(date_dbconvert($delivery->date))."', `customer_id`='".slash($delivery->customer_id)."', `claim`='".slash($delivery->claim)."', `labour_id`='".slash($delivery->labour_id)."' where id='".$delivery->id."'", $dblink );
					$delivery_id = $delivery->id;
				}
				else {
					doquery( "insert into delivery (date, customer_id, claim, labour_id) VALUES ('".slash(date_dbconvert($delivery->date))."', '".slash($delivery->customer_id)."', '".slash($delivery->claim)."', '".slash($delivery->labour_id)."')", $dblink );
					$delivery_id = inserted_id();
				}
				$delivery_item_ids = array();
				foreach( $delivery->delivery_items as $delivery_item) {
					if( empty( $delivery_item->id ) ) {
						doquery( "insert into delivery_items( delivery_id, color_id, size_id, design_id, quantity, extra, unit_price ) values( '".$delivery_id."', '".$delivery_item->color_id."', '".$delivery_item->size_id."', '".$delivery_item->design_id."', '".$delivery_item->quantity."', '".$delivery_item->extra."', '".$delivery_item->unit_price."')", $dblink );
						$delivery_item_ids[] = inserted_id();
					}
					else {
						doquery( "update delivery_items set `color_id`='".$delivery_item->color_id."', `size_id`='".$delivery_item->size_id."', `design_id`='".$delivery_item->design_id."', `quantity`='".$delivery_item->quantity."', `extra`='".$delivery_item->extra."', `unit_price`='".$delivery_item->unit_price."' where id='".$delivery_item->id."'", $dblink );
						$delivery_item_ids[] = $delivery_item->id;
					}
				}
				if( !empty( $delivery->id ) && count( $delivery_item_ids ) > 0 ) {
					doquery( "delete from delivery_items where delivery_id='".$delivery_id."' and id not in( ".implode( ",", $delivery_item_ids )." )", $dblink );
				}

				$response = array(
					"status" => 1,
					"id" => $delivery_id
				);
			}
			else {
				$response = array(
					"status" => 0,
					"error" => $err
				);
			}
		break;
	}
	echo json_encode( $response );
	die;
}