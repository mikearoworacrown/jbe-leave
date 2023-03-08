<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Leave Request Form";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }

    $employeeRecord = new Employee();
    $employeeDetails = $employeeRecord->getEmployee($_SESSION['email-phone']);

    $today = date("d-m-Y");

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
                        <input type="text" class="form-control" name="leave-name" id="leave-name" value="<?php echo $employeeDetails[0]['firstname'] . " " . $employeeDetails[0]['lastname']?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-department">Department: </label>
                        <input type="text" class="form-control" name="leave-department" id="leave-department" value="<?php echo $employeeDetails[0]['department']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-branch">Branch: </label>
                        <input type="text" class="form-control" name="leave-branch" id="leave-branch" value="<?php echo $employeeDetails[0]['branch']; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave-employeeid">Employee ID: </label>
                        <input type="text" class="form-control" name="leave-employeeid" id="leave-employeeid" value="<?php echo $employeeDetails[0]['employee_id']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-year">Leave Year: <span class="jbe__required jbe__error" id="daysleft"></span><span class="jbe__required">day(s) left</span></label>
                        <input type="hidden" id="employee_id" value="<?php echo $_SESSION['employee-id'];?>">
                        <select class="form-select selectleaveyear" name="leave-year" id="leave-year" required>
                            <option value="" disabled selected>Select Leave Year</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <script>
                        let leaveYear = document.querySelector("#leave-year");
                        leaveYear.onchange = () => {
                            let employee_id = document.querySelector("#employee_id").value;
                            let year = leaveYear.value;
                            let toSend = {
                                employee_id: employee_id,
                                year: year
                            }
                            // console.log(value);
                            let xhr = new XMLHttpRequest(); //creating XML object
                            xhr.open("POST", "../ajax_php/getyear.php", true);
                            xhr.onload = () => {
                                if(xhr.readyState === XMLHttpRequest.DONE){
                                    if(xhr.status === 200){
                                        let data = xhr.response;
                                        let dataParsed = JSON.parse(data);
                                        let daysleft = document.querySelector("#daysleft");
                                        daysleft.innerHTML = dataParsed[0]['daysleft'];
                                    }
                                }
                            }
                            let jsonString = JSON.stringify(toSend);
                            // console.log(jsonString);
                            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                            xhr.send(jsonString);
                        }
                    </script>
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
                        <input type="text" class="form-control leave-commencing" name="leave-commencing" id="datepicker1" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-resumption">Resumption Date: <span class="jbe__required jbe__error"></span></label>
                        <input type="text" class="form-control leave-resumption" name="leave-resumption" id="datepicker3" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-ending">Ending Date: <span class="jbe__required jbe__error">Fill commencing and resumption date</span></label>
                        <input type="text" class="form-control leave-ending" name="leave-ending" id="datepicker2" style="background-color: transparent; border: none;" disabled required>
                    </div>
                </div>  

                <div class="col-md-6 pt-5">
                    <div class="form-group">
                        <label for="leave-replace">To be Replaced By: <span class="jbe__required jbe__error"></span></label>
                        <input type="text" class="form-control" name="leave-replace" id="leave-replace" required>
                    </div>
                    <div class="form-group">
                        <label for="leave-status">Status: </label>
                        <input type="text" class="form-control" name="leave-status" id="leave-status" value="Pending" style="color: #ffc107;" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-noofdays">Number of leave days:<span class="jbe__required jbe__error dateleft-error"></span></label>
                        <input type="text" class="form-control leave-noofdays" name="leave-noofdays" id="leave-noofdays" style="background-color: transparent; border: none;" required disabled>
                    </div>
                </div>
            </div>
            
            <script>
                let startDate = document.querySelector('.leave-request-form .leave-commencing');
                let resumptionDate = document.querySelector('.leave-request-form .leave-resumption');
                let endDate = document.querySelector('.leave-request-form .leave-ending');
                let noOfDays = document.querySelector('.leave-request-form .leave-noofdays');
               

                startDate.onchange = () => {
                    // console.log(startDate.value);
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
                    // console.log(resumptionDate.value);
                    let resumptionDateValue = {
                        resumptionDateInputValue: resumptionDate.value
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
            <button type="submit" class="jbe__Leave-submit" id="jbe__Leave-submit">Apply</button>
        </form>
    </div>
</section>

<?php
    include(SHARED_PATH . "/footer.php");
?>