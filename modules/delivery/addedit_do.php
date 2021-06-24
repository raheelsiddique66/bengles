<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
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
		case "get_delivery":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select a.*, b.name from delivery a left join labour b on a.labour_id = b.id where a.id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$delivery = array(
					"id" => $r[ "id" ],
					"gatepass_id" => $r[ "gatepass_id" ],
					"date" => date_convert( $r[ "date" ] ),
					"customer_id" => unslash( $r[ "customer_id" ] ),
					"claim" => unslash( $r[ "claim" ] ),
					"labour_id" => unslash( $r[ "labour_id" ] ),
					"customer_payment_id" => 0,
					"payment_account_id" => "",
					"payment_amount" => 0,
				);
//				if( !empty( $r[ "customer_payment_id" ] ) ) {
//					$customer_payment = doquery( "select * from customer_payment where id = '".$r[ "customer_payment_id" ]."'", $dblink );
//					if( numrows( $customer_payment ) > 0 ) {
//						$customer_payment = dofetch( $customer_payment );
//						$delivery[ "customer_payment_id" ] = $customer_payment[ "id" ];
//						$delivery[ "payment_account_id" ] = $customer_payment[ "account_id" ];
//						$delivery[ "payment_amount" ] = $customer_payment[ "amount" ];
//					}
//				}
				$delivery_items = array();
				$rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from delivery_items where delivery_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
				if( numrows( $rs1 ) > 0 ) {
					while( $r1 = dofetch( $rs1 ) ) {
						$quantities = [];
						foreach(explode(",", $r1["sizes"]) as $size){
							$size = explode("x", $size);
							$quantities[$size[0]] = $size[1];
						}
						$delivery_items[] = array(
							"id" => $r1["id"],
							"delivery_id" => $r1[ "delivery_id" ],
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
					$delivery_items[] = array(
						"id" => "",
						"delivery_id" => "",
						"color_id" => "",
						"size_id" => "",
						"design_id" => "",
						"machine_id" => "0",
						"quantity" => "",
						"extra" => "",
						"unit_price" => get_config("default_price"),
					);
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
					if( empty( $delivery_item->design_id ) || empty( $delivery_item->color_id ) || !isset($delivery_item->quantity) || count((array)$delivery_item->quantity) == 0 ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $delivery->id ) ) {
					doquery( "update delivery set `date`='".slash(date_dbconvert($delivery->date))."', `gatepass_id`='".slash($delivery->gatepass_id)."', `customer_id`='".slash($delivery->customer_id)."', `claim`='".slash($delivery->claim)."', `labour_id`='".slash($delivery->labour_id)."' where id='".$delivery->id."'", $dblink );
					$delivery_id = $delivery->id;
				}
				else {
					doquery( "insert into delivery (date, customer_id, claim, labour_id, gatepass_id) VALUES ('".slash(date_dbconvert($delivery->date))."', '".slash($delivery->customer_id)."', '".slash($delivery->claim)."', '".slash($delivery->labour_id)."', '".slash($delivery->gatepass_id)."')", $dblink );
					$delivery_id = inserted_id();
				}
//				if( !empty( $delivery->payment_account_id ) ) {
//					$update = false;
//					if( !empty( $delivery->customer_payment_id ) ) {
//						$customer_payment = doquery( "select id from customer_payment where id='".$delivery->customer_payment_id."'", $dblink );
//						if( numrows( $customer_payment ) > 0 ) {
//							$update = true;
//						}
//					}
//					if( $update ) {
//						doquery( "update customer_payment set customer_id = '".slash( $delivery->customer_id )."', amount = '".slash( $delivery->payment_amount )."', account_id = '".slash( $delivery->payment_account_id )."' where id = '".$delivery->customer_payment_id."'", $dblink );
//					}
//					else {
//						doquery( "insert into customer_payment(customer_id, datetime_added, amount, account_id, details) values( '".slash( $delivery->customer_id )."', NOW(), '".slash( $delivery->payment_amount )."', '".slash( $delivery->payment_account_id )."', 'Payment against Delivery #".$delivery->gatepass_id."' )", $dblink );
//						$delivery->customer_payment_id = inserted_id();
//						doquery( "update delivery set customer_payment_id = '".$delivery->customer_payment_id."' where id='".$delivery_id."'", $dblink );
//					}
//				}
				$delivery_item_ids = array();
				foreach( $delivery->delivery_items as $delivery_item) {
					foreach($delivery_item->quantity as $size_id => $quantity){
						$quantity = (int)$quantity;
						if(!empty($quantity)){
							$check = doquery("select * from delivery_items where delivery_id = '".$delivery_id."' and color_id = '".$delivery_item->color_id."' and design_id = '".$delivery_item->design_id."' and machine_id = '".$delivery_item->machine_id."' and size_id = '".$size_id."'",$dblink);
							if( numrows( $check ) == 0 ) {
								doquery( "insert into delivery_items( delivery_id, color_id, size_id, design_id, machine_id, quantity, extra, unit_price ) values( '".$delivery_id."', '".$delivery_item->color_id."', '".$size_id."', '".$delivery_item->design_id."', '".$delivery_item->machine_id."', '".$quantity."', '".$delivery_item->extra."', '".$delivery_item->unit_price."')", $dblink );
								$delivery_item_ids[] = inserted_id();
							}
							else {
								$check = dofetch($check);
								doquery( "update delivery_items set `quantity`='".$quantity."' where id='".$check["id"]."'", $dblink );
								$delivery_item_ids[] = $check["id"];
							}
						}
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