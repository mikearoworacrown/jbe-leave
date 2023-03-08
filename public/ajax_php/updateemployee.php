<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . '/class/Employee.php');

    $employee = new Employee();
    $employeeRecord = $employee->getEmployeeById($_POST['employee_id']);

   
    if($employeeRecord[0]['email_phone'] === $_POST['email-phone']){
        $_SESSION['database-email_phone'] = $employeeRecord[0]['email_phone'];
    }

    $updateEmployee = $employee->updateEmployeeRecord();

    echo json_encode($updateEmployee);
?>