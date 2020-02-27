<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
$row = 1;
$records = array();
if (($handle = fopen("data.csv", "r")) !== FALSE) {
    $data = fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $customer_id = get_table_id("customer", "customer_name", $data[1]);
        $color_id = get_table_id("color", "title", $data[3]);
        $design_id = get_table_id("design", "title", $data[10]);
        if( $data[2] == "RECEVIED" ){
            $table = "incoming";
        }
        else if( $data[2] == "WASHING" ){
            $table = "washing";
        }
        else if( $data[2] == "SENT" ){
            $table = "delivery";
        }
        $ch = doquery("select * from ".$table." where date='".date("Y-m-d", strtotime($data[0]))."' and customer_id = '".$customer_id."'", $dblink);
        if( numrows($ch) > 0 ){
            $ch = dofetch($ch);
            $id = $ch["id"];
        }
        else {
            if ($data[2] == "RECEVIED") {
                doquery("insert into incoming(gatepass_id, date, customer_id, labour_id) values('" . slash(trim($data[11])) . "', '" . date("Y-m-d", strtotime($data[0])) . "', '" . $customer_id . "', 0)", $dblink);
            } else if ($data[2] == "WASHING") {
                doquery("insert into washing(date, customer_id) values('" . date("Y-m-d", strtotime($data[0])) . "', '" . $customer_id . "')", $dblink);
            } else if ($data[2] == "SENT") {
                doquery("insert into delivery(gatepass_id, date, customer_id, claim, labour_id) values('" . slash(trim($data[11])) . "', '" . date("Y-m-d", strtotime($data[0])) . "', '" . $customer_id . "', 0, 0)", $dblink);
            }
            $id = inserted_id();
        }
        for($i = 12; $i <= 16; $i++){
            if( !empty($data[$i]) ){
                if( $data[2] == "RECEVIED" ) {
                    doquery("insert into incoming_items(incoming_id, machine_id, color_id, size_id, design_id, quantity) values('".$id."', '0', '".$color_id."', '".($i-11)."', '".$design_id."', '".$data[$i]."')", $dblink);
                }
                else if( $data[2] == "WASHING" ) {
                    doquery("insert into washing_items(washing_id, color_id, size_id, design_id, quantity) values('".$id."', '".$color_id."', '".($i-11)."', '".$design_id."', '".$data[$i]."')", $dblink);
                }
                else if( $data[2] == "SENT" ) {
                    doquery("insert into delivery_items(delivery_id, machine_id, color_id, size_id, design_id, quantity, extra, unit_price) values('".$id."', '0', '".$color_id."', '".($i-11)."', '".$design_id."', '".$data[$i]."', '".$data[7]."', '".$data[8]."')", $dblink);
                }
            }
        }
    }
    fclose($handle);
}

function get_table_id($table, $field, $value){
    global $dblink, $records;
    if( isset($records[$table][trim($value)]) ){
        $id = $records[$table][trim($value)];
    }
    else {
        $id = doquery("select * from ".$table." where LOWER(".$field.") = '" . slash(strtolower(trim($value))) . "'", $dblink);
        if (numrows($id) > 0) {
            $id = dofetch($id);
            $id = $id["id"];
        } else {
            doquery("insert into ".$table."(".$field.") values('" . slash(trim($value)) . "')", $dblink);
            $id = inserted_id();
        }
        $records[$table][trim($value)] = $id;
    }
    return $id;
}
