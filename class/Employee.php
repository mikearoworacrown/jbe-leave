<?php
// echo $_SERVER['SCRIPT_FILENAME']; -- Absolute Path
require_once("C:/xampp/htdocs/jbe-leave/class/DBController.php");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once("C:/xampp/htdocs/jbe-leave/vendor/autoload.php");

class Employee {
    private $db_handle;
    
    function __construct() {
        $this->db_handle = new DBController();
    }

    /**********************************************CHECK IF EMAIL OR PHONE EXIST********************************************************/
    public function isEmailExist($email_phone){
        $query = "SELECT * FROM jbe_employees WHERE email_phone = ?";
        $paramType = "s";
        $paramArray = array(
            $email_phone
        );
        $resultSet = $this->db_handle->select($query, $paramType, $paramArray);
        $count = 0;
        if(is_array($resultSet)){
            $count = count($resultSet);
        }
        if($count > 0){
            $result = true;
        }else{
            $result = false;
        }
        return $result;
    }

    /**********************************************GET EMPLOYEE FROM DATABASE********************************************************/
    public function getEmployee($email_phone){
        $query = "SELECT * FROM jbe_employees where email_phone = ?";
        $paramType = 's';
        $paramArray = array(
            $email_phone
        );
        $employeeRecord = $this->db_handle->select($query, $paramType, $paramArray);
        return $employeeRecord;
    }

    public function getTotalNumberOfEmployee(){
        $query = 'SELECT * FROM jbe_employees';
        $paramType = "";
        $paramArray = "";
        $totalNumOfEmployee  = $this->db_handle->select($query, $paramType, $paramArray);
        return count($totalNumOfEmployee);
    }

    public function getAllEmployee() {
        $query = "SELECT * FROM jbe_employees ORDER BY employee_id DESC";
        $paramType = "";
        $paramArray = "";

        $allemployeeRecord = $this->db_handle->select($query, $paramType, $paramArray);
        return $allemployeeRecord;
    }

    public function getEmployeeById($employee_id) {
        $query = "SELECT * FROM jbe_employees WHERE employee_id = ?";
        $paramType = "s";
        $paramArray = array(
            $employee_id
        );

        $employeeRecord = $this->db_handle->select($query, $paramType, $paramArray);
        return $employeeRecord;
    }

    public function getEmployeeByName($like) {
        $query = 'SELECT * FROM jbe_employees WHERE CONCAT(jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone) 
        LIKE ? ORDER BY employee_id DESC';

        $likeappend = '%'.$like.'%';

        $paramType = "s";
        $paramArray = array(
            $likeappend
        );

        $employeeRecord = $this->db_handle->select($query, $paramType, $paramArray);
        return $employeeRecord;
    }
    

    /**********************************************LOGIN EMPLOYEE*********************************************************/
    public function loginEmployee(){
        $employeeRecord = $this->getEmployee($_SESSION["email-phone"]);
        $loginPassword = 0;
        if (!empty($employeeRecord)) {
            if (!empty($_SESSION["password"])) {
                $password = $_SESSION["password"];
            }
            $hashedPassword = $employeeRecord[0]["password"];
            $loginPassword = 0;
            if (password_verify($password, $hashedPassword)) {
                if($employeeRecord[0]["status"] != 'active'){
                    $loginPassword = 0;
                }else {
                    $loginPassword = 1;
                }
            }
        } 
        else {
            $loginPassword = 0;
        }

        if ($loginPassword == 1) {
            $_SESSION["email-phone"] = $employeeRecord[0]["email_phone"];
            $_SESSION["employee-id"] = $employeeRecord[0]["employee_id"];
            $_SESSION["totalleave"] = $employeeRecord[0]["totalleave"];
            $_SESSION["employeetype"] = $employeeRecord[0]["employeetype"];
            $_SESSION["firstname"] = $employeeRecord[0]["firstname"];
            $_SESSION["lastname"] = $employeeRecord[0]["lastname"];
            $_SESSION['linemanageremail'] = $employeeRecord[0]["linemanageremail"];
            $_SESSION['linemanagername'] = $employeeRecord[0]["linemanagername"];
            if(isset($_SESSION['employee-id']) && isset($_SESSION["employeetype"]))
            {
                if ($_SESSION["employeetype"] == "user"){
                    $loginStatus = "user";
                } else if ($_SESSION["employeetype"] == "supervisor") {
                    $loginStatus = "supervisor";
                }else if ($_SESSION["employeetype"] == "hr") {
                    $loginStatus = "hr";
                }
                return $loginStatus;
            }
        } 
        else if ($loginPassword == 0) {
            $loginStatus = "Invalid username or password in Employee.php";
            return $loginStatus;
        }else {
            $loginStatus = "Something went wrong";
            return $loginStatus;
        }
    }

/**********************************************REGISTER EMPLOYEE*********************************************************/
    public function registerEmployee()
    {
        $isEmailExist = $this->isEmailExist($_POST['email-phone']);
        if ($isEmailExist) 
        {
            $response = array(
                "status" => "error",
                "message" => "Email already exists."
            );
        } 
        else
        {
            if (!empty($_POST['password'])) {
                // PHP's password_hash is the best choice to use to store passwords
                // do not attempt to do your own encryption, it is not safe
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $query = 'INSERT INTO jbe_employees (firstname, lastname, password, email_phone, department, job_description, branch, region, 
             linemanagername, linemanageremail, totalleave) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $paramType = 'sssssssssss';
            $paramValue = array(
                $_POST['firstname'],
                $_POST['lastname'],
                $hashedPassword,
                $_POST['email-phone'],
                $_POST['department'],
                $_POST['job-title'],
                $_POST['branch'],
                $_POST['region'],
                $_POST['line-manager'],
                $_POST['manager-email'],
                $_POST['total-leave']
            );
            $employeeId = $this->db_handle->insert($query, $paramType, $paramValue);

            if (!empty($employeeId)) {
                $year = date("Y") - 1;
                $daystaken = $_POST['total-leave'];
                $daysleft = 0;
                
                $query = 'INSERT INTO leave_years (employee_id, year, totalleave, daystaken, daysleft)
                VALUES (?, ?, ?, ?, ?)';

                $paramType = 'sssss';
                $paramValue = array(
                    $employeeId,
                    $year,
                    $_POST['total-leave'],
                    $daystaken,
                    $daysleft
                );
                $leaveId0 = $this->db_handle->insert($query, $paramType, $paramValue);

                if(!empty($leaveId0)){
                    $year = date("Y");
                    $daystaken = 0;
                    $daysleft = $_POST['total-leave'];
                    
                    $query = 'INSERT INTO leave_years (employee_id, year, totalleave, daystaken, daysleft)
                    VALUES (?, ?, ?, ?, ?)';

                    $paramType = 'sssss';
                    $paramValue = array(
                        $employeeId,
                        $year,
                        $_POST['total-leave'],
                        $daystaken,
                        $daysleft
                    );
                    $leaveId = $this->db_handle->insert($query, $paramType, $paramValue);
                }
                
                if(!empty($leaveId)){
                    $response = array(
                        "status" => "success",
                        "message" => "Employee registered successfully."
                    );
                }                
            }
        }
         return $response;
    }

    public function updateEmployeeRecord() {
        if($_SESSION['database-email_phone'] !== $_POST['email-phone']){
            $isEmailExist = $this->isEmailExist($_POST['email-phone']);
            if ($isEmailExist) 
            {
                $response = array(
                    "status" => "error",
                    "message" => "Email already exists."
                );
            }
        }else{
            $query = 'UPDATE jbe_employees SET firstname = ?, lastname = ?, email_phone = ?, department = ?, employeetype = ?, job_description = ?, branch = ?, 
            linemanagername = ?, linemanageremail = ?, totalleave = ?, status = ?  WHERE employee_id = ?';
            $paramType = 'sssssssssssi';
            $paramValue = array(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['email-phone'],
                $_POST['department'],
                $_POST['employee-type'],
                $_POST['job-description'],
                $_POST['branch'],
                $_POST['line-manager'],
                $_POST['line-manage-email'],
                $_POST['total-leave'],
                $_POST['employee-status'],
                $_POST['employee_id']
            );
            $employeeId = $this->db_handle->update($query, $paramType, $paramValue);
            $_SESSION['update-message'] = $_POST['firstname'] . " Employee Record Updated Succesfully";
            $response = array(
                "status" => "success",
                "message" => "Successfully Updated."
            );
        }

        return $response;
        
    }

    /**********************************************GET LEAVE YEAR RECORD*********************************************************/
    public function getEmployeeYearRecord($employee_id, $year){
        $query = "SELECT jbe_employees.employee_id, leave_years.* FROM jbe_employees INNER JOIN 
        leave_years ON jbe_employees.employee_id = leave_years.employee_id WHERE leave_years.employee_id = ? AND year = ?";
        
        $paramType = 'ss';
        $paramArray = array(
            $employee_id,
            $year
        );
        $employeeYearRecord = $this->db_handle->select($query, $paramType, $paramArray); 
        return $employeeYearRecord;
    }


    /**********************************************GET DEPARTMENT BASED ON DEPARTMENT ID*********************************************************/
    public function getDepartment($department_id) {
        $query = "SELECT * FROM departments WHERE department_id = ?";

        $paramType = "s";
        $paramArray = array(
            $department_id
        );

        $department = $this->db_handle->select($query, $paramType, $paramArray);
        return $department;
    }

    /**********************************************GET ALL DEPARTMENT*********************************************************/
    public function getDepartments() {
        $query = "SELECT * FROM departments";

        $paramType = "";
        $paramArray = "";

        $allDepartments = $this->db_handle->select($query, $paramType, $paramArray);
        return $allDepartments;
    }

    /**********************************************GET REGION BASED ON REGION ID*********************************************************/
    public function getRegion($region_id) {
        $query = "SELECT * FROM regions WHERE region_id = ?";

        $paramType = "s";
        $paramArray = array(
            $region_id
        );

        $region = $this->db_handle->select($query, $paramType, $paramArray);
        return $region;
    }

    /**********************************************GET ALL REGION*********************************************************/
    public function getRegions() {
        $query = "SELECT * FROM regions";

        $paramType = "";
        $paramArray = "";

        $allRegions = $this->db_handle->select($query, $paramType, $paramArray);
        return $allRegions;
    }

    /**********************************************GET BRANCH BASED OF BRANCH ID*********************************************************/
    public function getBranch($branch_id) {
        $query = "SELECT * FROM branches WHERE branch_id = ?";

        $paramType = "s";
        $paramArray = array(
            $branch_id
        );

        $branch = $this->db_handle->select($query, $paramType, $paramArray);
        return $branch;
    }

    public function getEmployeeBranch() {
        $query = "SELECT * FROM branches";

        $paramType = "";
        $paramArray = "";

        $branch = $this->db_handle->select($query, $paramType, $paramArray);
        return $branch;
    }

    /**********************************************GET ALL BRANCHES BASED ON REGION ID*********************************************************/
    public function getBranches($region_id) {
        $query = "SELECT * FROM branches WHERE region_id = ?";

        $paramType = "s";
        $paramArray = array(
            $region_id
        );

        $allBranches = $this->db_handle->select($query, $paramType, $paramArray);
        return $allBranches;
    }


    /**********************************************APPLY FOR LEAVE*********************************************************/
    public function applyforleave() {
        $getYearRecord = $this->getEmployeeYearRecord($_SESSION["employee-id"], $_POST["leave-year"]);

        $_SESSION['daystaken'] = $getYearRecord[0]['daystaken'];
        $_SESSION['daysleft'] = $getYearRecord[0]['daysleft'];

        $query = 'INSERT INTO jbe_employees_leave (employee_id, totalleave, daystaken, daysleft, start_date, end_date, noofdays, resumption_date, year, replacedby, leavetype) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $paramType = 'sssssssssss';
        $paramValue = array(
            $_SESSION["employee-id"],
            $_SESSION["totalleave"],
            $_SESSION["daystaken"],
            $_SESSION["daysleft"],
            $_POST["leave-commencing"],
            $_SESSION['endDate'],
            $_SESSION['noofdays'],
            $_POST["leave-resumption"],
            $_POST["leave-year"],
            $_POST["leave-replace"],
            $_POST['leave-type']
        );

        $employeeLeaveId = $this->db_handle->insert($query, $paramType, $paramValue);
        if(!empty($employeeLeaveId)) {
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'mail.qualisbusinesssupport.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'jbetest@qualisbusinesssupport.com';                     //SMTP username
                $mail->Password   = 'K3l3shamm@';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('jbetest@qualisbusinesssupport.com', 'Leave Application - Jubailibros Engineering');
                $mail->AddAddress($_SESSION["linemanageremail"], $_SESSION["linemanagername"]);	
                // $mail->AddAddress($_SESSION["email-phone"], $_SESSION["firstname"]); 

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Leave Application - '. $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; 
                $content = '<div style="background-color: white; padding: 5px;">
                <h5 style="font-size: 1.25rem">Dear ' .  $_SESSION['linemanagername'] . '</h5>
                <h6 style="font-size: 1rem">Kindly Approve <span style="color:#091281">' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . '</span> Leave Application </h6>
                <a style="font-size: 1rem" style="padding-top: 1px;" href="http://localhost/phpprojects/jbe-leave/public/">Click on link for rediection</a>
                <h6 style="font-size: 1rem">Best Regards</h6>
                </div>';
                $mail->MsgHTML($content);
                // $mail->Body    = 'Dear ' . $_SESSION['linemanagername'] . ' Kindly head to your Porter and Approve ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . ' Leave Application.' . ' Best Regards';

                $mail->send();
                $_SESSION['message'] = "Leave Application Sent to your Supervisor";
                $response = array(
                    "status" => "success",
                    "message" => $_SESSION['message']
                );
            } catch (Exception $e) {
                $_SESSION['message'] = "Message could not be sent. Updated Regardless";
                $response = array(
                    "status" => "error",
                    "message" => $_SESSION['message']
                );
            }
           
        }else{
            $_SESSION['message'] = "Something Went Wrong";
            $response = array(
                "status" => "error",
                "message" => $_SESSION['message']
            );
            
        }
        return $response;
    }

    public function updateTeamLeave($team_employee_id, $team_employee_leave_id){
        $query0 = "SELECT * FROM jbe_employees WHERE employee_id = ?";
        $paramType0 = "s";
        $paramArray0 = array(
            $team_employee_id
        );

        $teamDetails = $this->db_handle->select($query0, $paramType0, $paramArray0);

        if($_SESSION['team-leave-status'] == "Declined" || $_SESSION['team-leave-status'] == "Pending"){
            $query = "UPDATE jbe_employees_leave SET replacedby = ?, status = ? WHERE employee_id = ? AND employee_leave_id = ?";
           
            $paramType = "ssii";
            $paramArray = array(
                $_SESSION['team-leave-replaceby'],
                $_SESSION['team-leave-status'],
                $team_employee_id,
                $team_employee_leave_id
            );

            $updatedTeamLeave = $this->db_handle->update($query, $paramType, $paramArray);
            $_SESSION['message'] = "Team Record Updated!";
            $response = array(
                "status" => "success",
                "message" => $_SESSION['message']
            );
            
            return $response;  
        }
        else if($_SESSION['team-leave-status'] == "Approved" ){
            if (isset($_SESSION['team-leave-enddate']) && isset($_SESSION['team-leave-noofdays'])){
                $query = "UPDATE jbe_employees_leave SET start_date = ?, resumption_date = ?, end_date = ?, noofdays = ?, replacedby = ?, status = ? 
                WHERE employee_id = ? AND employee_leave_id = ?";
    
                $paramType = "ssssssii";
                $paramArray = array(
                    $_SESSION['team-leave-startdate'],
                    $_SESSION['team-leave-resumption'],
                    $_SESSION['team-leave-enddate'],
                    $_SESSION['team-leave-noofdays'],
                    $_SESSION['team-leave-replaceby'],
                    $_SESSION['team-leave-status'],
                    $team_employee_id,
                    $team_employee_leave_id
                );
    
                $updatedTeamLeave = $this->db_handle->update($query, $paramType, $paramArray);
            }else{
                $query = "UPDATE jbe_employees_leave SET start_date = ?, resumption_date = ?, replacedby = ?, status = ? 
                WHERE employee_id = ? AND employee_leave_id = ?";

                $paramType = "ssssii";
                $paramArray = array(
                    $_SESSION['team-leave-startdate'],
                    $_SESSION['team-leave-resumption'],
                    $_SESSION['team-leave-replaceby'],
                    $_SESSION['team-leave-status'],
                    $team_employee_id,
                    $team_employee_leave_id
                );

                $updatedTeamLeave = $this->db_handle->update($query, $paramType, $paramArray);
            }
            
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'mail.qualisbusinesssupport.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'jbetest@qualisbusinesssupport.com';                     //SMTP username
                $mail->Password   = 'K3l3shamm@';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('jbetest@qualisbusinesssupport.com', 'Leave Application - Jubailibros Engineering');
                $mail->AddAddress("Ayoaro85@gmail.com", "Ugoh Akongwubel");	
                // $mail->AddAddress($_SESSION["email-phone"], $_SESSION["firstname"]); 

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Leave Application - '. $teamDetails[0]['firstname'] . ' ' . $teamDetails[0]['lastname']; 
                $content = '<div style="background-color: white; padding: 5px;">
                <h5 style="font-size: 1.25rem">Dear Ugoh</h5>
                <h6 style="font-size: 1rem">Kindly find <span style="color:#091281">' . $teamDetails[0]['firstname'] . ' ' . $teamDetails[0]['lastname'] . '</span> Leave Application </h6>
                <a style="font-size: 1rem" style="padding-top: 1px;" href="http://localhost/phpprojects/jbe-leave/public/">Click on link for rediection</a>
                <h6 style="font-size: 1rem">Best Regards</h6>
                </div>';
                $mail->MsgHTML($content);
                // $mail->Body    = 'Dear ' . $_SESSION['linemanagername'] . ' Kindly head to your Porter and Approve ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . ' Leave Application.' . ' Best Regards';

                $mail->send();
                $_SESSION['message'] = $teamDetails[0]['firstname'] . " Leave Application Sent to HR";
                $response = array(
                    "status" => "success",
                    "message" => $_SESSION['message']
                );
            } catch (Exception $e) {
                $_SESSION['message'] = "Message could not be sent. Updated Regardless";
                $response = array(
                    "status" => "error",
                    "message" => $_SESSION['message']
                );
            }
            return $response;            
        }

    }

    public function getApprovedLeaveApplication($status, $hr_attend) {
        $query = 'SELECT jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone, jbe_employees.department, jbe_employees.branch, jbe_employees_leave.* FROM jbe_employees 
        INNER JOIN jbe_employees_leave ON jbe_employees.employee_id = jbe_employees_leave.employee_id WHERE jbe_employees_leave.status = ? 
        AND jbe_employees_leave.hr_attend = ? ORDER BY employee_leave_id DESC';

        $paramType = 'ss';
        $paramArray = array (
            $status,
            $hr_attend
        );

        $approvedLeaveApplication = $this->db_handle->select($query, $paramType, $paramArray);
        return $approvedLeaveApplication;
    }

    public function getApprovedLeaveApplicationLike($status, $hr_attend, $like) {
        $query = 'SELECT jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone, jbe_employees.department, jbe_employees.branch, jbe_employees_leave.* FROM jbe_employees 
        INNER JOIN jbe_employees_leave ON jbe_employees.employee_id = jbe_employees_leave.employee_id WHERE jbe_employees_leave.status = ? 
        AND jbe_employees_leave.hr_attend = ? AND CONCAT(jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone) LIKE ? ORDER BY employee_leave_id DESC';

        $likeappend = '%'.$like.'%';

        $paramType = 'sss';
        $paramArray = array (
            $status,
            $hr_attend,
            $likeappend
        );

        $approvedLeaveApplicationLike = $this->db_handle->select($query, $paramType, $paramArray);
        return $approvedLeaveApplicationLike;
    }


    public function updateEmployeeLeave($employee_id, $employee_leave_id) {
        $approvedLeave = $this->getApprovedLeaveApplication("Approved", "no");
    
        $totalleave = $approvedLeave[0]['totalleave'];
        $daystaken = $approvedLeave[0]['daystaken'] + $approvedLeave[0]['noofdays'];
        $daysleft = $totalleave - $daystaken;
        $hr_attend = "yes";
        $year = $_SESSION["process-team-year"];
        $firstname = $approvedLeave[0]['firstname'];
        $lastname = $approvedLeave[0]['lastname'];
        $email = $approvedLeave[0]['email_phone'];

        $query0 = "UPDATE leave_years SET daystaken = ?, daysleft = ? WHERE employee_id = ? AND year = ?";
        $paramType0 = "iiis";
        $paramArray0 = array(
            $daystaken,
            $daysleft,
            $employee_id,
            $year
        );
        $updateemployeeyear = $this->db_handle->update($query0, $paramType0, $paramArray0);
        
        $query = "UPDATE jbe_employees_leave SET daystaken = ?, daysleft = ?, hr_attend = ? WHERE employee_id = ? AND employee_leave_id = ?";

        $paramType = "iisii";
        $paramArray = array(
            $daystaken,
            $daysleft,
            $hr_attend,
            $employee_id,
            $employee_leave_id
        );

        $updateemployeeapplication = $this->db_handle->update($query, $paramType, $paramArray);
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.qualisbusinesssupport.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'jbetest@qualisbusinesssupport.com';                     //SMTP username
            $mail->Password   = 'K3l3shamm@';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('jbetest@qualisbusinesssupport.com', 'Leave Application - Jubailibros Engineering');
            $mail->AddAddress("Ayoaro85@gmail.com", "Ugoh Akongwubel");	
            // $mail->AddAddress($_SESSION["email-phone"], $_SESSION["firstname"]); 

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Leave Application - '. $firstname . ' ' . $lastname; 
            $content = '<div style="background-color: white; padding: 2px;">
            <h5 style="font-size: 1.25rem">Dear ' . $firstname . '</h5>
            <h6 style="font-size: 1rem"> Your Leave Request Have Been Approved And Processeed. Kindly check your porter for more information.</h6>
            <a style="font-size: 1rem" href="http://localhost/phpprojects/jbe-leave/public/">Click on link for rediection</a>
            <h6 style="font-size: 1rem">Best Regards</h6>
            </div>';
            $mail->MsgHTML($content);

            $mail->send();
            $_SESSION['message'] =  $firstname . " " . $lastname . " Leave Application have been processed Successfully.";
            $response = array(
                "status" => "success",
                "message" => $_SESSION['message']
            );
        } catch (Exception $e) {
            $_SESSION['message'] = "Message could not be sent. Updated Regardless";
            $response = array(
                "status" => "error",
                "message" => $_SESSION['message']
            );
        }
        return $response;
    }

    public function getleaveapplication($employee_id, $year){
        $query = 'SELECT * FROM jbe_employees_leave WHERE employee_id = ? AND year = ?';
        
        $paramType = 'ss';
        $paramArray = array (
            $employee_id,
            $year
        );

        $leaveapplication = $this->db_handle->select($query, $paramType, $paramArray);
        return $leaveapplication;
    }


    public function getAllTeam($department, $employeetype){
        $query = 'SELECT * FROM jbe_employees WHERE department = ? AND employeetype = ?';

        $paramType = 'ss';
        $paramArray = array (
            $department,
            $employeetype
        );

        $allTeam = $this->db_handle->select($query, $paramType, $paramArray);
        return $allTeam;
    }

    public function getAllTeamApplication($department, $employeetype, $status) {
        $query = 'SELECT jbe_employees.employee_id, jbe_employees.firstname, jbe_employees.lastname, jbe_employees_leave.* FROM jbe_employees 
        INNER JOIN jbe_employees_leave ON jbe_employees.employee_id = jbe_employees_leave.employee_id WHERE 
        jbe_employees.department = ? AND jbe_employees.employeetype = ? AND jbe_employees_leave.status = ?';

        $paramType = 'sss';
        $paramArray = array (
            $department,
            $employeetype,
            $status
        );

        $teamApplication = $this->db_handle->select($query, $paramType, $paramArray);
        return $teamApplication;
    }

    public function getTeamLeaveRecord($department, $employeetype, $year) {
        $query = 'SELECT jbe_employees.*, leave_years.* FROM jbe_employees INNER JOIN leave_years ON jbe_employees.employee_id = leave_years.employee_id 
        WHERE jbe_employees.department = ? AND jbe_employees.employeetype = ? AND leave_years.year = ?';

        $paramType = 'sss';
        $paramArray = array (
            $department,
            $employeetype,
            $year
        );

        $teamLeaveRecord = $this->db_handle->select($query, $paramType, $paramArray);
        return $teamLeaveRecord;
    }

    public function getEmployeeLeaveRecord($employee_leave_id, $employee_id, $status) {
        $query = 'SELECT jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone, jbe_employees.branch, jbe_employees.department, jbe_employees_leave.* FROM jbe_employees 
        INNER JOIN jbe_employees_leave ON jbe_employees.employee_id = jbe_employees_leave.employee_id WHERE jbe_employees.employee_id = ? 
        AND jbe_employees_leave.employee_leave_id = ? AND jbe_employees_leave.status = ?';

        $paramType = 'sss';
        $paramArray = array (
            $employee_id,
            $employee_leave_id,
            $status
        );

        $employeeLeaveDetail = $this->db_handle->select($query, $paramType, $paramArray);
        return $employeeLeaveDetail;
    }

    public function printedGrantedLeave($employee_id, $employee_leave_id){
        $query = 'SELECT jbe_employees.firstname, jbe_employees.lastname, jbe_employees.email_phone, jbe_employees.branch, jbe_employees.department, jbe_employees_leave.* FROM jbe_employees 
        INNER JOIN jbe_employees_leave ON jbe_employees.employee_id = jbe_employees_leave.employee_id WHERE jbe_employees.employee_id = ? 
        AND jbe_employees_leave.employee_leave_id = ?';

        $paramType = 'ii';
        $paramArray = array (
            $employee_id,
            $employee_leave_id
        );

        $printForm = $this->db_handle->select($query, $paramType, $paramArray);
        return $printForm;
    }

}

?>