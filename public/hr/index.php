<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Leave Form";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }

    unset($_SESSION['endDate']);
    unset($_SESSION['noofdays']);
    
    $employeeRecord = new Employee();
    $employeeDetails = $employeeRecord->getEmployee($_SESSION['email-phone']);
    $_SESSION['firstname'] = $employeeDetails[0]['firstname'];

    $year = date('Y');
    $employee_id = $_SESSION['employee-id'];
    $employeeYearDetails = $employeeRecord->getEmployeeYearRecord($employee_id, $year);
    $getleaveapplication = $employeeRecord->getleaveapplication($employee_id, $year);

    $status = "Approved";
    $hr_attend  = "no";
    $approvedLeave = $employeeRecord->getApprovedLeaveApplication($status, $hr_attend);



    include(SHARED_PATH . "/header.php");
?>

<section class="jbe__container-fluid jbe__homepage">
    <div class="jbe__container">
        <?php if(isset($_SESSION['message'])){
            echo "
                <div id='toastr-message'>
                    <h6>". $_SESSION['message'] ."</h6>
                </div>
            ";
        }
        unset($_SESSION['message']);
        ?>
        <div class="jbe__homepage-welcome">
            <h4>Welcome <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['firstname'] . " " . $employeeDetails[0]['lastname'] ?></span></h4>
            <a href="<?php echo url_for('/hr/leaveform.php')?>" class="h6 button">Apply For Leave</a>
        </div>
        <h5 class="jbe__general-header-h5">Leave information</h5>
        <div class="jbe__homepage-leave-info">
            <div class="row">
                <div class="col-md-6">
                    <h6>Job Title: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['job_description'];  ?></span></h6>
                    <h6>Department: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['department'];  ?></span></h6>
                    <h6>Line Manager: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['linemanagername'];  ?></span></h6>
                    <h6>Branch: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['branch'];  ?></span></h6>
                </div>
                <div class="col-md-6">
                    <h6>Year:
                        <input type="hidden" class="employee_id" value="<?php echo $_SESSION['employee-id'];?>">
                        <select class="indexselect leaveyearindex">
                            <option value="2022">2022</option>
                            <option value="2023" selected>2023</option>
                        </select>
                        <script>
                            let leaveYear = document.querySelector(".leaveyearindex");
                            leaveYear.onchange = () => {
                                let employee_id = document.querySelector(".employee_id").value;
                                let year = leaveYear.value;
                                let toSend = {
                                    employee_id: employee_id,
                                    year: year
                                }
                                // console.log(value);
                                let xhr = new XMLHttpRequest(); //creating XML object
                                xhr.open("POST", "../ajax_php/getyearapplication.php", true);
                                xhr.onload = () => {
                                    if(xhr.readyState === XMLHttpRequest.DONE){
                                        if(xhr.status === 200){
                                            let data = xhr.response;
                                            let dataParsed = JSON.parse(data);
                                            // console.log(dataParsed);
                                            let tbody = document.querySelector("#tbody");
                                            if(dataParsed[0].hasOwnProperty('status')){
                                                let tbody = document.querySelector("#tbody");
                                                let daystaken = document.querySelector(".indexdaystaken");
                                                let daysleft = document.querySelector(".indexdaysremaining");
                                                let startdate = document.querySelector("#startdate");
                                                let enddate = document.querySelector("#enddate");
                                                let resumptiondate = document.querySelector("#resumptiondate");
                                                let noofdays = document.querySelector("#noofdays");
                                                let status = document.querySelector("#status");
                                                let replacedby = document.querySelector("#replacedby");
                                                let rownumber = document.querySelector("#rownumber");
                                                daystaken.innerHTML = dataParsed[0]['daystaken'];
                                                daysleft.innerHTML = dataParsed[0]['daysleft'];

                                                tbody.innerHTML = "";
                                                // console.log(dataParsed.length);
                                                for(let i = 0; i < dataParsed.length; i++){
                                                    let tbody = document.getElementById("tbody");
                                                    let tr = document.createElement("tr");
                                                    let th = document.createElement("th");
                                                    th.appendChild(document.createTextNode(i+1));
                                                    tr.appendChild(th);
                                                    let td1 = document.createElement('td');
                                                    td1.appendChild(document.createTextNode(dataParsed[i]['start_date']));
                                                    tr.appendChild(td1);
                                                    let td2 = document.createElement('td');
                                                    td2.appendChild(document.createTextNode(dataParsed[i]['end_date']));
                                                    tr.appendChild(td2);
                                                    let td3 = document.createElement('td');
                                                    td3.appendChild(document.createTextNode(dataParsed[i]['resumption_date']));
                                                    tr.appendChild(td3);
                                                    let td4 = document.createElement('td');
                                                    td4.appendChild(document.createTextNode(dataParsed[i]['noofdays']));
                                                    tr.appendChild(td4);
                                                    let td5 = document.createElement('td');
                                                    td5.appendChild(document.createTextNode(dataParsed[i]['replacedby']));
                                                    tr.appendChild(td5);
                                                    let td6 = document.createElement('td');
                                                    let span = document.createElement('span');
                                                    span.appendChild(document.createTextNode(dataParsed[i]['status']));
                                                    td6.appendChild(span);
                                                    
                                                    if(dataParsed[i]['status'] == "Pending") {
                                                        span.style.backgroundColor = "#ffc107";
                                                        span.style.padding = "0.2rem";
                                                        span.style.borderRadius = "0.3rem";
                                                        let i = document.createElement('i');
                                                        i.setAttribute("class", "fas fa-times h5");
                                                        td6.appendChild(i);
                                                        i.style.color = "#D0312D";
                                                        i.style.paddingLeft = "4px";
                                                    } else if(dataParsed[i]['status'] == "Approved" && dataParsed[i]['hr_attend'] == 'yes') {
                                                        span.style.backgroundColor = "#198754";
                                                        span.style.padding = "0.2rem";
                                                        span.style.borderRadius = "0.3rem";
                                                        let i = document.createElement('i');
                                                        i.setAttribute("class", "fas fa-check h5");
                                                        td6.appendChild(i);
                                                        i.style.color = "#198754";
                                                        i.style.paddingLeft = "4px";
                                                    }else if(dataParsed[i]['status'] == "Approved" && dataParsed[i]['hr_attend'] == 'no') {
                                                        span.style.backgroundColor = "#198754";
                                                        span.style.padding = "0.2rem";
                                                        span.style.borderRadius = "0.3rem";
                                                        let i = document.createElement('i');
                                                        i.setAttribute("class", "fas fa-times h5");
                                                        td6.appendChild(i);
                                                        i.style.color = "#D0312D";
                                                        i.style.paddingLeft = "4px";
                                                    }else if(dataParsed[i]['status'] == "Declined") {
                                                        span.style.backgroundColor = "#D0312D";
                                                        span.style.padding = "0.2rem";
                                                        span.style.borderRadius = "0.3rem";
                                                        let i = document.createElement('i');
                                                        i.setAttribute("class", "fas fa-times h5");
                                                        td6.appendChild(i);
                                                        i.style.color = "#D0312D";
                                                        i.style.paddingLeft = "4px";
                                                    }
                                                    tr.appendChild(td6);
                                                    tbody.appendChild(tr);
                                                    // rownumber.innerHTML = i+1;
                                                    // startdate.innerHTML = dataParsed[i]['start_date'];
                                                    // enddate.innerHTML = dataParsed[i]['end_date'];
                                                    // resumptiondate.innerHTML = dataParsed[i]['resumption_date'];
                                                    // noofdays.innerHTML = dataParsed[i]['noofdays'];
                                                    // replacedby.innerHTML = dataParsed[i]['replacedby'];
                                                    // status.innerHTML = dataParsed[i]['status'];
                                                    
                                                }
                                            }else{
                                                tbody.innerHTML = "";
                                                let daystaken = document.querySelector(".indexdaystaken");
                                                let daysleft = document.querySelector(".indexdaysremaining");
                                                
                                                daystaken.innerHTML = dataParsed[0]['daystaken'];
                                                daysleft.innerHTML = dataParsed[0]['daysleft'];
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
                    </h6>
                    <h6>Total Days of Leave: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['totalleave']  ?></span></h6>
                    <h6>Days Taken: <span class="jbe__homepage-name indexdaystaken"><?php echo $employeeYearDetails[0]['daystaken'] ?></span></h6>
                    <h6>Days Remaining: <span class="jbe__homepage-name indexdaysremaining"><?php echo $employeeYearDetails[0]['daysleft'] ?></span></h6>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jbe__container-fluid jbe__table">
    <div class="jbe__container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="4%">S/N</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Resumption Date</th>
                    <th scope="col" width="12%">Number of days</th>
                    <th scope="col">Replaced By</th>
                    <th scope="col">Status</th>
                </tr>
            </thead> 
            <tbody class="tbody" id="tbody">
                <?php
                if(!empty($getleaveapplication)){
                    for($i = 0; $i < count($getleaveapplication); $i++){
                        echo "
                        <tr>
                            <th scope='row' id='rownumber'>". $i+1 ."</th>
                            <td id='startdate'>". $getleaveapplication[$i]['start_date'] ."</td>
                            <td id='enddate'>". $getleaveapplication[$i]['end_date'] ."</td>
                            <td id='resumptiondate'>". $getleaveapplication[$i]['resumption_date'] ."</td>
                            <td id='noofdays'>". $getleaveapplication[$i]['noofdays'] ."</td>
                            <td id='replacedby'>". $getleaveapplication[$i]['replacedby'] ."</td>"; ?>
                            <?php if($getleaveapplication[$i]['status'] == 'Pending'){ 
                                echo "<td><nobr><span id='status' class='pending'>". $getleaveapplication[$i]['status'] ."</span>
                                <i class='fas fa-times h5' style='color:#D0312D;'></i></nobr>
                                </td>";
                            } else if($getleaveapplication[$i]['status'] == 'Approved' && $getleaveapplication[$i]['hr_attend'] == 'no') {
                                echo "<td><nobr><span id='status' class='approved'>". $getleaveapplication[$i]['status'] ."</span>
                                <i class='fas fa-times h5' style='color:#D0312D;'></i></nobr>
                                </td>";
                            } else if($getleaveapplication[$i]['status'] == 'Approved' && $getleaveapplication[$i]['hr_attend'] == 'yes') {
                                echo "<td><nobr><span id='status' class='approved'>". $getleaveapplication[$i]['status'] ."</span>
                                <i class='fas fa-check h5' style='color:#198754;'></i></nobr>
                                </td>";
                            }
                             else if($getleaveapplication[$i]['status'] == 'Declined'){
                                echo "<td><nobr><span id='status' class='declined'>". $getleaveapplication[$i]['status'] ."</span>
                                <i class='fas fa-times h5' style='color:#D0312D;'></i></nobr>
                                </td>";
                            }
                            echo 
                        "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</section>




<section class="jbe__container-fluid jbe__employees-record">
    <div class="jbe__container">
        <div class="jbe__homepage-welcome">
            <div>
                <h5 class="jbe__general-header-h5">Employees Leave Requests</h5>
                <h5>Branch: <span class="jbe__homepage-name"><?php echo $employeeDetails[0]['branch'];  ?></span></h5>
            </div>
            <a href="<?php echo url_for('/hr/grantedleave.php')?>" class="h6 button">Granted Leave Requests</a>
        </div>
    </div>
</section>
<section class="jbe__container-fluid jbe__table">
    <div class="jbe__container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="4%">S/N</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col" width="11%">Number of days</th>
                    <th scope="col">Resumption Date</th>
                    <th scope="col">Replaced By</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(!empty($approvedLeave)){
                    for($i = 0; $i < count($approvedLeave); $i++){
                        echo "
                        <tr>
                            <th scope='row' id='rownumber'>". $i+1 . "</th>
                            <td>". $approvedLeave[$i]['firstname'] . " " . $approvedLeave[$i]['lastname'] . "</td>
                            <td>". $approvedLeave[$i]['start_date'] ."</td>
                            <td>". $approvedLeave[$i]['end_date'] ."</td>
                            <td>". $approvedLeave[$i]['noofdays'] ."</td>
                            <td>". $approvedLeave[$i]['resumption_date'] ."</td>
                            <td>". $approvedLeave[$i]['replacedby'] ."</td>
                            <td><span id='status' class='approved'>". $approvedLeave[$i]['status'] ."</span>
                            <i class='fas fa-times h5' style='color:#D0312D;'></i>
                            </td>
                            <td>
                                <a class='h5' href='employeeleaveform-edit.php?employee_id=".$approvedLeave[$i]['employee_id']."&employee_leave_id=".$approvedLeave[$i]['employee_leave_id']."'><i class='fas fa-edit'></i></a>
                            </td>
                        </tr>";
                    } 
                }
            ?>
            </tbody>
        </table>
    </div>
</section>

<?php
    include(SHARED_PATH . "/footer.php");
?>