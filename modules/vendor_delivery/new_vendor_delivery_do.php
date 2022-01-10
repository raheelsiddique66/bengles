<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case 'date_vendor_delivery':
			$response = array(
				"date" => isset($_SESSION["vendor_delivery_manage"]["new_vendor_delivery"]["date"])?$_SESSION["vendor_delivery_manage"]["new_vendor_delivery"]["date"]:date_convert( date( "Y-m-d" ) ),
			);
		break;
		case "get_accounts":
			$rs = doquery( "select * from account where status=1 order by id", $dblink );
			$accounts = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$accounts[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ])
					);
				}
			}
			$response = $accounts;
		break;
		case "get_vendor":
			$rs = doquery( "select * from vendor where status=1 order by vendor_name", $dblink );
			$vendors = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$vendors[] = array(
						"id" => $r[ "id" ],
						"vendor_name" => unslash($r[ "vendor_name" ]),
					);
				}
			}
			$response = $vendors;
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
                        "rate" => unslash($r[ "rate" ]),
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
		case "get_vendor_delivery":
			$_SESSION["vendor_delivery_manage"]["new_vendor_delivery"]["date"] = $_POST["date"];
			$deliveries = array();
			$rs = doquery( "select a.*, b.name from vendor_delivery a left join labour b on a.labour_id = b.id where a.date='".date_dbconvert($_POST["date"])."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while($r = dofetch( $rs )){
					$vendor_delivery = array(
						"id" => $r[ "id" ],
						"gatepass_id" => $r[ "gatepass_id" ],
						"date" => date_convert( $r[ "date" ] ),
						"vendor_id" => unslash( $r[ "vendor_id" ] ),
						"vendor_delivery_items" => array()
					);
					$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from vendor_delivery_items where vendor_delivery_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
					if( numrows( $rs1 ) > 0 ) {
						while( $r1 = dofetch( $rs1 ) ) {
							$quantities = [];
							foreach(explode(",", $r1["sizes"]) as $size){
								$size = explode("x", $size);
								$quantities[$size[0]] = $size[1];
							}
							$vendor_delivery["vendor_delivery_items"][] = array(
								"id" => $r1["id"],
								"vendor_delivery_id" => $r1[ "vendor_delivery_id" ],
								"color_id" => $r1["color_id"],
								"size_id" => $r1[ "size_id" ],
								"design_id" => $r1[ "design_id" ],
								"machine_id" => $r1[ "machine_id" ],
								"quantity" => $quantities,
								"extra" => $r1[ "extra" ],
								"unit_price" => $r1[ "unit_price" ],
							);
						}
					}
					else{
						$vendor_delivery["vendor_delivery_items"][] = array(
							"id" => "",
							"vendor_delivery_id" => "",
							"color_id" => "",
							"size_id" => "",
							"design_id" => "",
							"machine_id" => "0",
							"quantity" => "",
							"extra" => "",
							"unit_price" => get_config("default_price"),
						);
					}
					$deliveries[] = $vendor_delivery;
				}
				$response = $deliveries;
			}
			
		break;
		case "save_vendor_delivery":
			$err = array();
			$deliveries = json_decode( $_POST[ "vendor_delivery" ] );
			$date = date_dbconvert($_POST[ "date" ] );
			foreach( $deliveries as $vendor_delivery ) {
				if( empty( $vendor_delivery->vendor_id ) ) {
					$err[] = "Fields with * are mandatory";
				}
				if( count( $vendor_delivery->vendor_delivery_items ) == 0 ) {
					$err[] = "Add some Items first.";
				}
				else {
					$i=1;
					foreach( $vendor_delivery->vendor_delivery_items as $vendor_delivery_item ) {
						if( empty( $vendor_delivery_item->design_id ) || empty( $vendor_delivery_item->color_id ) || !isset($vendor_delivery_item->quantity) || count((array)$vendor_delivery_item->quantity) == 0 ){
							$err[] = "Fill all the required fields on Row#".$i;
						}
						$i++;
					}
				}
				if( count( $err ) == 0 ) {
					if( !empty( $vendor_delivery->id ) ) {
						doquery( "update vendor_delivery set `date`='".$date."', `gatepass_id`='".slash($vendor_delivery->gatepass_id)."', `vendor_id`='".slash($vendor_delivery->vendor_id)."' where id='".$vendor_delivery->id."'", $dblink );
						$vendor_delivery_id = $vendor_delivery->id;
					}
					else {
						doquery( "insert into vendor_delivery (date, vendor_id, gatepass_id) VALUES ('".$date."', '".slash($vendor_delivery->vendor_id)."', '".slash($vendor_delivery->gatepass_id)."')", $dblink );
						$vendor_delivery_id = inserted_id();
					}
					$vendor_delivery_item_ids = array();
					foreach( $vendor_delivery->vendor_delivery_items as $vendor_delivery_item) {
						foreach($vendor_delivery_item->quantity as $size_id => $quantity){
							$quantity = (int)$quantity;
							if(!empty($quantity)){
								$check = doquery("select * from vendor_delivery_items where vendor_delivery_id = '".$vendor_delivery_id."' and color_id = '".$vendor_delivery_item->color_id."' and design_id = '".$vendor_delivery_item->design_id."' and machine_id = '".$vendor_delivery_item->machine_id."' and size_id = '".$size_id."'",$dblink);
								if( numrows( $check ) == 0 ) {
									doquery( "insert into vendor_delivery_items( vendor_delivery_id, color_id, size_id, design_id, machine_id, quantity, extra, unit_price ) values( '".$vendor_delivery_id."', '".$vendor_delivery_item->color_id."', '".$size_id."', '".$vendor_delivery_item->design_id."', '".$vendor_delivery_item->machine_id."', '".$quantity."', '".$vendor_delivery_item->extra."', '".$vendor_delivery_item->unit_price."')", $dblink );
									$vendor_delivery_item_ids[] = inserted_id();
								}
								else {
									$check = dofetch($check);
									doquery( "update vendor_delivery_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
									$vendor_delivery_item_ids[] = $check["id"];
								}
							}
						}
					}
					if( !empty( $vendor_delivery->id ) && count( $vendor_delivery_item_ids ) > 0 ) {
						doquery( "delete from vendor_delivery_items where vendor_delivery_id='".$vendor_delivery_id."' and id not in( ".implode( ",", $vendor_delivery_item_ids )." )", $dblink );
					}

					$response = array(
						"status" => 1,
						"id" => $vendor_delivery_id
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
        case "save_vendor":
            $box_err = array();
            $vendor = json_decode( $_POST[ "vendor" ] );
            if( empty( $vendor->vendor_name ) ) {
                $box_err[] = "Fields with * are mandatory";
            }
            if( count( $box_err ) == 0 ) {
                doquery( "insert into vendor (vendor_name, vendor_name_urdu, machine_id) VALUES ('".slash($vendor->vendor_name)."', '".slash($vendor->vendor_name_urdu)."', '".slash($vendor->machine_id)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "vendor" => array(
                        "id" => $id,
                        "vendor_name" => $vendor->vendor_name,
                        "vendor_name_urdu" => $vendor->vendor_name_urdu,
						"machine_id" => get_field($vendor->machine_id, "machine", "title"),
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
                doquery( "insert into color (title, title_urdu, rate) VALUES ('".slash($color->title)."', '".slash($color->title_urdu)."', '".slash($color->rate)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "color" => array(
                        "id" => $id,
                        "title" => $color->title,
                        "title_urdu" => $color->title_urdu,
						"rate" => $color->rate,
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
		case "save_machine":
            $box_err = array();
            $machine = json_decode( $_POST[ "machine" ] );
            if( empty( $machine->title ) ) {
                $box_err[] = "Fields with * are mandatory";
            }
            if( count( $box_err ) == 0 ) {
                doquery( "insert into machine (title) VALUES ('".slash($machine->title)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "machine" => array(
                        "id" => $id,
                        "title" => $machine->title,
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