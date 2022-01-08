<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_session':
			$response = array(
                "vendor" => isset($_SESSION["manage_vendor"]["vendor_id"])?$_SESSION["manage_vendor"]["vendor_id"]:""
            );
		break;
        case "get_vendor":
            $rs = doquery( "select * from vendor where status=1 order by vendor_name", $dblink );
            $vendors = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $vendors[] = array(
                        "id" => $r[ "id" ],
                        "vendor_name" => unslash($r[ "vendor_name" ])
                    );
                }
            }
            $response = $vendors;
        break;
		case "get_vendor_outgoing":
            extract($_POST);
            $_SESSION["manage_vendor"]["vendor_id"] = $vendor_id;
            $rs = doquery( "SELECT a.*, b.vendor_name FROM `vendor_outgoing` a left join vendor b on a.vendor_id = b.id left join vendor_outgoing_items c on a.id = c.vendor_outgoing_id ".(!empty($vendor_id)?"where vendor_id = '".$vendor_id."'":"")." group by vendor_outgoing_id order by gatepass_id desc limit 0, 10", $dblink );
            $incomings = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $incomings[] = array(
                        "id" => $r[ "id" ],
                        "gatepass_id" => unslash($r[ "gatepass_id" ]),
                        "date" => date_convert($r[ "date" ]),
                        "vendor_id" => $r[ "vendor_id" ],
                        "vendor_name" => $r["vendor_name"],
                        "labour" => get_field($r[ "labour_id" ], "labour", "name")
                    );
                    $incoming_items = array();
                    $rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from incoming_items where incoming_id='".$r[ "id" ]."'  group by color_id,design_id", $dblink );
                    if(numrows($rs1)>0){
                        while($r1=dofetch($rs1)){
                            $quantities = [];
                            foreach(explode(",", $r1["sizes"]) as $size){
                                $size = explode("x", $size);
                                $quantities[$size[0]] = $size[1];
                            }
                            $incoming_items[] = array(
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
                }
                $response = array(
                    "status" => true,
                    "incoming" => $incomings
                );
            }
            else{
                $response = array(
                    "status" => false,
                    "msg" => "No Record Found."
                );
            }

		break;
        case "get_vendor_delivery":
            extract($_POST);
            $_SESSION["manage_vendor"]["vendor_id"] = $vendor_id;
            $rs = doquery( "SELECT a.*, b.vendor_name FROM `vendor_delivery` a left join vendor b on a.vendor_id = b.id left join vendor_delivery_items c on a.id = c.vendor_delivery_id ".(!empty($vendor_id)?"where vendor_id = '".$vendor_id."'":"")." group by vendor_delivery_id order by gatepass_id desc limit 0, 10", $dblink );
            $delivery = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $delivery[] = array(
                        "id" => $r[ "id" ],
                        "gatepass_id" => unslash($r[ "gatepass_id" ]),
                        "date" => date_convert($r[ "date" ]),
                        "vendor_id" => $r[ "vendor_id" ],
                        "vendor_name" => $r["vendor_name"],
                        "labour" => get_field($r[ "labour_id" ], "labour", "name")
                    );
                    $delivery_items = array();
                    $rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from delivery_items where delivery_id='".$r[ "id" ]."'  group by color_id,design_id", $dblink );
                    if(numrows($rs1)>0){
                        while($r1=dofetch($rs1)){
                            $quantities = [];
                            foreach(explode(",", $r1["sizes"]) as $size){
                                $size = explode("x", $size);
                                $quantities[$size[0]] = $size[1];
                            }
                            $delivery_items[] = array(
                                "id" => $r1["id"],
                                "delivery_id" => $r1[ "delivery_id" ],
                                "machine_id" => $r1[ "machine_id" ],
                                "color_id" => $r1["color_id"],
                                "size_id" => $r1[ "size_id" ],
                                "design_id" => $r1[ "design_id" ],
                                "quantity" => $quantities
                            );
                        }
                    }
                }
                $response = array(
                    "status" => true,
                    "delivery" => $delivery
                );
            }
            else{
                $response = array(
                    "status" => false,
                    "msg" => "No Record Found."
                );
            }

        break;
        case "get_washing":
            extract($_POST);
            $_SESSION["manage_vendor"]["vendor_id"] = $vendor_id;
            $rs = doquery( "SELECT a.*, b.vendor_name FROM `washing` a left join vendor b on a.vendor_id = b.id left join washing_items c on a.id = c.washing_id ".(!empty($vendor_id)?"where vendor_id = '".$vendor_id."'":"")." order by date desc limit 0, 10", $dblink );
            $washing = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $washing[] = array(
                        "id" => $r[ "id" ],
                        "date" => date_convert($r[ "date" ]),
                        "vendor_id" => $r[ "vendor_id" ],
                        "vendor_name" => $r["vendor_name"]
                    );
                    $washing_items = array();
                    $rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from washing_items where washing_id='".$r[ "id" ]."' group by color_id,design_id", $dblink );
                    if(numrows($rs1)>0){
                        while($r1=dofetch($rs1)){
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
                }
                $response = array(
                    "status" => true,
                    "washing" => $washing
                );
            }
            else{
                $response = array(
                    "status" => false,
                    "msg" => "No Record Found."
                );
            }

        break;
        case "get_vendor_payment":
            extract($_POST);
            $_SESSION["manage_vendor"]["vendor_id"] = $vendor_id;
            $rs = doquery( "SELECT * FROM `vendor_payment` ".(!empty($vendor_id)?"where vendor_id = '".$vendor_id."'":"")." order by datetime_added desc limit 0, 10", $dblink );
            $vendor_payment = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $vendor_payment[] = array(
                        "id" => $r[ "id" ],
                        "machine" => get_field($r[ "machine_id" ], "machine", "title"),
                        "vendor" => get_field($r[ "vendor_id" ], "vendor", "vendor_name"),
                        "date" => datetime_convert($r[ "datetime_added" ]),
                        "amount" => curr_format($r["amount"]),
                        "discount" => curr_format($r["discount"]),
                        "account" => get_field($r[ "account_id" ], "account", "title"),
                        "details" => unslash($r[ "details" ]),
                        "claim" => $r[ "claim" ],
                    );
                }
                $response = array(
                    "status" => true,
                    "vendor_payment" => $vendor_payment
                );
            }
            else{
                $response = array(
                    "status" => false,
                    "msg" => "No Record Found."
                );
            }
        break;
        case "get_invoice":
            extract($_POST);
            $_SESSION["manage_vendor"]["vendor_id"] = $vendor_id;
            $rs = doquery( "SELECT * FROM `invoice` ".(!empty($vendor_id)?"where vendor_id = '".$vendor_id."'":"")." order by datetime_added desc limit 0, 10", $dblink );
            $invoice = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $invoice[] = array(
                        "id" => $r[ "id" ],
                        "machine" => get_field($r[ "machine_id" ], "machine", "title"),
                        "vendor" => get_field($r[ "vendor_id" ], "vendor", "vendor_name"),
                        "date" => datetime_convert($r[ "datetime_added" ]),
                        "date_from" => date_convert($r[ "date_from" ]),
                        "date_to" => date_convert($r[ "date_to" ]),
                        "notes" => unslash($r[ "notes" ])
                    );
                }
                $response = array(
                    "status" => true,
                    "invoice" => $invoice
                );
            }
            else{
                $response = array(
                    "status" => false,
                    "msg" => "No Record Found."
                );
            }
        break;

	}
	echo json_encode( $response );
	die;
}
