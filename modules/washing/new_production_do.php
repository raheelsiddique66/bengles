<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case 'date_washing':
			$response = array(
				"date" => isset($_SESSION["washing_manage"]["new_washing"]["date"])?$_SESSION["washing_manage"]["new_washing"]["date"]:date_convert( date( "Y-m-d" ) ),
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
		case "get_washing":
			$_SESSION["washing_manage"]["new_washing"]["date"] = $_POST["date"];
			$washings = array();
			$rs = doquery( "select * from washing where date='".date_dbconvert($_POST["date"])."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while($r = dofetch( $rs )){
					$washing = array(
						"id" => $r[ "id" ],
						"date" => date_convert( $r[ "date" ] ),
						"customer_id" => unslash( $r[ "customer_id" ] ),
						"gatepass_id" => unslash( $r[ "gatepass_id" ] ),
						"washing_items" => array()
					);
					$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from washing_items where washing_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
					if( numrows( $rs1 ) > 0 ) {
						while( $r1 = dofetch( $rs1 ) ) {
							$quantities = [];
							foreach(explode(",", $r1["sizes"]) as $size){
								$size = explode("x", $size);
								$quantities[$size[0]] = $size[1];
							}
							$washing["washing_items"][] = array(
								"id" => $r1["id"],
								"washing_id" => $r1[ "washing_id" ],
								"machine_id" => $r1[ "machine_id" ],
								"color_id" => $r1["color_id"],
								"size_id" => $r1[ "size_id" ],
								"design_id" => $r1[ "design_id" ],
								"quantity" => $quantities
							);
						}
					}
					$washings[] = $washing;
				}
				$response = $washings;
			}
			
		break;
		case "save_washing":
			$err = array();
			$washings = json_decode( $_POST[ "washing" ] );
			$date = date_dbconvert($_POST[ "date" ] );
			foreach( $washings as $washing ) {
				if( empty( $washing->customer_id ) ) {
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
						doquery( "update washing set `date`='".$date."', `customer_id`='".slash($washing->customer_id)."', `gatepass_id`='".slash($washing->gatepass_id)."' where id='".$washing->id."'", $dblink );
						$washing_id = $washing->id;
					}
					else {
						doquery( "insert into washing (date, customer_id, gatepass_id) VALUES ('".$date."', '".slash($washing->customer_id)."', '".slash($washing->gatepass_id)."')", $dblink );
						$washing_id = inserted_id();
					}
					$washing_item_ids = array();
					foreach( $washing->washing_items as $washing_item) {
						foreach($washing_item->quantity as $size_id => $quantity){
							$quantity = (int)$quantity;
							if(!empty($quantity)){
								$check = doquery("select * from washing_items where washing_id = '".$washing_id."' and machine_id = '".$washing_item->machine_id."' and color_id = '".$washing_item->color_id."' and design_id = '".$washing_item->design_id."' and size_id = '".$size_id."'",$dblink);
								if( numrows( $check ) == 0 ) {
									doquery( "insert into washing_items( washing_id, machine_id, color_id, size_id, design_id, quantity ) values( '".$washing_id."', '".$washing_item->machine_id."', '".$washing_item->color_id."', '".$size_id."', '".$washing_item->design_id."', '".$quantity."')", $dblink );
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