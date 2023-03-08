<?php 
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Edit User Leave Form";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'supervisor'){
        header('Location: ../');
        exit();
    }

    $employee = new Employee();

    $employee_id = $_GET['employee_id'];
    $employee_leave_id = $_GET['employee_leave_id'];
    $status = 'Pending';
    $_SESSION['team-id'] = $employee_id;
    $_SESSION['team-leave-id'] = $employee_leave_id;
    $today = date('d/m/Y');

    $employeeLeaveDetail = $employee->getEmployeeLeaveRecord($employee_leave_id, $employee_id, $status);

    if(empty($employeeLeaveDetail)){
        header('Location: ../');
        exit();
    }

    include(SHARED_PATH . "/header.php");
?>


<section class="jbe__container-fluid jbe__request-form">
    <div class="jbe__container">
        <div class="jbe__request-form-h4">
            <div class="line1"></div>
            <h4>Leave Request Form - Locals</h4>
            <div class="line2"></div>
        </div>
        <form action="" method="post" class="leave-request-form">
            <div class="row leaveform-row1">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave-name">Name: </label>
                        <input type="text" class="form-control" name="leave-name" id="leave-name" value="<?php echo $employeeLeaveDetail[0]['firstname'] . " " . $employeeLeaveDetail[0]['lastname']?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-department">Department: </label>
                        <input type="text" class="form-control" name="leave-department" id="leave-department" value="<?php echo $employeeLeaveDetail[0]['department']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-branch">Branch: </label>
                        <input type="text" class="form-control" name="leave-branch" id="leave-branch" value="<?php echo $employeeLeaveDetail[0]['branch']; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave-employeeid">Employee ID: </label>
                        <input type="text" class="form-control" name="leave-employeeid" id="leave-employeeid" value="<?php echo $employeeLeaveDetail[0]['employee_id']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-year">Leave Year: <span class="jbe__required jbe__error daysleft" id="daysleft"><?php echo $employeeLeaveDetail[0]['daysleft']; ?></span><span class="jbe__required"> day(s) left</span></label>
                        <input type="text" class="form-control" name="leave-year" id="leave-year" value="<?php echo $employeeLeaveDetail[0]['year']; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="leave-date">Date: </label>
                        <input type="text" class="form-control" name="leave-date" id="leave-date" value="<?php echo $today; ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="row leaveform-row2">
                <div class="leave-type">
                    <h4 class="mb-5">Leave Type</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leave-type" id="leave-type1" value="Annual Leave" checked>
                        <label class="form-check-label" for="leave-type1">
                            Annual Leave
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leave-type" id="leave-type2" value="Casual Leave">
                        <label class="form-check-label" for="leave-type2">
                            Casual Leave
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leave-type" id="leave-type3" value="Sick Leave">
                        <label class="form-check-label" for="leave-type3">
                            Sick Leave
                        </label>
                    </div>
                </div>

                <div class="col-md-6 pb-5">
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="leave-type" id="leave-type4" value="Leave Without Pay">
                        <label class="form-check-label" for="leave-type4">
                            Leave Without Pay
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input mt-3" type="radio" name="leave-type" id="leave-type5" value="Other: ">
                        <label class="form-check-label" for="leave-type5">
                            Other <input type="text" class="form-control mt-1 ms-1" name="leave-type-other" id="leave-type-other" placeholder="Identify Reason">
                        </label>
                    </div>
                </div>

                <hr />

                <div class="col-md-6 pt-5">
                    <div class="form-group">
                        <label for="leave-commencing">Commencing Date: <span class="jbe__required jbe__error date-error"></span></label>
                        <input type="text" class="form-control leave-commencing" name="leave-commencing" id="datepicker1" value="<?php echo $employeeLeaveDetail[0]['start_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-resumption">Resumption Date: <span class="jbe__required jbe__error"></span></label>
                        <input type="text" class="form-control leave-resumption" name="leave-resumption" id="datepicker3" value="<?php echo $employeeLeaveDetail[0]['resumption_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-ending">Ending Date: <span class="jbe__required jbe__error"></span></label>
                        <input type="text" class="form-control leave-ending" name="leave-ending" id="datepicker2" value="<?php echo $employeeLeaveDetail[0]['end_date']; ?>" disabled>
                        <input type="hidden" name='notchangedenddate' value="<?php echo $employeeLeaveDetail[0]['end_date']; ?>">
                    </div>
                </div>  

                <div class="col-md-6 pt-5">
                    <div class="form-group">
                        <label for="leave-replace">To be Replaced By: <span class="jbe__required jbe__error"></span></label>
                        <input type="text" class="form-control" name="leave-replace" id="leave-replace" value="<?php echo $employeeLeaveDetail[0]['replacedby']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-status">Status: <span class="jbe__required jbe__error"></span></label>
                        <select class="form-select" name="leave-status" id="leave-status" style="color:#ffc107;" required>
                            <option value="Pending" style="color:#ffc107;" selected>Pending</option>
                            <option value="Approved" style="color:green">Approved</option>
                            <option value="Declined" style="color:red">Declined</option>
                        </select>
                        <script>
                            let select = document.querySelector('#leave-status');
                            select.onchange = () => {
                                if(select.value == "Pending") {
                                    select.style.color = "#ffc107";
                                }else if(select.value == "Approved"){
                                    select.style.color = "#198754";
                                }else if(select.value == "Declined"){
                                    select.style.color = "#D0312D";
                                }
                            }
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="leave-noofdays">Number of days: <span class="jbe__required jbe__error daysleft1"><?php echo $employeeLeaveDetail[0]['daysleft'] . " "; ?> days left</span></label>
                        <input type="text" class="form-control leave-noofdays" name="leave-noofdays" id="leave-noofdays" value="<?php echo $employeeLeaveDetail[0]['noofdays']; ?>"  disabled>
                        <input type='hidden' name='notchangednoofdays' value="<?php echo $employeeLeaveDetail[0]['noofdays']; ?>">
                    </div>
                </div>
            </div>
            <script>
                let startDate = document.querySelector('.leave-request-form .leave-commencing');
                let resumptionDate = document.querySelector('.leave-request-form .leave-resumption');
                let endDate = document.querySelector('.leave-request-form .leave-ending');
                let noOfDays = document.querySelector('.leave-request-form .leave-noofdays');
               
                
                startDate.onchange = () => {
                    resumptionDate.value = "";
                    let startDateValue = {
                        startDateInputValue: startDate.value
                    }
                    let xhr = new XMLHttpRequest(); //creating XML object
                    xhr.open("POST", "../ajax_php/noofdays.php", true);
                    xhr.onload = () => {
                        if(xhr.readyState === XMLHttpRequest.DONE){
                            if(xhr.status === 200){
                            }
                        }
                    }
                    let date1 = JSON.stringify(startDateValue);
                    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                    xhr.send(date1);
                }

                resumptionDate.onchange = () => {
                    let temp = startDate.value;
                    let resumptionDateValue = {
                        resumptionDateInputValue: resumptionDate.value,
                        startDateInputValue: temp
                    }
                    let xhr = new XMLHttpRequest(); //creating XML object
                    xhr.open("POST", "../ajax_php/noofdays.php", true);
                    xhr.onload = () => {
                        if(xhr.readyState === XMLHttpRequest.DONE){
                            if(xhr.status === 200){
                                let data = xhr.response;
                                let dateParsed = JSON.parse(data);
                                if(dateParsed.hasOwnProperty("message")){
                                    console.log(dateParsed["message"]);
                                    let dateError = document.querySelector('.date-error');
                                    dateError.innerHTML = dateParsed["message"];
                                }else{
                                    let daysleft = document.querySelector("#daysleft").textContent;
                                    endDate.value = dateParsed["enddate"];
                                    noOfDays.value = dateParsed["noofdays"];
                                    if(noOfDays.value > Number(daysleft)) {
                                        let leaveresumption = document.querySelector(".leave-resumption");
                                        leaveresumption.value = "";
                                        document.querySelector(".dateleft-error").textContent = "Cannot be greater than days left.";
                                    }
                                }
                                
                            }
                        }
                    }
                    let date2 = JSON.stringify(resumptionDateValue)
                    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                    xhr.send(date2);
                }
            </script>
            <div class="loader"></div>
            <button type="submit" class="jbe__updateLeave-submit" id="jbe__updateLeave-submit">Process Team Leave</button>
        </form>
    </div>
</section>


<?php
    include(SHARED_PATH . "/footer.php");
?>