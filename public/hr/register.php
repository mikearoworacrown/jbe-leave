<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Staff Record";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }

    $employeeRecord = new Employee();
    $employeeDetails = $employeeRecord->getEmployee($_SESSION['email-phone']);

    $departments = $employeeRecord->getDepartments();
    $regions = $employeeRecord->getRegions();
    $today = date("d-m-Y");

    include(SHARED_PATH . "/header.php");
?>

<style>
    .jbe__register-form {
        width: 100%;
        margin: 1rem auto 3rem auto;
        padding: 30px;
        border-radius: 15px;
        background-color: #fff;
        border: 2px solid var(--primary-color);
    }

    .jbe__form-title {
        margin: 8rem auto 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--primary-color);
        color: #fff;
        width: 200px;
        height: 40px;
        border-radius: 1rem;
    }

    .jbe__register-submit{
        display: block;
        width: 100%;
        margin-top: 1rem;
        padding: 0.75rem;
        background: var(--primary-color);
        color: #fff;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.2em;
        font-weight: bold;
    }

    .jbe__register label {
        font-weight: 600;
    }
   .jbe__register input {
        border: 2px solid var(--primary-color);
        font-weight: 700;
        color: var(--primary-color);
    }

    .jbe__register select {
        border: 2px solid var(--primary-color);
        font-weight: 700;
        color: var(--primary-color);
    }

    @media (min-width: 576px) {
        .jbe__register-form{
            max-width:540px;
        }
    }

    @media (min-width: 768px) {
        .jbe__register-form{
            max-width: 650px;
        }
    }
</style>

<section class="jbe__fluid-container">
    <div class="jbe__form-title h5">Register Employee</div>
    <div class="jbe__container jbe__register-form">
        <form class="jbe__register" id="jbe__register" action="" autocomplete="off">
            <div class="jbe__error-msg" id="jbe__error-msg">This is an error message</div>
            <div class="jbe__success-msg" id="jbe__success-msg">This is a success message</div>
            <div class="form-group">
                <label for="email-phone" id="label-email-phone">Email Address/Phone Number<span class="jbe__required jbe__error" id="email-phone-info"></span></label>
                <input type="text" class="form-control email-phone" name="email-phone" id="email-phone" placeholder="Enter Email Address / Phone Number" value ="" required/>
            </div>
            <div class="form-group">
                <label for="firstname" id="label-firstname">Firstname<span class="jbe__required jbe__error" id="firstname-info"></span></label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter Firstname" value =""required/>
            </div>
            <div class="form-group">
                <label for="lastname" id="label-lastname">Lastname<span class="jbe__required jbe__error" id="lastname-info"></span></label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Lastname" value ="" required/>
            </div>
           
            <div class="form-group">
                <label for="department" id="label-department">Department<span class="jbe__required jbe__error" id="department-info"></span></label>
                <select id="department" name="department-id" class="form-select" required>
                    <option disabled selected value="">Select Department</option>
                    <?php 
                        if(!empty($departments)){
                            for($i = 0; $i < count($departments); $i++){
                    ?>
                                <option value="<?php echo $departments[$i]['department_id'] ?>"><?php echo $departments[$i]['department'] ?></option>
                    <?php       
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job-title" id="label-job-title">Job Title<span class="jbe__required jbe__error" id="job-info"></span></label>
                <input type="text" class="form-control" name="job-title" id="job-title" placeholder="Enter Job Title" value ="" required/>
            </div>

            <div class="form-group">
                <label for="total-leave" id="label-total-leave">Total Leave<span class="jbe__required jbe__error" id="total-leave-info"></span></label>
                <input type="text" class="form-control" name="total-leave" id="total-leave" placeholder="Enter Total Leave" value ="" required/>
            </div>

            <div class="form-group">
                <label for="line-manager" id="label-line-manager">Line Manager<span class="jbe__required jbe__error" id="line-manager-info"></span></label>
                <input type="text" class="form-control" name="line-manager" id="line-manager" placeholder="Enter Line Manager Name" value ="" required/>
            </div>

            <div class="form-group">
                <label for="manager-email" id="label-manager-email">Line Manager Email<span class="jbe__required jbe__error" id="manager-email-info"></span></label>
                <input type="text" class="form-control" name="manager-email" id="manager-email" placeholder="Enter Line Manager Email" value ="" required/>
            </div>

            <div class="form-group">
                <label for="region" id="label-region">Region<span class="jbe__required jbe__error" id="region-info"></span></label>
                <select id="region" name="region-id" class="form-select" required>
                    <option disabled selected value="">Select Region</option>
                    <?php 
                        if(!empty($regions)){
                            for($i = 0; $i < count($regions); $i++){
                    ?>
                                <option value="<?php echo $regions[$i]['region_id'] ?>"><?php echo $regions[$i]['region'] ?></option>
                    <?php       
                            }
                        }
                    ?>
                    <script>
                        let region = document.querySelector("#region");
                        region.onchange = () => {
                            let region_id = region.value;
                            let toSend = {
                                region_id: region_id
                            }
                            let xhr = new XMLHttpRequest(); //creating XML object
                            xhr.open("POST", "getbranches.php", true);
                            xhr.onload = () => {
                                if(xhr.readyState === XMLHttpRequest.DONE){
                                    if(xhr.status === 200){
                                        let data = xhr.response;
                                        let dataParsed = JSON.parse(data);
                                        //console.log(dataParsed[0].hasOwnProperty('branch'));
                                        if(dataParsed[0].hasOwnProperty('branch')){
                                            let select = document.querySelector("#branch");
                                            while (select.lastChild.value !== '') {
                                                select.removeChild(select.lastChild);
                                            }
                                            for(let i = 0; i < dataParsed.length; i++){
                                                let option = document.createElement("option");
                                                option.text = dataParsed[i]['branch'];
                                                option.value = dataParsed[i]['branch_id'];
                                                select.appendChild(option);
                                            }
                                        }else {
                                            let select = document.querySelector("#branch");
                                            while (select.lastChild.value !== '') {
                                                select.removeChild(select.lastChild);
                                            }
                                            let option = document.createElement("option");
                                            option.text = dataParsed[0]['region'];
                                            option.value = dataParsed[0]['region_id'];
                                            select.appendChild(option);
                                        }
                                        
                                    }
                                }
                            }
                            let jsonString = JSON.stringify(toSend);
                            // console.log(jsonString);
                            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                            xhr.send(jsonString);
                        }
                    </script>
                </select>
            </div>
            <div class="form-group">
                <label for="branch" id="label-branch">Branch<span class="jbe__required jbe__error" id="branch-info"></span></label>
                <select id="branch" name="branch-id" class="form-select" required>
                    <option disabled selected value="">Select Branch</option>
                    
                </select>
            </div>
            
            <div class="jbe__password-msg" id="jbe__password-msg">
                
            </div>
            <div class="form-group">
                <label for="password" id="label-password">Password<span class="jbe__required jbe__error" id="password-info"></span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required/>
                <i class="fas fa-eye"></i>
            </div>
            <div class="form-group">
                <button type="submit" id="jbe__register-submit" class="jbe__register-submit" name="jbe__register-submit">Create Employee</button>
            </div>
        </form>
    </div>
</section>

<?php
    include(SHARED_PATH . "/footer.php");
?>