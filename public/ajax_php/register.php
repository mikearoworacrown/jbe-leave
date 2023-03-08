<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . '/class/Employee.php');

    // echo var_dump($_POST);
    // Validate phone number
    $phone_number_validation_regex = "/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/";
    // Validate email address
    $email_validation_regex = "/^[A-Za-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[A-Za-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[a-z0-9])?\\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?$/"; 
    // Validate strong password
    $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 

    $email_phone = $_POST['email-phone'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $department_id = $_POST['department-id'];
    $job_title = $_POST['job-title'];
    $total_leave = $_POST['total-leave'];
    $line_manager = $_POST['line-manager'];
    $manager_email = $_POST['manager-email'];
    $region_id = $_POST['region-id'];
    $branch_id = $_POST['branch-id'];
    $passwordReg = $_POST['password'];

    $employee = new Employee();
    $branchDetail = $employee->getBranch($branch_id);
    $departmentDetail = $employee->getDepartment($department_id);
    $regionDetail = $employee->getRegion($region_id);

    $_POST['branch'] = $branchDetail[0]['branch'];
    $_POST['region'] = $regionDetail[0]['region'];
    $_POST['department'] = $departmentDetail[0]['department'];


    
    $email = preg_match($email_validation_regex, $email_phone);
    $phone =  preg_match($phone_number_validation_regex, $email_phone);
    $password = preg_match($password_regex, $passwordReg);

    if($email || $phone) {
        if($password){
            $response = $employee->registerEmployee();
            echo json_encode($response);
        }else{
            $response = array(
                "status" => "error",
                "message" => "Password must contain minimum of 8 characters, at least one uppercase, one lowercase one digit and a special character"
            );
            echo json_encode($response);
        }
    }else {
        $response = array(
            "status" => "error",
            "message" => "Invalid Email Address or Phone Number."
        );
        echo json_encode($response);
    }


?>
