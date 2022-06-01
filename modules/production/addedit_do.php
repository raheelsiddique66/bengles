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
		case "get_color":
			$rs = doquery( "select * from color where status=1 order by sortorder", $dblink );
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
			$rs = doquery( "select * from size where status=1 order by sortorder", $dblink );
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
			$rs = doquery( "select * from design where status=1 order by sortorder", $dblink );
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
		case "get_machines":
			$rs = doquery( "select * from machine where status=1 order by title", $dblink );
			$machines = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$machines[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $machines;
		break;
		case "get_production":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from production where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$production = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] ),
					"gatepass_id" => $r[ "gatepass_id" ],
				);
				$production_items = array();
				$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from production_items where production_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$quantities = [];
						foreach(explode(",", $r1["sizes"]) as $size){
							$size = explode("x", $size);
							$quantities[$size[0]] = $size[1];
						}
						$production_items[] = array(
							"id" => $r1["id"],
							"production_id" => $r1[ "production_id" ],
							"machine_id" => $r1[ "machine_id" ],
							"color_id" => $r1["color_id"],
							"size_id" => $r1[ "size_id" ],
							"design_id" => $r1[ "design_id" ],
							"quantity" => $quantities
                        );
					}
				}
				$production[ "production_items" ] = $production_items;
			}
			$response = $production;
		break;
		case "save_production":
			$err = array();
			$production = json_decode( $_POST[ "production" ] );
			if( empty( $production->date ) || empty( $production->customer_id ) ) {
				$err[] = "Fields with * are mandatory";
			}
			if( count( $production->production_items ) == 0 ) {
				$err[] = "Add some Items first.";
			}
			else {
				$i=1;
				foreach( $production->production_items as $production_item ) {
					if( empty( $production_item->design_id ) || empty( $production_item->color_id ) || !isset($production_item->quantity) || count((array)$production_item->quantity) == 0 ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $production->id ) ) {
					doquery( "update production set `date`='".slash(date_dbconvert($production->date))."', `customer_id`='".slash($production->customer_id)."', `gatepass_id`='".slash($production->gatepass_id)."' where id='".$production->id."'", $dblink );
					$production_id = $production->id;
				}
				else {
					doquery( "insert into production (date, customer_id, gatepass_id) VALUES ('".slash(date_dbconvert($production->date))."', '".slash($production->customer_id)."', '".slash($production->gatepass_id)."')", $dblink );
					$production_id = inserted_id();
				}
				$production_item_ids = array();
				foreach( $production->production_items as $production_item) {
					foreach($production_item->quantity as $size_id => $quantity){
						$quantity = (int)$quantity;
						if(!empty($quantity)){
							$check = doquery("select * from production_items where production_id = '".$production_id."' and machine_id = '".$production_item->machine_id."' and color_id = '".$production_item->color_id."' and design_id = '".$production_item->design_id."' and size_id = '".$size_id."'",$dblink);
							if( numrows( $check ) == 0 ) {
								doquery( "insert into production_items( production_id, machine_id, color_id, size_id, design_id, quantity ) values( '".$production_id."', '".$production_item->machine_id."', '".$production_item->color_id."', '".$size_id."', '".$production_item->design_id."', '".$quantity."')", $dblink );
								$production_item_ids[] = inserted_id();
							}
							else {
								$check = dofetch($check);
								doquery( "update production_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
								$production_item_ids[] = $check["id"];
							}
						}
					}
				}
				if( !empty( $production->id ) && count( $production_item_ids ) > 0 ) {
					doquery( "delete from production_items where production_id='".$production_id."' and id not in( ".implode( ",", $production_item_ids )." )", $dblink );
				}

				$response = array(
					"status" => 1,
					"id" => $production_id
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