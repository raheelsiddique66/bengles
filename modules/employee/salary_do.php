<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_session':
			$response = array(
			    "date" => isset($_SESSION["manage_salary"]["salary_date"])?$_SESSION["manage_salary"]["salary_date"]:date_convert( date( "Y-m-d" ) ),
                "type" => isset($_SESSION["manage_salary"]["salary_type"])?$_SESSION["manage_salary"]["salary_type"]:"0"
            );
		break;
		case "get_records":
            extract($_POST);
            $_SESSION["manage_salary"]["salary_type"] = $salary_type;
            $_SESSION["manage_salary"]["salary_date"] = $salary_date;
			$dates = array();
            $start = strtotime(date_dbconvert($salary_date));
			if($salary_type == 0){
                $date = date("Y-m-01", $start);
                $end = strtotime ( '+1 month' , strtotime ( $date ) ) ;
            }
			else if($salary_type == 1){
                $end = strtotime("next Saturday", $start);
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
                );
                $currentdate = strtotime('+1 day', $currentdate);
            }
            $rs = doquery( "select * from employees where status=1 and salary_type='".$salary_type."' order by name", $dblink );
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
                    $ch = doquery("select * from employee_salary where employee_id='".$r["id"]."' and date='".date("Y-m-d", strtotime($dates[count($dates)-1]["value"]))."'", $dblink);
                    if(numrows($ch)>0){
                        $ch = dofetch($ch);
                        $salary = $ch["salary_rate"];
                        $over_time_rate = $ch["over_time_rate"];
                        $calculated_salary = $ch["calculated_salary"];
                    }
                    else{
                        $salary = $r["salary"];
                        $over_time_rate = $r["over_time_per_hour"];
                        $calculated_salary = 0;
                    }
                    $employees[] = array(
                        "id" => $r[ "id" ],
                        "name" => unslash($r[ "name" ]),
                        "father_name" => unslash($r[ "father_name" ]),
                        "attendance" => $e_dates,
                        "salary" => (int)$salary,
                        "over_time_rate" => (int)$over_time_rate,
                        "calculated_salary" => (int)$calculated_salary,
                        "balance" => get_employee_balance($r["id"], date("Y-m-d", $start))
                    );
                }
            }
			$response = array(
			    "employees" => $employees,
                "dates" => $dates
            );
		break;
        case "save_record":
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
            }
            $response["status"] = 1;
            break;
	}
	echo json_encode( $response );
	die;
}
