<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }

    $requestPayload = file_get_contents("php://input");
    $data = json_decode($requestPayload, true);

    // echo $data['id'] . " " . $data['leave_id'];
    $employee = new Employee();
    $printform = $employee->printedGrantedLeave($data['id'], $data['leave_id']);
    $strtotime = strtotime($printform[0]['created_at']);

    $_SESSION['granted-id'] = $printform[0]['employee_id'];
    $_SESSION['granted-leave-id'] = $printform[0]['employee_leave_id'];
    $_SESSION['granted-firstname'] = $printform[0]['firstname'];
    $_SESSION['granted-lastname'] = $printform[0]['lastname'];
    $_SESSION['granted-branch'] = $printform[0]['branch'];
    $_SESSION['granted-department'] = $printform[0]['department'];
    $_SESSION['granted-start-date'] = $printform[0]['start_date'];
    $_SESSION['granted-end-date'] = $printform[0]['end_date'];
    $_SESSION['granted-resumption-date'] = $printform[0]['resumption_date'];
    $_SESSION['granted-replacedby'] = $printform[0]['replacedby'];
    $_SESSION['granted-noofdays'] = $printform[0]['noofdays'];
    $_SESSION['granted-year'] = $printform[0]['year'];
    $_SESSION['granted-date'] = date('d-m-Y', $strtotime);

    
    echo json_encode($printform);
   
?>




