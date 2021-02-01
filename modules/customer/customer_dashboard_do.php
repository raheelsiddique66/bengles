<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_session':
			$response = array(
                "customer" => isset($_SESSION["manage_customer"]["customer_id"])?$_SESSION["manage_customer"]["customer_id"]:"",
            );
		break;
        case "get_customer":
            $rs = doquery( "select * from customer where status=1 order by customer_name", $dblink );
            $customers = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $customers[] = array(
                        "id" => $r[ "id" ],
                        "customer_name" => unslash($r[ "customer_name" ])
                    );
                }
            }
            $response = $customers;
        break;
		case "get_incoming":
            extract($_POST);
            $_SESSION["manage_customer"]["customer_id"] = $customer_id;
            $rs = doquery( "select * from customer where status=1 order by customer_name", $dblink );
            $incomings = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $incomings[] = array(
                        "id" => $r[ "id" ],
                        "customer_name" => unslash($r[ "customer_name" ])
                    );
                }
            }
			$response = array(
			    "incomings" => $incomings
            );
		break;

	}
	echo json_encode( $response );
	die;
}
