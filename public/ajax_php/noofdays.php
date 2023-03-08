<?php
    require_once("C:/xampp/htdocs/jbe-leave/private/initialize.php");
    $requestPayload = file_get_contents("php://input");

    $data = json_decode($requestPayload, true);

    if(!empty($data["startDateInputValue"])){
        $_SESSION['startDate'] = $data["startDateInputValue"];
    }
    if(!empty($data["resumptionDateInputValue"])){
        $_SESSION['resumptionDate'] = $data["resumptionDateInputValue"];
    }

    $firstDate = date('d-m-Y', strtotime($_SESSION['startDate']));
    $secondDate = date('d-m-Y', strtotime($_SESSION['resumptionDate']));
    $today = date('d-m-Y');

    if ($firstDate >= $secondDate) {
        $result = array(
            "message"  => "Check Dates. Incorrect Date Range."
        );

        echo json_encode($result);
    }else {
        if(isset($_SESSION['startDate']) && isset($_SESSION['resumptionDate'])) {
            // echo $_SESSION['startDate'] . " " . $_SESSION['resumptionDate'];
    
            $start = new DateTime($_SESSION['startDate']);
            $resumption = new DateTime($_SESSION['resumptionDate']);
            // otherwise the  end date is excluded (bug?)
            // $resumption->modify('+1 day');
        
            $interval = $resumption->diff($start);
        
            // total days
            $days = $interval->days;
        
            // create an iterateable period of date (P1D equates to 1 day)
            $period = new DatePeriod($start, new DateInterval('P1D'), $resumption);
        
            // best stored as array, so you can add more than one
            $holidays = array('2012-09-07');
        
            foreach($period as $dt) {
                $curr = $dt->format('D');
        
                // substract if Saturday or Sunday
                if ($curr == 'Sat' || $curr == 'Sun') {
                    $days--;
                }
        
                // (optional) for the updated question
                elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            }
        
            $res_day = $resumption->format('D');
            if($res_day == 'Mon'){
                $resumption->modify('-3 day');
                $end = $resumption->format('d-m-Y');
            }else {
                $resumption->modify('-1 day');
                $end = $resumption->format('d-m-Y');
            }
            $_SESSION['endDate'] = $end;
            $_SESSION['noofdays'] = $days;
        
            $result = array(
                "noofdays"  => $_SESSION['noofdays'],
                "enddate" => $_SESSION['endDate']
            );
    
            if(!empty($result)) {
                echo json_encode($result);
            }
        }
    }

?>