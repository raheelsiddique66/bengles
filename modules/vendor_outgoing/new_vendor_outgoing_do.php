<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case 'date_vendor_outgoing':
			$response = array(
				"date" => isset($_SESSION["vendor_outgoing_manage"]["new_vendor_outgoing"]["date"])?$_SESSION["vendor_outgoing_manage"]["new_vendor_outgoing"]["date"]:date_convert( date( "Y-m-d" ) ),
			);
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
		case "get_vendor_outgoing":
			$_SESSION["vendor_outgoing_manage"]["new_vendor_outgoing"]["date"] = $_POST["date"];
			$vendor_outgoings = array();
			$rs = doquery( "select a.*, b.name from vendor_outgoing a left join labour b on a.labour_id = b.id where a.date='".date_dbconvert($_POST["date"])."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while($r = dofetch( $rs )){
					$vendor_outgoing = array(
						"id" => $r[ "id" ],
						"date" => date_convert( $r[ "date" ] ),
						"vendor_id" => unslash( $r[ "vendor_id" ] ),
						"gatepass_id" => unslash( $r[ "gatepass_id" ] ),
						"vendor_outgoing_items" => array()
					);
					$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from vendor_outgoing_items where vendor_outgoing_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
					if( numrows( $rs1 ) > 0 ) {
						while( $r1 = dofetch( $rs1 ) ) {
							$quantities = [];
							foreach(explode(",", $r1["sizes"]) as $size){
								$size = explode("x", $size);
								$quantities[$size[0]] = $size[1];
							}
							$vendor_outgoing["vendor_outgoing_items"][] = array(
								"id" => $r1["id"],
								"vendor_outgoing_id" => $r1[ "vendor_outgoing_id" ],
								"machine_id" => $r1[ "machine_id" ],
								"color_id" => $r1["color_id"],
								"size_id" => $r1[ "size_id" ],
								"design_id" => $r1[ "design_id" ],
								"quantity" => $quantities
							);
						}
					}
					$vendor_outgoings[] = $vendor_outgoing;
				}
				$response = $vendor_outgoings;
			}
			
		break;
		case "save_vendor_outgoing":
			$err = array();
			$vendor_outgoings = json_decode( $_POST[ "vendor_outgoing" ] );
			$date = date_dbconvert($_POST[ "date" ] );
			foreach( $vendor_outgoings as $vendor_outgoing ) {
				if( empty( $vendor_outgoing->vendor_id ) ) {
					$err[] = "Fields with * are mandatory";
				}
				if( count( $vendor_outgoing->vendor_outgoing_items ) == 0 ) {
					$err[] = "Add some Items first.";
				}
				else {
					$i=1;
					foreach( $vendor_outgoing->vendor_outgoing_items as $vendor_outgoing_item ) {
						if( empty( $vendor_outgoing_item->design_id ) || empty( $vendor_outgoing_item->color_id ) || !isset($vendor_outgoing_item->quantity) || count((array)$vendor_outgoing_item->quantity) == 0 ){
							$err[] = "Fill all the required fields on Row#".$i;
						}
						$i++;
					}
				}
				if( count( $err ) == 0 ) {
					if( !empty( $vendor_outgoing->id ) ) {
						doquery( "update vendor_outgoing set `date`='".$date."', `vendor_id`='".slash($vendor_outgoing->vendor_id)."', `gatepass_id`='".slash($vendor_outgoing->gatepass_id)."' where id='".$vendor_outgoing->id."'", $dblink );
						$vendor_outgoing_id = $vendor_outgoing->id;
					}
					else {
						doquery( "insert into vendor_outgoing (date, vendor_id, gatepass_id) VALUES ('".$date."', '".slash($vendor_outgoing->vendor_id)."', '".slash($vendor_outgoing->gatepass_id)."')", $dblink );
						$vendor_outgoing_id = inserted_id();
					}
					$vendor_outgoing_item_ids = array();
					foreach( $vendor_outgoing->vendor_outgoing_items as $vendor_outgoing_item) {
						foreach($vendor_outgoing_item->quantity as $size_id => $quantity){
							$quantity = (int)$quantity;
							if(!empty($quantity)){
								$check = doquery("select * from vendor_outgoing_items where vendor_outgoing_id = '".$vendor_outgoing_id."' and machine_id = '".$vendor_outgoing_item->machine_id."' and color_id = '".$vendor_outgoing_item->color_id."' and design_id = '".$vendor_outgoing_item->design_id."' and size_id = '".$size_id."'",$dblink);
								if( numrows( $check ) == 0 ) {
									doquery( "insert into vendor_outgoing_items( vendor_outgoing_id, machine_id, color_id, size_id, design_id, quantity ) values( '".$vendor_outgoing_id."', '".$vendor_outgoing_item->machine_id."', '".$vendor_outgoing_item->color_id."', '".$size_id."', '".$vendor_outgoing_item->design_id."', '".$quantity."')", $dblink );
									$vendor_outgoing_item_ids[] = inserted_id();
								}
								else {
									$check = dofetch($check);
									doquery( "update vendor_outgoing_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
									$vendor_outgoing_item_ids[] = $check["id"];
								}
							}
						}
					}
					if( !empty( $vendor_outgoing->id ) && count( $vendor_outgoing_item_ids ) > 0 ) {
						doquery( "delete from vendor_outgoing_items where vendor_outgoing_id='".$vendor_outgoing_id."' and id not in( ".implode( ",", $vendor_outgoing_item_ids )." )", $dblink );
					}

					$response = array(
						"status" => 1,
						"id" => $vendor_outgoing_id
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
                doquery( "insert into vendor (vendor_name, vendor_name_urdu) VALUES ('".slash($vendor->vendor_name)."', '".slash($vendor->vendor_name_urdu)."')", $dblink);
                $id = inserted_id();
                $response = array(
                    "status" => 1,
                    "vendor" => array(
                        "id" => $id,
                        "vendor_name" => $vendor->vendor_name,
                        "vendor_name_urdu" => $vendor->vendor_name_urdu,
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