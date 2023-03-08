<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . '/class/Employee.php');

    //var_dump($_POST);
    
    $_SESSION['leave-type']= $_POST["leave-type"];

    $applyLeave = new Employee();
    $response = $applyLeave->applyforleave();

    echo json_encode($response);
    
?>