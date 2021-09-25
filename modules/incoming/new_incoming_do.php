<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case 'date_incoming':
			$response = array(
				"date" => isset($_SESSION["incoming_manage"]["new_incoming"]["date"])?$_SESSION["incoming_manage"]["new_incoming"]["date"]:date_convert( date( "Y-m-d" ) ),
			);
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
		case "get_incoming":
			$_SESSION["incoming_manage"]["new_incoming"]["date"] = $_POST["date"];
			$incomings = array();
			$rs = doquery( "select a.*, b.name from incoming a left join labour b on a.labour_id = b.id where a.date='".date_dbconvert($_POST["date"])."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while($r = dofetch( $rs )){
					$incoming = array(
						"id" => $r[ "id" ],
						"date" => date_convert( $r[ "date" ] ),
						"customer_id" => unslash( $r[ "customer_id" ] ),
						"gatepass_id" => unslash( $r[ "gatepass_id" ] ),
						"incoming_items" => array()
					);
					$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from incoming_items where incoming_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
					if( numrows( $rs1 ) > 0 ) {
						while( $r1 = dofetch( $rs1 ) ) {
							$quantities = [];
							foreach(explode(",", $r1["sizes"]) as $size){
								$size = explode("x", $size);
								$quantities[$size[0]] = $size[1];
							}
							$incoming["incoming_items"][] = array(
								"id" => $r1["id"],
								"incoming_id" => $r1[ "incoming_id" ],
								"machine_id" => $r1[ "machine_id" ],
								"color_id" => $r1["color_id"],
								"size_id" => $r1[ "size_id" ],
								"design_id" => $r1[ "design_id" ],
								"quantity" => $quantities
							);
						}
					}
					$incomings[] = $incoming;
				}
				$response = $incomings;
			}
			
		break;
		case "save_incoming":
			$err = array();
			$incomings = json_decode( $_POST[ "incoming" ] );
			$date = date_dbconvert($_POST[ "date" ] );
			foreach( $incomings as $incoming ) {
				if( empty( $incoming->customer_id ) ) {
					$err[] = "Fields with * are mandatory";
				}
				if( count( $incoming->incoming_items ) == 0 ) {
					$err[] = "Add some Items first.";
				}
				else {
					$i=1;
					foreach( $incoming->incoming_items as $incoming_item ) {
						if( empty( $incoming_item->design_id ) || empty( $incoming_item->color_id ) || !isset($incoming_item->quantity) || count((array)$incoming_item->quantity) == 0 ){
							$err[] = "Fill all the required fields on Row#".$i;
						}
						$i++;
					}
				}
				if( count( $err ) == 0 ) {
					if( !empty( $incoming->id ) ) {
						doquery( "update incoming set `date`='".$date."', `customer_id`='".slash($incoming->customer_id)."', `gatepass_id`='".slash($incoming->gatepass_id)."' where id='".$incoming->id."'", $dblink );
						$incoming_id = $incoming->id;
					}
					else {
						doquery( "insert into incoming (date, customer_id, gatepass_id) VALUES ('".$date."', '".slash($incoming->customer_id)."', '".slash($incoming->gatepass_id)."')", $dblink );
						$incoming_id = inserted_id();
					}
					$incoming_item_ids = array();
					foreach( $incoming->incoming_items as $incoming_item) {
						foreach($incoming_item->quantity as $size_id => $quantity){
							$quantity = (int)$quantity;
							if(!empty($quantity)){
								$check = doquery("select * from incoming_items where incoming_id = '".$incoming_id."' and machine_id = '".$incoming_item->machine_id."' and color_id = '".$incoming_item->color_id."' and design_id = '".$incoming_item->design_id."' and size_id = '".$size_id."'",$dblink);
								if( numrows( $check ) == 0 ) {
									doquery( "insert into incoming_items( incoming_id, machine_id, color_id, size_id, design_id, quantity ) values( '".$incoming_id."', '".$incoming_item->machine_id."', '".$incoming_item->color_id."', '".$size_id."', '".$incoming_item->design_id."', '".$quantity."')", $dblink );
									$incoming_item_ids[] = inserted_id();
								}
								else {
									$check = dofetch($check);
									doquery( "update incoming_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
									$incoming_item_ids[] = $check["id"];
								}
							}
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
			}
		break;
		case "save_labour":
			$box_err = array();
			$labour = json_decode( $_POST[ "labour" ] );
			if( empty( $labour->name ) ) {
				$box_err[] = "Fields with * are mandatory";
			}
			if( count( $box_err ) == 0 ) {
				doquery( "insert into labour (name) VALUES ('".slash($labour->name)."')", $dblink);
				$id = inserted_id();
				$response = array(
					"status" => 1,
					"labour" => array(
						"id" => $id,
						"name" => $labour->name,
					)
				);
			}
			else {
				$response = array(
					"status" => 0,
					"error" => $box_err
				);
			}
		break;
        case "save_customer":
            $box_err = array();
            $customer = json_decode( $_POST[ "customer" ] );
            if( empty( $customer->customer_name ) ) {
                $box_err[] = "Fields with * are mandatory";
            }
            if( count( $box_err ) == 0 ) {
                doquery( "insert into customer (customer_name, customer_name_urdu) VALUES ('".slash($customer->customer_name)."', '".slash($customer->customer_name_urdu)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "customer" => array(
                        "id" => $id,
                        "customer_name" => $customer->customer_name,
                        "customer_name_urdu" => $customer->customer_name_urdu,
                    )
                );
            }
            else {
                $response = array(
                    "status" => 0,
                    "error" => $box_err
                );
            }
        break;
        case "save_design":
            $box_err = array();
            $design = json_decode( $_POST[ "design" ] );
            if( empty( $design->title ) ) {
                $box_err[] = "Fields with * are mandatory";
            }
            if( count( $box_err ) == 0 ) {
                doquery( "insert into design (title, title_urdu) VALUES ('".slash($design->title)."', '".slash($design->title_urdu)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "design" => array(
                        "id" => $id,
                        "title" => $design->title,
                        "title_urdu" => $design->title_urdu,
                    )
                );
            }
            else {
                $response = array(
                    "status" => 0,
                    "error" => $box_err
                );
            }
        break;
        case "save_color":
            $box_err = array();
            $color = json_decode( $_POST[ "color" ] );
            if( empty( $color->title ) ) {
                $box_err[] = "Fields with * are mandatory";
            }
            if( count( $box_err ) == 0 ) {
                doquery( "insert into color (title, title_urdu) VALUES ('".slash($color->title)."', '".slash($color->title_urdu)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "color" => array(
                        "id" => $id,
                        "title" => $color->title,
                        "title_urdu" => $color->title_urdu,
                    )
                );
            }
            else {
                $response = array(
                    "status" => 0,
                    "error" => $box_err
                );
            }
            break;
	}
	echo json_encode( $response );
	die;
}