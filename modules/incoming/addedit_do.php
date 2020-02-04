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
		case "get_incoming":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from incoming where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$incoming = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] ),
					"labour_id" => unslash( $r[ "labour_id" ] )
				);
				$incoming_items = array();
				$rs1 = doquery( "select * from incoming_items where incoming_id='".$r[ "id" ]."'", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$incoming_items[] = array(
							"id" => $r1["id"],
							"incoming_id" => $r1[ "incoming_id" ],
							"color_id" => $r1["color_id"],
							"size_id" => $r1[ "size_id" ],
							"design_id" => $r1[ "design_id" ],
							"quantity" => $r1[ "quantity" ]
                        );
					}
				}
				$incoming[ "incoming_items" ] = $incoming_items;
			}
			$response = $incoming;
		break;
		case "save_incoming":
			$err = array();
			$incoming = json_decode( $_POST[ "incoming" ] );
			if( empty( $incoming->date ) || empty( $incoming->customer_id ) || empty( $incoming->labour_id ) ) {
				$err[] = "Fields with * are mandatory";
			}
			if( count( $incoming->incoming_items ) == 0 ) {
				$err[] = "Add some Items first.";
			}
			else {
				$i=1;
				foreach( $incoming->incoming_items as $incoming_item ) {
					if( empty( $incoming_item->size_id ) || empty( $incoming_item->color_id ) ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $incoming->id ) ) {
					doquery( "update incoming set `date`='".slash(date_dbconvert($incoming->date))."', `customer_id`='".slash($incoming->customer_id)."', `labour_id`='".slash($incoming->labour_id)."' where id='".$incoming->id."'", $dblink );
					$incoming_id = $incoming->id;
				}
				else {
					doquery( "insert into incoming (date, customer_id, labour_id) VALUES ('".slash(date_dbconvert($incoming->date))."', '".slash($incoming->customer_id)."', '".slash($incoming->labour_id)."')", $dblink );
					$incoming_id = inserted_id();
				}
				$incoming_item_ids = array();
				foreach( $incoming->incoming_items as $incoming_item) {
					if( empty( $incoming_item->id ) ) {
						doquery( "insert into incoming_items( incoming_id, color_id, size_id, design_id, quantity ) values( '".$incoming_id."', '".$incoming_item->color_id."', '".$incoming_item->size_id."', '".$incoming_item->design_id."', '".$incoming_item->quantity."')", $dblink );
						$incoming_item_ids[] = inserted_id();
					}
					else {
						doquery( "update incoming_items set `color_id`='".$incoming_item->color_id."', `size_id`='".$incoming_item->size_id."', `design_id`='".$incoming_item->design_id."', `quantity`='".$incoming_item->quantity."' where id='".$incoming_item->id."'", $dblink );
						$incoming_item_ids[] = $incoming_item->id;
					}
				}
				if( !empty( $incoming->id ) && count( $incoming_item_ids ) > 0 ) {
					doquery( "delete from incoming_items where incoming_id='".$incoming_id."' and id not in( ".implode( ",", $incoming_item_ids )." )", $dblink );
				}

				$response = array(
					"status" => 1,
					"id" => $incoming_id
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