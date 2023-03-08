<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $employee = new Employee();

    $_SESSION['employeesearchvalue'] = $_POST['searchvalue'];

    echo json_encode($_SESSION['employeesearchvalue']);

?>