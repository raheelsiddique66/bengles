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
		case "get_washing":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from washing where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$washing = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] ),
					"gatepass_id" => $r[ "gatepass_id" ],
				);
				$washing_items = array();
				$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from washing_items where washing_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$quantities = [];
						foreach(explode(",", $r1["sizes"]) as $size){
							$size = explode("x", $size);
							$quantities[$size[0]] = $size[1];
						}
						$washing_items[] = array(
							"id" => $r1["id"],
							"washing_id" => $r1[ "washing_id" ],
							"color_id" => $r1["color_id"],
							"size_id" => $r1[ "size_id" ],
							"design_id" => $r1[ "design_id" ],
							"quantity" => $quantities
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
					if( empty( $washing_item->design_id ) || empty( $washing_item->color_id ) || !isset($washing_item->quantity) || count((array)$washing_item->quantity) == 0 ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $washing->id ) ) {
					doquery( "update washing set `date`='".slash(date_dbconvert($washing->date))."', `customer_id`='".slash($washing->customer_id)."', `gatepass_id`='".slash($washing->gatepass_id)."' where id='".$washing->id."'", $dblink );
					$washing_id = $washing->id;
				}
				else {
					doquery( "insert into washing (date, customer_id, gatepass_id) VALUES ('".slash(date_dbconvert($washing->date))."', '".slash($washing->customer_id)."', '".slash($washing->gatepass_id)."')", $dblink );
					$washing_id = inserted_id();
				}
				$washing_item_ids = array();
				foreach( $washing->washing_items as $washing_item) {
					foreach($washing_item->quantity as $size_id => $quantity){
						$quantity = (int)$quantity;
						if(!empty($quantity)){
							$check = doquery("select * from washing_items where washing_id = '".$washing_id."' and color_id = '".$washing_item->color_id."' and design_id = '".$washing_item->design_id."' and size_id = '".$size_id."'",$dblink);
							if( numrows( $check ) == 0 ) {
								doquery( "insert into washing_items( washing_id, color_id, size_id, design_id, quantity ) values( '".$washing_id."', '".$washing_item->color_id."', '".$size_id."', '".$washing_item->design_id."', '".$quantity."')", $dblink );
								$washing_item_ids[] = inserted_id();
							}
							else {
								$check = dofetch($check);
								doquery( "update washing_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
								$washing_item_ids[] = $check["id"];
							}
						}
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