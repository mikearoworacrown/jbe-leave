<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Staff Record";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }
    $_SESSION['savedemployeesearchvalue'] = "";

    include(SHARED_PATH . "/header.php");

    $employee = new Employee();

    if(isset($_SESSION['employeesearchvalue'])){
        $employeeRecord = $employee->getEmployeeByName($_SESSION['employeesearchvalue']);
        $_SESSION['savedemployeesearchvalue'] = $_SESSION['employeesearchvalue'];
        unset($_SESSION['employeesearchvalue']);
    }else{
        // $employeeDetails = $employee->getEmployee($_SESSION['email-phone']);
        $totalNumberOfEmployee = $employee->getTotalNumberOfEmployee();
        $num_per_page = 10;
        $page_total = ceil($totalNumberOfEmployee / $num_per_page);

        if(isset($_SESSION['range1']) && isset($_SESSION['range2'])){
            $range1 = $_SESSION['range1'];
            if($totalNumberOfEmployee < $_SESSION['range2']){
                $range2 = $totalNumberOfEmployee;
            }else{
                $range2 = $_SESSION['range2'];
            }
        }else{
            $range1 = 0;
            if($totalNumberOfEmployee < 10){
                $range2 = $totalNumberOfEmployee;
            }else{
                $range2 = 10;
            }
        }

        if(isset($_SESSION['page'])){
            $pageActive = $_SESSION['page'];
        }else{
            $pageActive = '1';
        }
        

        $allEmployee = $employee->getAllEmployee();
        $today = date("d-m-Y");
    }

?>
<input type="hidden" class="inputpage" value="<?php echo $pageActive; ?>">

<style>
    .jbe__employees-record{
        margin-top: 8rem;
    }
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

<section class="jbe__container-fluid jbe__employees-record">
    <div class="jbe__container">
        <?php if(isset($_SESSION['update-message'])){
            echo "
                <div id='toastr-message'>
                    <h6>". $_SESSION['update-message'] ."</h6>
                </div>
            ";
        }
        unset($_SESSION['update-message']);
        ?>
    </div>
    <div class="jbe__container">
        <div class="jbe__homepage-welcome">
            <div>
                <h5 class="jbe__general-header-h5">Employees Record - Registered Employee</h5>
                <h5>Branch: <span class="jbe__homepage-name">Victoria Island</span></h5>
                <form action="" method="post" class="searchform">
                    <input type="text" class="form-control searchvalue" name="searchvalue" value="<?php if(isset($_SESSION['savedemployeesearchvalue'])){ echo $_SESSION['savedemployeesearchvalue'];}?>" placeholder="Search Employee">
                    <button type="button" class="searchformbtn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <a href="<?php echo url_for('/hr/register.php')?>" class="h6 button">Register New Employee</a>
        </div>
    </div>
</section>
<style>
    .pages{
        top: 15px;
        text-align: center;
    }
    .pages a{
        font-size: 1.2rem;
        border: 2px solid var(--primary-color);
        padding: 2px 4px;
        margin-left: 3px;
    }
    .pagbtn.active{
        background-color: var(--primary-color);
        color: #fff;
        border: 2px solid var(--primary-color);
    }
    .activatedeactivate {
        cursor: pointer;
    }
    
</style>
<section class="jbe__container-fluid jbe__table">
    <div class="jbe__container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="4%">S/N</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Email Address/Phone Number</th>
                    <th scope="col">Department</th>
                    <th scope="col">Job Description</th>
                    <th scope="col">Total Leave</th>
                    <th scope="col">Line Manager</th>
                    <th scope="col">Status</th>
                    <th scope="col" width="5%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($employeeRecord)){
                        for($i = 0; $i < count($employeeRecord); $i++){
                            echo "<tr><th scope='row'>" . $i+1 . "</th>
                            <td>" . $employeeRecord[$i]['firstname'] . ' ' . $employeeRecord[$i]['lastname']  . "</td>
                            <td>" . $employeeRecord[$i]['email_phone']  . "</td>
                            <td>" . $employeeRecord[$i]['department'] ."</td>
                            <td>" . $employeeRecord[$i]['job_description'] . "</td>
                            <td>" . $employeeRecord[$i]['totalleave'] . "</td>
                            <td>" . $employeeRecord[$i]['linemanagername'] . "</td>";
                            if($employeeRecord[$i]['status'] === 'active'){
                                echo "<td><span style='background-color:#198754; padding: 0.3rem; border-radius: 0.4rem;'>". $employeeRecord[$i]['status'] ."<span></td>";
                            }else{
                                echo "<td><span style='background-color:#D0312D; padding: 0.3rem; border-radius: 0.4rem;'>". $employeeRecord[$i]['status'] ."<span></td>";
                            }
                            echo "<td>
                                <a href='employee-edit.php?employee_id=". $employeeRecord[$i]['employee_id'] ."' class='h5'><i class='fas fa-edit'></i></a>";
                          echo "</td></tr>";
                        }
                    }else{
                        for($i = $range1; $i < $range2; $i++){
                            echo "<tr><th scope='row'>" . $i+1 . "</th>
                            <td>" . $allEmployee[$i]['firstname'] . ' ' . $allEmployee[$i]['lastname']  . "</td>
                            <td>" . $allEmployee[$i]['email_phone']  . "</td>
                            <td>" . $allEmployee[$i]['department'] ."</td>
                            <td>" . $allEmployee[$i]['job_description'] . "</td>
                            <td>" . $allEmployee[$i]['totalleave'] . "</td>
                            <td>" . $allEmployee[$i]['linemanagername'] . "</td>";
                            if($allEmployee[$i]['status'] === 'active'){
                                echo "<td><span style='background-color:#198754; padding: 0.3rem; border-radius: 0.4rem;'>". $allEmployee[$i]['status'] ."<span></td>";
                            }else{
                                echo "<td><span style='background-color:#D0312D; padding: 0.3rem; border-radius: 0.4rem;'>". $allEmployee[$i]['status'] ."<span></td>";
                            }
                            echo "<td>
                                <a href='employee-edit.php?employee_id=". $allEmployee[$i]['employee_id'] ."' class='h5'><i class='fas fa-edit'></i></a>";
                          echo "</td></tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
        <div class="pages" style="position: relative;">
        <?php
        // if($totalNumberOfEmployee > 15){
            for($i = 1; $i <= $page_total; $i++) {
                echo "<a class='pagbtn page".$i."'>$i</a>";
            }
        // }
        ?>
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
                xhr.open("POST", "../ajax_php/searchemployee.php", true)
                xhr.onload = () => {
                    if(xhr.readyState === XMLHttpRequest.DONE){
                        if(xhr.status === 200){
                            let data = xhr.response;
                            let dataParsed = JSON.parse(data);
                            if(dataParsed !== ""){
                                location.href = "employees.php";
                            }
                        }
                    }
                }
                let formData = new FormData(searchForm); //creating new formData object
                xhr.send(formData); //sending the form data to php
            }
        }
    }
</script>

<script>
    const pagNavigation = document.querySelectorAll('.pagbtn');

    function pagination() {
        //Button clicked
        for(let i = 0; i < pagNavigation.length; i++)
        {
            pagNavigation[i].addEventListener('click', function(){
                let pageNum = {
                    page: pagNavigation[i].textContent,
                }
                let xhr = new XMLHttpRequest(); //creating XML object
                xhr.open("POST", "../ajax_php/pagination.php", true);
                xhr.onload = () => {
                    if(xhr.readyState === XMLHttpRequest.DONE){
                        if(xhr.status === 200){
                            let data = xhr.response;
                            let dataParsed = JSON.parse(data);
                            if(dataParsed['page'] !== ""){
                                location.href = "employees.php";
                            }
                        }
                    }
                }
                let pageNum1 = JSON.stringify(pageNum);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.send(pageNum1);
            })
        }
    }

    pagination();
</script>

<script>
    const pagenumber = document.querySelector('.inputpage');
    const pagNav = document.querySelectorAll('.pagbtn');
    for(let i = 0; i < pagNav.length; i++){
        if(pagNav[i].innerHTML === pagenumber.value){
            pagNav[i].classList.add('active');
        }
    }
    
</script>
<?php
    unset($_SESSION['range1']);
    unset($_SESSION['range2']);
    unset($_SESSION['page']);
    unset($_SESSION['employeesearchvalue']);
    include(SHARED_PATH . "/footer.php");
?>