<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    require_once(PROJECT_PATH . "/class/Employee.php");

    if(!isset($_SESSION['email-phone']) || $_SESSION['employeetype'] != 'hr'){
        header('Location: ../');
        exit();
    }
?>

<?php

    require_once("dompdf/autoload.inc.php");

    use Dompdf\Dompdf;
    //instantiate and use the dompdf class
    $dompdf = new Dompdf();

    //Load HTML Content
    $htmlContent = '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .jbe__container, .jbe__container-fluid {
    
    width: 100%;
    
    margin-right: auto;
    margin-left: auto;
}

.row {
   position: relative;
   
}

.col-1 {
       position: absolute;
       left: 10px;
        width: 45%;
    }
    .col-2 {
       position: absolute;
       right: 35px;
        width: 45%;
    }

    .form-group {
    position: relative;
    margin: 0 auto 0.5rem auto;
}
        .form-control {
    display: block;
    width: 100%;
    padding-bottom: 0.7rem;
    padding-left: 0.7rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    height: 20px;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    appearance: none;
    border-radius: 0.375rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
        .jbe__request-form {
    margin-top: -2rem;
    font-size: 18px;
}

.jbe__request-form-h4{
    display:block;
    width: 300px;
    margin-left: auto;
    margin-right: auto;
    
}
.jbe__request-form-h4 h4{
    padding: 2px 5px;
    border: 4px solid #091281;
    color: #091281;
    text-align: center;
    border-radius: 3rem;
}

.leaveform-row1 {
    border: 4px solid #091281;
    height: 190px;
    border-radius: 2rem;
    padding: 2rem;
    margin: 0 0 1rem 0;
}

.leaveform-row1 label {
    font-weight: 700;
}
.leaveform-row1 input {
    border: 2px solid #091281;
    font-weight: 700;
    color: #091281;
}
.leaveform-row2 label {
    font-weight: 600;
}
.leaveform-row2 input {
    border: 2px solid #091281;
    font-weight: 700;
    color: #091281;
}

.leaveform-row2 {
    height: 550px;
    border: 4px solid #091281;
    border-radius: 2rem;
    padding: 2rem;
    margin: 0 0 1rem 0;
}

.leave-type {
    display: block;
    width: 150px;
    margin-top: -25px;
    margin-left: auto;
    margin-right: auto;
    
}
.leave-type-2, .leave-type-2 {
    width: 200px;
}
.date-start-end{
    top: 250px;
}
.leaveform-row2 h4 {
    display: inline-block;
    text-align: center;
    background-color: #091281;
    color: #fff;
    border: 3px solid #091281;
    border-radius: 3rem;
    padding: 0.5rem 1rem;
}

.signature{
    top: 520px;
}

.form-check {
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5em;
    border: none;
    outline: none;
}

.form-check .form-check-input {
    float: left;
    margin-left: -1.2rem;
    margin-top: -0.2px;
    border: none;
    outline: none;
}
.form-check-label{
    padding-left: 5px;
}


    </style>
</head>
<body>
    <div class="jbe__container jbe__request-form">
        <div class="jbe__request-form-h4">
            <h4>Leave Request Form - Locals</h4>
        </div>
        <form class="leave-request-form">
            <div class="row leaveform-row1">
                <div class="col-1">
                    <div class="form-group">
                        <label for="leave-name">Name: </label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-firstname']. ' ' . $_SESSION['granted-lastname']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-department">Department: </label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-department']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-branch">Branch: </label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-branch']. '" disabled>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="leave-employeeid">Employee ID: </label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-id']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-year">Leave Year:</label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-year']. '" disabled>
                    </div>

                    <div class="form-group">
                        <label for="leave-date">Date: </label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-date']. '" disabled>
                    </div>
                </div>
            </div>

            <div class="row leaveform-row2">
                <div class="leave-type">
                    <h4 class="mb-5">Leave Type</h4>
                </div>
                
                <div class="col-1 leave-type-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Annual Leave" checked>
                        <label class="form-check-label" for="leave-type1">
                            Annual Leave
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Casual Leave">
                        <label class="form-check-label" for="leave-type2">
                            Casual Leave
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Sick Leave">
                        <label class="form-check-label" for="leave-type3">
                            Sick Leave
                        </label>
                    </div>
                </div>

                <div class="col-2 leave-type-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Leave Without Pay">
                        <label class="form-check-label" for="leave-type4">
                            Leave Without Pay
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input mt-3" type="radio" value="Other: ">
                        <label class="form-check-label" for="leave-type5">
                            Other <input type="text" class="form-control mt-1 ms-1" placeholder="Identify Reason">
                        </label>
                    </div>
                </div>

                <div style="position: absolute; left: 5px; top: 210px; height: 5px; width: 98%; border-bottom: 1px solid #091281;"></div>
                
                <div class="col-1 date-start-end">
                    <div class="form-group">
                        <label for="leave-commencing">Commencing Date:</label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-start-date']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-resumption">Resumption Date:</label>
                        <input type="text" class="form-control leave-resumption"  id="datepicker3" value="'.$_SESSION['granted-resumption-date']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-ending">Ending Date:</span></label>
                        <input type="text" class="form-control leave-ending" id="datepicker2" value="'.$_SESSION['granted-end-date']. '" disabled>
                    </div>
                </div>  

                <div class="col-2 date-start-end">
                    <div class="form-group">
                        <label for="leave-replace">To be Replaced By:</label>
                        <input type="text" class="form-control" value="'.$_SESSION['granted-replacedby']. '" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-status">Status:</label>
                        <input type="text" class="form-control" value="Approved" disabled>
                    </div>
                    <div class="form-group">
                        <label for="leave-noofdays">Number of days:</label>
                        <input type="text" class="form-control"value="'.$_SESSION['granted-noofdays']. '"  disabled>
                    </div>
                </div>

                <div style="position: absolute; left: 5px; top: 465px; height: 5px; width: 98%;">
                    <div class="leave-type">
                        <h4 class="mb-5">HR Section</h4>
                    </div>
                </div>

                <div class="col-1 signature">
                    <div class="form-group" style="position: relative; margin-left: 10px;">
                        <label for="sign">Name: </label>
                        <div style="position:absolute; height: 5px; top: 60px; left: 0; width: 100%; border-bottom: 2px solid #091281;">
                    
                        </div>
                    </div>
                </div>

                <div class="col-2 signature">
                    <div class="form-group" style="position: relative; margin-right: 10px;">
                        <label for="sign">Signature: </label>
                        <div style="position:absolute; height: 5px; top: 60px; right: 0; width: 100%; border-bottom: 2px solid #091281;">
                    
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</body>
</html>';
    $dompdf->loadHtml($htmlContent);

    //(Optional) Setup the paper size and orientation
    // $dompdf->setPaper('A4', 'landscape');

    //render the html as PDF
    $dompdf->render();

    //Output the generated PDF to browser
    $dompdf->stream('document', array('Attachment' => 0));

?>



<?php
    include(SHARED_PATH . "/footer.php");
?>

