<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case "get_employees":
			$rs = doquery( "select * from employees where status=1 order by name", $dblink );
			$employees = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$employees[] = array(
						"id" => $r[ "id" ],
						"name" => unslash($r[ "name" ]),
						"father_name" => unslash($r[ "father_name" ])
					);
				}
			}
			$response = $employees;
		break;
	}
	echo json_encode( $response );
	die;
}