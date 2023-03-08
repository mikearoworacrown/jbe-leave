<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . '/class/Employee.php');

    // var_dump($_POST);

    if(!isset($_SESSION['endDate'])){
        $_SESSION['endDate'] = $_POST['notchangedenddate'];
    }

    if(!isset($_SESSION['noofdays'])){
        $_SESSION['noofdays'] = $_POST['notchangednoofdays'];
    }
    
    $_SESSION['team-leave-replaceby'] =  $_POST["leave-replace"];
    $_SESSION['team-leave-status'] =  $_POST["leave-status"];
    $_SESSION['team-leave-startdate'] = $_POST["leave-commencing"];
    $_SESSION['team-leave-resumption'] = $_POST["leave-resumption"];
    $_SESSION['team-leave-enddate'] = $_SESSION['endDate'];
    $_SESSION['team-leave-noofdays'] = $_SESSION['noofdays'];

    // echo $_SESSION['team-leave-replaceby'] . " " . $_SESSION['team-leave-status'] . " " . $_SESSION['team-leave-startdate']
    // . " " . $_SESSION['team-leave-resumption'] . " " . $_SESSION['team-leave-enddate'] . " " . $_SESSION['team-leave-noofdays'];

    // var_dump($_SESSION);
    $updateLeave = new Employee();
    $response = $updateLeave->updateTeamLeave($_SESSION["team-id"], $_SESSION["team-leave-id"]);

    echo json_encode($response);
    
?>