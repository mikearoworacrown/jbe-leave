<!DOCTYPE html>
<html lang="en">
<head>
    <!--META CHARSET-->
    <meta charset="UTF-8">
    <!--META EDGE-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--META VIEWPORT-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--TITLE-->
    <title>Jubailibros Leave Form <?php if(isset($page_title)){ echo ' - ' . h($page_title); }?> </title>
    <!--FAVICON-->
    <link rel="icon" type="image/x-icon" href="<?php echo url_for('img/favicon.png?v=') . time();?>">
    <!--META DESCRIPTION-->
    <meta name="Description" content="">
    <!--META KEYWORDS-->
    <meta name="keywords" content="">
    <!--META AUTHOR-->
    <meta name="author" content="Michael Aroworade">
    <!--Style Links-->
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  
    <link rel="stylesheet" href="<?php echo url_for('css/utilities.css?v=') . time();?>">
    <link rel="stylesheet" href="<?php echo url_for('css/styles.css?v=') . time(); ?>">
    <link rel="stylesheet" href="<?php echo url_for('css/jquery-ui.css?v=') . time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>
<body>
    
    <!--/////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////NAVBAR SECTION//////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////-->
    <nav class="jbe__navbar jbe__nabar-expand-md">
        <div class="jbe__container">
            <a href="<?php echo url_for('index.php?v=') . time();?>" class="jbe__navbar-brand">
                <img src="<?php echo url_for('img/jbe.png?v=') . time();?>" alt="Leave Form">
            </a>

            <div class="jbe__title">Leave Form</div>

            <button class="jbe__navbar-toggler" onclick="collapse();">
                <span class="jbe__navbar-toggler-icon">
                    <i class="fas fa-bars"></i>
                </span>
            </button>
            <div class="jbe__navbar-collapse" id="jbe__navbar-collapse">
                <ul class="jbe__navbar-nav">
                    <?php
                    if(isset($_SESSION['firstname'])){
                        echo "<li class='jbe__nav-item profile-name'><a href=". url_for("index.php") ." class='jbe__nav-link'>" . $_SESSION['firstname'] . "</a></li>";
                        echo "<li class='jbe__nav-item'><a href=" . url_for("/logout.php") . " class='jbe__nav-link'>Logout</a></li>";
                    }
                    else {
                        echo "<li class='jbe__nav-item'><a href='' class='jbe__nav-link'>Profile</a></li>";
                    }
                    if((isset($_SESSION['employeetype']) && $_SESSION['employeetype'] == "hr")){
                        echo "<li class='jbe__nav-item'><a href=". url_for("/hr/employees.php") . " class='jbe__nav-link'>Staff Record</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>