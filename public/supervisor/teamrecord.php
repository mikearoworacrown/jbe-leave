<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    $page_title = "Leave Form";

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'supervisor'){
        header('Location: ../');
        exit();
    }
    
    $employeeRecord = new Employee();

    $year = date('Y');
    $employee_id = $_SESSION['employee-id'];
    $employeetype = "user";


    $teamLeaveRecord = $employeeRecord->getTeamLeaveRecord($_SESSION['department'], $employeetype, $year);

    
    include(SHARED_PATH . "/header.php");
?>


<section class="jbe__container-fluid jbe__homepage">
    <div class="jbe__container">
        <div class="jbe__homepage-welcome">
            <h4><span class="jbe__homepage-name"><?php echo $_SESSION['department']; ?> Department</span></h4>
        </div>
        <h5 class="jbe__general-header-h5">Team Leave Record</h5>
        <div class="jbe__homepage-leave-info">
            <div class="row">
                <div class="col-md-6">
                    <h6>Total Number of Team: <span class="jbe__homepage-name"><?php echo count($teamLeaveRecord); ?></span></h6>
                </div>
                <div class="col-md-6">
                    <h6>Year: <span style="color: #091281;"><?php echo $year; ?></span></h6>
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
                    <th scope="col">Employee Name</th>
                    <th scope="col">Email Address/Phone Number</th>
                    <th scope="col">Job Description</th>
                    <th scope="col">Branch</th>
                    <th scope="col">Total Leave</th>
                    <th scope="col">Days Taken</th>
                    <th scope="col">Days Remaining</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(!empty($teamLeaveRecord)){
                    for($i = 0; $i < count($teamLeaveRecord); $i++){
                        echo "
                        <tr>
                            <th scope='row' id='rownumber'>". $i+1 ."</th>
                            <td>". $teamLeaveRecord[$i]['firstname'] . " " . $teamLeaveRecord[$i]['lastname'] . "</td>
                            <td>" . $teamLeaveRecord[$i]['email_phone']  . "</td>
                            <td>" . $teamLeaveRecord[$i]['job_description'] . "</td>
                            <td>" . $teamLeaveRecord[$i]['branch'] . "</td>
                            <td>" . $teamLeaveRecord[$i]['totalleave'] . "</td>
                            <td>" . $teamLeaveRecord[$i]['daystaken'] . "</td>
                            <td>" . $teamLeaveRecord[$i]['daysleft'] . "</td>";
                        "</tr>";
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