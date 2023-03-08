<?php 
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH .'/class/Employee.php');
    $employee = new Employee();
    
    $_SESSION['email-phone'] = sanitize_validate_email(h($_POST['email-phone']));
    $_SESSION['password']= h($_POST['password']);

    
    if(!empty($_SESSION['email-phone']) && !empty($_SESSION['password'])){
        $loginResult = $employee->loginEmployee($_SESSION['email-phone']);
        echo $loginResult;
    }
    else {
        echo "Invalid Email/Phone Number or Password from login.php.";
    }
?>