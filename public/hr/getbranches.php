<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    $requestPayload = file_get_contents("php://input");

    // echo $requestPayload;

    $data = json_decode($requestPayload, true);
    $region_id = $data['region_id'];

    require_once(PROJECT_PATH .'/class/Employee.php');
    $employee = new Employee();
    
    $branches = $employee->getbranches($region_id);

    // if(!empty($branches)){
    //     for($i = 0; $i < count($branches); $i++){
    //         echo $branches[$i]['branch'];
    //     }
    // }

    if(!empty($branches)){
        echo json_encode($branches);
    }else{
        $region = $employee->getRegion($region_id);
        if(!empty($region)){
            echo json_encode($region);
        }
    }
?>


