<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $employee = new Employee();

    $status = "Approved";
    $hr_attend  = "yes";
    $_SESSION['searchvalue'] = $_POST['searchvalue'];

    $grantedLeave = $employee->getApprovedLeaveApplicationLike($status, $hr_attend, $_SESSION['searchvalue']);

    echo json_encode($grantedLeave);

?>