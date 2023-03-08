<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $employee = new Employee();
    $employee_id = $_GET['employee_id'];
    $employeeRecord = $employee->getEmployeeById($employee_id);
    $departments = $employee->getDepartments();
    $branches = $employee->getEmployeeBranch();

    if(empty($employeeRecord)){
        header('Location: ../');
        exit();
    }

    include(SHARED_PATH . "/header.php");
?>

<section class="jbe__container-fluid jbe__request-form">
    <div class="jbe__container">
        <div class="jbe__request-form-h4">
            <div class="line1"></div>
            <h4>Edit Employee Record</h4>
            <div class="line2"></div>
        </div>
        <form action="" method="post" class="update-employee">
            <div class="row leaveform-row1">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstname">Firstname: </label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $employeeRecord[0]['firstname'];?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Lastname: </label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $employeeRecord[0]['lastname']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email-phone">Email Address / Phone Number: </label>
                        <input type="text" class="form-control" name="email-phone" id="email-phone" value="<?php echo $employeeRecord[0]['email_phone']; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee-id">Employee ID: </label>
                        <input type="text" class="form-control" name="employee-id" id="employee-id" value="<?php echo $employeeRecord[0]['employee_id']; ?>" disabled>
                        <input type="hidden" name="employee_id" value="<?php echo $employeeRecord[0]['employee_id']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="employee-type">Employee Type: </label>
                        <select id="employee-type" name="employee-type" class="form-select" required>
                            <option value="user" <?php if($employeeRecord[0]['employeetype'] == 'user'){ ?> selected="<?php echo "selected";}?>">user</option>
                            <option value="supervisor" <?php if($employeeRecord[0]['employeetype'] == 'supervisor'){ ?> selected="<?php echo "selected";}?>">supervisor</option>
                            <option value="hr" <?php if($employeeRecord[0]['employeetype'] == 'hr'){ ?> selected="<?php echo "selected";}?>">hr</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="job-description">Job Description: </label>
                        <input type="text" class="form-control" name="job-description" id="job-description" value="<?php echo $employeeRecord[0]['job_description']; ?>">
                    </div>
                </div>
            </div>

            <div class="row leaveform-row2">
                <div class="col-md-6 pt-5">
                    <div class="form-group">
                        <label for="department">Department: </label>
                        <select id="department" name="department" class="form-select" required>
                            <?php 
                                if(!empty($departments)){
                                    for($i = 0; $i < count($departments); $i++){
                            ?>
                                        <option value="<?php echo $departments[$i]['department'] ?>" <?php if($employeeRecord[0]['department'] == $departments[$i]['department']){ ?> selected="<?php echo "selected";}?>"><?php echo $departments[$i]['department'] ?></option>
                            <?php       
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="branch">Branch </label>
                        <select id="branch" name="branch" class="form-select branch" required>
                            <?php 
                                if(!empty($branches)){
                                    for($i = 0; $i < count($branches); $i++){
                            ?>
                                        <option value="<?php echo $branches[$i]['branch'] ?>" <?php if($employeeRecord[0]['branch'] == $branches[$i]['branch']){ ?> selected="<?php echo "selected";}?>"><?php echo $branches[$i]['branch'] ?></option>
                            <?php       
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="line-manager">Line Manager:</label>
                        <input type="text" class="form-control line-manager" name="line-manager" id="line-manager" value="<?php echo $employeeRecord[0]['linemanagername']; ?>">
                    </div>
                </div>  

                <div class="col-md-6 pt-5">
                    <div class="form-group">
                        <label for="line-manage-email">Line Manager's Email:</label>
                        <input type="text" class="form-control" name="line-manage-email" id="line-manage-email" value="<?php echo $employeeRecord[0]['linemanageremail']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="total-leave">Total days of Leave: </label>
                        <input type="text" class="form-control" name="total-leave" id="total-leave" value="<?php echo $employeeRecord[0]['totalleave']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="employee-status">Status:</label>
                        <select id="employee-status" name="employee-status" class="form-select" required>
                            <option value="active" <?php if($employeeRecord[0]['status'] == 'active'){ ?> selected="<?php echo "selected";}?>">Active</option>
                            <option value="inactive" <?php if($employeeRecord[0]['status'] == 'inactive'){ ?> selected="<?php echo "selected";}?>">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        
            <div class="loader"></div>
            <button type="submit" class="jbe__update-employee" id="jbe__update-employee">Update</button>
        </form>
    </div>
</section>

<?php
    include(SHARED_PATH . "/footer.php");
?>