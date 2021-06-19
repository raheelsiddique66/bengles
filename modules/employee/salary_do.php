<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_session':
			$response = array(
			    "date" => isset($_SESSION["manage_salary"]["salary_date"])?$_SESSION["manage_salary"]["salary_date"]:date_convert( date( "Y-m-d" ) ),
                "type" => isset($_SESSION["manage_salary"]["salary_type"])?$_SESSION["manage_salary"]["salary_type"]:"0",
                "machine" => isset($_SESSION["manage_salary"]["machine_id"])?$_SESSION["manage_salary"]["machine_id"]:"",
            );
		break;
        case "get_machine":
            $rs = doquery( "select * from machine where status=1 order by title", $dblink );
            $machines = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $machines[] = array(
                        "id" => $r[ "id" ],
                        "title" => unslash($r[ "title" ])
                    );
                }
            }
            $response = $machines;
        break;
		case "get_records":
            extract($_POST);
            $_SESSION["manage_salary"]["salary_type"] = $salary_type;
            $_SESSION["manage_salary"]["salary_date"] = $salary_date;
            $_SESSION["manage_salary"]["machine_id"] = $machine_id;
            $dates = array();
            $start = strtotime(date_dbconvert($salary_date));
			if($salary_type == 0){
                $date = date("Y-m-01", $start);
                $end = strtotime ( '+1 month' , strtotime ( $date ) ) ;
            }
			else if($salary_type == 1){
                $end = strtotime("next Thursday", $start);
            }
			else{
			    $end = strtotime("tomorrow", $start);
            }
            $currentdate = $start;
            while($currentdate < $end)
            {
                $dates[] = array(
                    "date" => date('D d', $currentdate),
                    "value" => date('Ymd', $currentdate),
                    "formatted" => date('d/m/Y', $currentdate),
                );
                $currentdate = strtotime('+1 day', $currentdate);
            }
            $rs = die( "select * from employees where status=1 and machine_id = '".$machine_id."' and (salary_type='".$salary_type."'".($salary_type==""?" or salary_type='0'":"").") order by name" );
            $employees = array();
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    $e_dates = [];
                    foreach($dates as $date){
                        $ch = doquery("select attendance from employee_attendance where employee_id='".$r["id"]."' and date='".date("Y-m-d", strtotime($date["value"]))."'", $dblink);
                        if(numrows($ch)>0){
                            $ch = dofetch($ch);
                            $e_dates[$date["value"]] = $ch["attendance"];
                        }
                        else{
                            if( date('D', strtotime($date["value"])) == 'Fri' ){
                                $e_dates[$date["value"]] = 'F';
                            }
                            else if($date["value"]<=date("Ymd")){
                                $e_dates[$date["value"]] = 'P';
                            }
                        }
                    }
                    $salary = $salary_type==1&&$r["salary_type"]==0?0:$r["salary"];
                    $machine = $machine_id;
                    $over_time_rate = $salary_type==0?0:$r["over_time_per_hour"];
                    $calculated_salary = 0;
                    $payment = 0;
                    $ch = doquery("select * from employee_salary where employee_id='" . $r["id"] . "' and date='" . date("Y-m-d", strtotime($dates[count($dates) - 1]["value"])) . "'", $dblink);
                    if (numrows($ch) > 0) {
                        $ch = dofetch($ch);
                        $salary = $ch["salary_rate"];
                        $over_time_rate = $ch["over_time_rate"];
                        $calculated_salary = $ch["calculated_salary"];
                        $ch = doquery("select * from employee_payment where employee_salary_id='" . $ch["id"] . "'", $dblink);
                        if (numrows($ch) > 0) {
                            $ch = dofetch($ch);
                            $payment = $ch["amount"];
                        }
                    }
                    $employees[] = array(
                        "id" => $r[ "id" ],
                        "name" => unslash($r[ "name" ]),
                        "name_in_urdu" => unslash($r[ "name_in_urdu" ]),
                        "father_name" => unslash($r[ "father_name" ]),
                        "attendance" => $e_dates,
                        "salary" => (int)$salary,
                        "over_time_rate" => (int)$over_time_rate,
                        "calculated_salary" => (int)$calculated_salary,
                        "balance" => get_employee_balance($r["id"], date("Y-m-d", strtotime($dates[count($dates)-1]["value"]))),
                        "payment" => $payment
                    );
                }
            }
			$response = array(
			    "employees" => $employees,
                "dates" => $dates
            );
		break;
        case "save_record":
            $dates = array();
            $start = strtotime(date_dbconvert($_SESSION["manage_salary"]["salary_date"]));
            $salary_type = $_SESSION["manage_salary"]["salary_type"];
            $machine_id = $_SESSION["manage_salary"]["salary_type"];
            if($salary_type == 0){
                $date = date("Y-m-01", $start);
                $end = strtotime ( '+1 month' , strtotime ( $date ) ) ;
            }
            else if($salary_type == 1){
                $end = strtotime("next Thursday", $start);
            }
            else{
                $end = strtotime("tomorrow", $start);
            }
            $currentdate = $start;
            while($currentdate < $end)
            {
                $dates[] = array(
                    "date" => date('D d', $currentdate),
                    "value" => date('Ymd', $currentdate),
                    "formatted" => date('d/m/Y', $currentdate),
                );
                $currentdate = strtotime('+1 day', $currentdate);
            }
            $employees = json_decode(stripslashes($_POST["employees"]));
            foreach($employees as $employee){
                foreach($employee->attendance as $date => $attendance){
                    $ch = doquery("select id from employee_attendance where employee_id='".$employee->id."' and date='".date("Y-m-d", strtotime($date))."'", $dblink);
                    if(numrows($ch)>0){
                        $ch = dofetch($ch);
                        doquery("update employee_attendance set attendance='".$attendance."' where id = '".$ch["id"]."'", $dblink);
                    }
                    else{
                        doquery("insert into employee_attendance(employee_id, date, attendance) values('".$employee->id."', '".date("Y-m-d", strtotime($date))."', '".$attendance."')", $dblink);
                    }
                }
                $date = $dates[count($dates)-1]["value"];
                $ch = doquery("select * from employee_salary where employee_id='".$employee->id."' and date='".date("Y-m-d", strtotime($date))."'", $dblink);
                if(numrows($ch)>0){
                    $ch = dofetch($ch);
                    $employee_salary_id = $ch["id"];
                    doquery("update employee_salary set salary_rate = '".$employee->salary."', salary_rate = '".$employee->salary."', over_time_rate = '".$employee->over_time_rate."', calculated_salary = '".$employee->calculated_salary."' where id = '".$ch["id"]."'", $dblink);
                }
                else{
                    doquery("insert into employee_salary(employee_id, date, salary_rate, over_time_rate, calculated_salary) values('".$employee->id."', '".date("Y-m-d", strtotime($date))."', '".$employee->salary."', '".$employee->over_time_rate."', '".$employee->calculated_salary."')", $dblink);
                    $employee_salary_id = inserted_id();
                }
                if(isset($employee->payment) && !empty($employee->payment)){
                    $ch = doquery("select * from employee_payment where employee_salary_id='".$employee_salary_id."'", $dblink);
                    if(numrows($ch)>0){
                        $ch = dofetch($ch);
                        doquery("update employee_payment set amount = '".$employee->payment."' where id = '".$ch["id"]."'", $dblink);
                    }
                    else{
                        doquery("insert into employee_payment(employee_id, employee_salary_id, date, amount, account_id) values('".$employee->id."', '".$employee_salary_id."', '".date("Y-m-d", strtotime($date))."', '".$employee->payment."', '".get_default_account_id()."')", $dblink);
                    }
                }
            }
            $response["status"] = 1;
            break;
	}
	echo json_encode( $response );
	die;
}
