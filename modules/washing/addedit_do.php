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
		case "get_washing":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from washing where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$washing = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] )
				);
				$washing_items = array();
				$rs1 = doquery( "select * from washing_items where washing_id='".$r[ "id" ]."'", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$washing_items[] = array(
							"id" => $r1["id"],
							"washing_id" => $r1[ "washing_id" ],
							"color_id" => $r1["color_id"],
							"size_id" => $r1[ "size_id" ],
							"design_id" => $r1[ "design_id" ],
							"quantity" => $r1[ "quantity" ]
                        );
					}
				}
				$washing[ "washing_items" ] = $washing_items;
			}
			$response = $washing;
		break;
		case "save_washing":
			$err = array();
			$washing = json_decode( $_POST[ "washing" ] );
			if( empty( $washing->date ) || empty( $washing->customer_id ) ) {
				$err[] = "Fields with * are mandatory";
			}
			if( count( $washing->washing_items ) == 0 ) {
				$err[] = "Add some Items first.";
			}
			else {
				$i=1;
				foreach( $washing->washing_items as $washing_item ) {
					if( empty( $washing_item->size_id ) || empty( $washing_item->color_id ) ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $washing->id ) ) {
					doquery( "update washing set `date`='".slash(date_dbconvert($washing->date))."', `customer_id`='".slash($washing->customer_id)."' where id='".$washing->id."'", $dblink );
					$washing_id = $washing->id;
				}
				else {
					doquery( "insert into washing (date, customer_id) VALUES ('".slash(date_dbconvert($washing->date))."', '".slash($washing->customer_id)."')", $dblink );
					$washing_id = inserted_id();
				}
				$washing_item_ids = array();
				foreach( $washing->washing_items as $washing_item) {
					if( empty( $washing_item->id ) ) {
						doquery( "insert into washing_items( washing_id, color_id, size_id, design_id, quantity ) values( '".$washing_id."', '".$washing_item->color_id."', '".$washing_item->size_id."', '".$washing_item->design_id."', '".$washing_item->quantity."')", $dblink );
						$washing_item_ids[] = inserted_id();
					}
					else {
						doquery( "update washing_items set `color_id`='".$washing_item->color_id."', `size_id`='".$washing_item->size_id."', `design_id`='".$washing_item->design_id."', `quantity`='".$washing_item->quantity."' where id='".$washing_item->id."'", $dblink );
						$washing_item_ids[] = $washing_item->id;
					}
				}
				if( !empty( $washing->id ) && count( $washing_item_ids ) > 0 ) {
					doquery( "delete from washing_items where washing_id='".$washing_id."' and id not in( ".implode( ",", $washing_item_ids )." )", $dblink );
				}

				$response = array(
					"status" => 1,
					"id" => $washing_id
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