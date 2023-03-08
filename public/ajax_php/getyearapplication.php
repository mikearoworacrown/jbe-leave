<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    $requestPayload = file_get_contents("php://input");

    $data = json_decode($requestPayload, true);
    $employee_id = $data['employee_id'];
    $year = $data['year'];

    require_once(PROJECT_PATH .'/class/Employee.php');
    $employee = new Employee();
    
    $employeeYearRecord = $employee->getEmployeeYearRecord($employee_id, $year);
    $getleaveapplication = $employee->getleaveapplication($employee_id, $year);
    
   
    if(!empty($employeeYearRecord) && !empty($getleaveapplication)){
        $_SESSION['count'] = count($getleaveapplication);
        echo json_encode($getleaveapplication);
    }else {
        echo json_encode($employeeYearRecord);
    }
?>

