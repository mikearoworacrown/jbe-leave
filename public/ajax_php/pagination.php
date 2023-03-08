<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");
    $requestPayload = file_get_contents("php://input");

    $data = json_decode($requestPayload, true);

    $_SESSION['range2'] = (int)$data['page'] * 10;
    $_SESSION['range1'] = $_SESSION['range2'] - 10;

    $_SESSION['page'] = (int)$data['page'];
    
    echo json_encode($data);
?>