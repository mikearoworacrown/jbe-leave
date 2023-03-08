<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");


    $page_title = "Granted Leave Request";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }
    $_SESSION['savedsearchvalue'] = "";
    include(SHARED_PATH . "/header.php");

    $employee = new Employee();
    $status = "Approved";
    $hr_attend  = "yes";

    if(isset($_SESSION['searchvalue'])) {
        $grantedLeave = $employee->getApprovedLeaveApplicationLike($status, $hr_attend, $_SESSION['searchvalue']);
        $_SESSION['savedsearchvalue'] = $_SESSION['searchvalue'];
        unset($_SESSION['searchvalue']);
    }else{
        $grantedLeave = $employee->getApprovedLeaveApplication($status, $hr_attend);
    }

?>
<style>
.searchform {
    display: flex;
    flex-direction: row;
}
.searchform input {
    border: 0.19rem solid var(--primary-color);
}
.searchform button {
    border: 0.19rem solid var(--primary-color);
    margin-left: 3px;
    border-radius: 0.5rem;
    background-color: #fff;
    padding: 0 0.6rem;
    cursor: pointer;
}
.searchform button i {
    font-size: 1rem;
    color: var(--primary-color);
}
</style>

<section class="jbe__container-fluid jbe__employees-record" style="margin-top: 8.5rem">
    <div class="jbe__container">
        <div class="jbe__homepage-welcome">
            <div>
                <h5 class="jbe__general-header-h5">Employees Granted Leave Requests</h5>
                <h5>Branch: <span class="jbe__homepage-name">Victoria Island</span></h5>
            </div>
            <form action="" method="post" class="searchform">
                <input type="text" class="form-control searchvalue" name="searchvalue" placeholder="Search Employee" value="<?php if(isset($_SESSION['savedsearchvalue'])){ echo $_SESSION['savedsearchvalue'];}?>">
                <button type="button" class="searchformbtn"><i class="fas fa-search"></i></button>
            </form>

        </div>
    </div>
</section>
<script>
    const searchForm = document.querySelector('.searchform');
    const searchFormBtn = document.querySelector('.searchformbtn');

    if(searchForm){
        searchForm.onsubmit = (e) => {
            e.preventDefault();
        }
    }

    if(searchFormBtn) {
        searchFormBtn.onclick = () => {
            let searchValue = document.querySelector(".searchvalue");
            
            if(searchValue.value != ""){
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "../ajax_php/searchform.php", true)
                xhr.onload = () => {
                    if(xhr.readyState === XMLHttpRequest.DONE){
                        if(xhr.status === 200){
                            location.href = "grantedleave.php";
                        }
                    }
                }
                let formData = new FormData(searchForm); //creating new formData object
                xhr.send(formData); //sending the form data to php
            }
        }
    }
</script>
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
                    <th scope="col">Days Taken</th>
                    <th scope="col">Days Left</th>
                    <th scope="col">Status</th>
                    <th scope="col">Print</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(!empty($grantedLeave)){
                    for($i = 0; $i < count($grantedLeave); $i++){
                        echo "
                        <tr>
                            <th scope='row' id='rownumber'>". $i+1 . "</th>
                            <td>". $grantedLeave[$i]['firstname'] . " " . $grantedLeave[$i]['lastname'] . "</td>
                            <td>". $grantedLeave[$i]['start_date'] ."</td>
                            <td>". $grantedLeave[$i]['end_date'] ."</td>
                            <td>". $grantedLeave[$i]['noofdays'] ."</td>
                            <td>". $grantedLeave[$i]['resumption_date'] ."</td>
                            <td>". $grantedLeave[$i]['replacedby'] ."</td>
                            <td>". $grantedLeave[$i]['daystaken'] ."</td>
                            <td>". $grantedLeave[$i]['daysleft'] ."</td>
                            <td><nobr><span id='status' class='approved'>". $grantedLeave[$i]['status'] ."</span>
                            <i class='fas fa-check h5' style='color:#198754;'></i></nobr>
                            </td>
                            <td>
                                <div class='printform'>
                                    <input type='hidden' class='grantedId' value=".$grantedLeave[$i]['employee_id'].">
                                    <input type='hidden' class='grantedLeaveId' value=".$grantedLeave[$i]['employee_leave_id'].">
                                    <input type='hidden' class='grantedYear' value=".$grantedLeave[$i]['year'].">
                                    <button class='printformbtn'><i class='fas fa-print h5' style='color:#091281; cursor:pointer;'></i></button>
                                </div>
                            </td>
                        </tr>";
                    } 
                }
            ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    let id = document.querySelectorAll('.grantedId');
    let leave_id = document.querySelectorAll('.grantedLeaveId');
    let leave_year = document.querySelectorAll('.grantedYear');
    
    const printFormSubmit = document.querySelectorAll('.printformbtn');
    

    function printEmployeeForm() {
        //Button clicked
        for(let i = 0; i < printFormSubmit.length; i++)
        {
            printFormSubmit[i].addEventListener('click', function(){
                let granted = {
                    id: id[i].value,
                    leave_id : leave_id[i].value,
                    leave_year: leave_year[i].value
                }
                let xhr = new XMLHttpRequest(); //creating XML object
                xhr.open("POST", "../ajax_php/printform.php", true);
                xhr.onload = () => {
                    if(xhr.readyState === XMLHttpRequest.DONE){
                        if(xhr.status === 200){
                            location.href = "processedform.php";
                        }
                    }
                }
                let granted1 = JSON.stringify(granted);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.send(granted1);
            })
        }
    }

    printEmployeeForm();
</script>

<?php
    include(SHARED_PATH . "/footer.php");
?>
