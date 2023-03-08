<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . '/class/Employee.php');
    

    $processLeave = new Employee();
    $response = $processLeave->updateEmployeeLeave($_POST["process-employeeid"], $_POST["process-leave-employeeid"]);

    echo json_encode($response);
    
?>