<?php
/*
  Template Name: dashboard
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

if (!is_user_logged_in()) {
    auth_redirect();
}

$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
initconfig();

createConnection();
//$getatten = getRating('175');
?>



<?php get_header(); ?>

<?php
$userRegistered = TRUE;
$contractsRegistered = TRUE;


if (isset($_REQUEST['contractid'])) {
    $contractid = $_REQUEST['contractid'];
}


$thisuser = wp_get_current_user();
dwerror_log(__FUNCTION__ . ":Curr wp user=" . $thisuser->user_email);

//Find Employer with logged in user's email address
//Find all contracts for the employer
//construct a dropdown with names of DW's for this employer
// Find employer record from wp user email
$sql = "SELECT * FROM `dwemps` WHERE `email` = '" . $thisuser->user_email . "'";
$result = dwqueryexec(__FUNCTION__, $sql);

if ($result->num_rows > 0) {
    // Find the employer who is calling
    $thisemployer = $result->fetch_assoc();
    $allcontracts = getempidContracts($thisemployer['dwempid']);

//for allcontracts card
    $contractscount = count($allcontracts);
    // $dwid=$allcontracts[0]['dwid'];
    if (count($allcontracts) > 0) {
        $contractsRegistered = TRUE;



        if (isset($_REQUEST['dwupdate'])) {

            $contractid = $_REQUEST['contractid'];
        } else {
            if (!isset($_REQUEST['contractid'])) {
                //The first one is default
                $contractid = $allcontracts[0]['contractid'];
            }
        }
    } else {
        $contractsRegistered = FALSE;
        $contractid = NULL;
    }
} else {
    // Employer not registered in dwpivr module            
    $userRegistered = FALSE;
}
dwerror_log(__FUNCTION__ . ":AllContracts=" . print_r($allcontracts, TRUE));
$today = date('Y-m-d');
dwerror_log(__FUNCTION__ . ":Contractid=" . $contractid);

if ($userRegistered && $contractsRegistered) {
    $alltasks = getallTasks($contractid);
} else {
    $alltasks = array();
}
$countalltask = count($alltasks);
dwerror_log(__FUNCTION__ . "Alltasks=" . print_r($alltasks, TRUE));
$dwempid = $thisemployer['dwempid'];
$currentemp = array();
$sql = "SELECT * FROM `dwemps` WHERE `dwempid` ='" . $dwempid . "'";
$result = dwqueryexec(__FUNCTION__, $sql);
$currentemp = mysqli_fetch_all($result, MYSQLI_ASSOC);
$currentstatus = array();
$sql = "SELECT * FROM `contracts` WHERE `contractid` ='" . $contractid . "'";
$result = dwqueryexec(__FUNCTION__, $sql);
$currentstatus = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r($currentstatus);

if (isset($_REQUEST['Save'])) {
    $sql = 'UPDATE `contracts` SET ';
    $dwempnotes = $_REQUEST["v_dwempnotes"];
    $sql .= " `dwempnotes` = '" . $dwempnotes . "' ";

    $sql .= " WHERE `contractid`='" . $contractid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
}

$rate = $rating[0]['feedback'];
if (isset($_REQUEST['rating'])) {

    $contractid = $_REQUEST['contractid'];
    $dwid = $_REQUEST['v_dwid'];
    $dwempid = $_REQUEST['v_dwempid'];
    $ratingNum = $_REQUEST['rating-star'];

    $ratingPoints = $_POST['ratingPoints'];
    $contractid = $_REQUEST['contractid'];
    $dwid = $_REQUEST['v_dwid'];
    $dwempid = $_REQUEST['v_dwempid'];
    $ratingNum = $_REQUEST['rating-star'];



    $date = date('Y-m-d ');
    $query = "UPDATE  `unitjobs` SET  `feedback` ='" . $ratingNum . "' WHERE  `contractid` ='" . $contractid . "' AND  `jobdate` = '" . $date . "'";
    //echo $query;
    $update = dwqueryexec(__FUNCTION__, $query);

   
}

$sqlresul = "SELECT * FROM `unitjobs` WHERE `contractid` = '" . $contractid . "'";
$previresult = dwqueryexec(__FUNCTION__, $sqlresul);
$rating = mysqli_fetch_all($previresult, MYSQLI_ASSOC);

$substartdt= $currentstatus[0]['substartdate'];
$subenddt= $currentstatus[0]['subenddate'];

$date=date('Y-m-d');
//echo $date;
if($date==$subenddt)
{
    $sql="UPDATE `contracts` SET `substatus`='NULL',`substartdate`='NULL',`subenddate`='NULL' WHERE `contractid`='".$contractid."'";
   
    $result = dwqueryexec(__FUNCTION__, $sql);
}

?>


<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('dwcss', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all');?>"/>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->
        <style type="text/css">
            /* Template Name : Dwcss*/



            .form-section {
                padding-left: 15px;

                display: none;
            }
            .form-section.current {
                display: inherit;
            }
            /* .btn-info, .btn-default {
                 margin-top: 10px;
             }*/
            input.parsley-success,
            select.parsley-success,
            textarea.parsley-success {
                color: #468847;
                background-color: #DFF0D8;
                border: 1px solid #D6E9C6;
            }

            input.parsley-error,
            select.parsley-error,
            textarea.parsley-error {
                color: black;
                /*      background-color: #F2DEDE;*/
                border: 1px solid #EED3D7;
            }

            .parsley-errors-list {
                margin: 2px 0 3px;
                padding: 0;
                list-style-type: none;
                font-size: 0.9em;
                line-height: 0.9em;
                opacity: 0;

                transition: all .3s ease-in;
                -o-transition: all .3s ease-in;
                -moz-transition: all .3s ease-in;
                -webkit-transition: all .3s ease-in;
            }

            .parsley-errors-list.filled {
                opacity: 1;
            }   


            .container {
                display: flex;
                flex-flow: row nowrap; /* Align on the same line */
                justify-content: space-between; /* Equal margin between the child elements */
            }
            .container {right: 0; text-align: center;}

            .container .left, .container .center, .container .right { display: inline-block; }

            .container .left { float: left; }
            .container .center { margin: 0 auto; }
            .container .right { float: right; }
            .clear { clear: both; }
            .form-section {
                padding-left: 15px;
                display: none;
            }
            .form-section.current {
                display: inherit;
            }
            /*.btn-info, .btn-default {
                margin-top: 10px;
            }*/
            input.parsley-success,
            select.parsley-success,
            textarea.parsley-success {
                color: #468847;
                background-color: #DFF0D8;
                border: 1px solid #D6E9C6;
            }

            input,
            select,
            textarea,
            .form-control {
                color: black;
                /* background-color: #F2DEDE;*/
                border: 1px solid #EED3D7;
            }

            .parsley-errors-list {
                margin: 2px 0 3px;
                padding: 0;
                list-style-type: none;
                font-size: 0.9em;
                line-height: 0.9em;
                opacity: 0;

                transition: all .3s ease-in;
                -o-transition: all .3s ease-in;
                -moz-transition: all .3s ease-in;
                -webkit-transition: all .3s ease-in;
            }

            .parsley-errors-list.filled {
                opacity: 1;
            }
            td {
                text-align: center;
            }
            .text-xs-center{
                text_align: center;
            }
            .g-recaptcha{

                display:inline-block;

            }
            .g-recaptcha div { margin-left: auto; margin-right: auto;}

            /***** Top content *****/

            .inner-bg {
                padding: 40px 0 170px 0;
            }

            .top-content .text {
                color: #fff;
            }

            .top-content .text h1 { color: #fff; }

            .top-content .description {
                margin: 20px 0 10px 0;
            }

            .top-content .description p { opacity: 0.8; }

            .top-content .description a {
                color: #fff;
            }
            .top-content .description a:hover, 
            .top-content .description a:focus { border-bottom: 1px dotted #fff; }

            .top-content .top-big-link {
                margin-top: 35px;
            }

            .form-box {
                padding-top: 40px;
            }

            .form-top {
                overflow: hidden;
                padding: 0 25px 15px 25px;
                background:#EAF2F8      ;
                -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; border-radius: 4px 4px 0 0;
                text-align: left;
            }

            .form-top-left {
                float: left;
                width: 75%;
                padding-top: 25px;
            }

            .form-top-left h3 { margin-top: 0; }

            .form-top-right {
                float: left;
                width: 25%;
                padding-top: 5px;
                font-size: 66px;
                color: #ddd;
                line-height: 100px;
                text-align: right;
            }

            .form-bottom {
                padding: 25px 25px 30px 25px;
                background:#EAFAF1;
                -moz-border-radius: 0 0 4px 4px; -webkit-border-radius: 0 0 4px 4px; border-radius: 0 0 4px 4px;
                text-align: left;
            }

            form .form-bottom textarea {
                height: 100px;
            }

            form .form-bottom button.btn {
                min-width: 105px;
            }

            form .form-bottom .input-error {
                border-color: #19b9e7;
            }

            form.registration-form fieldset {
                display: none;
            }


            /***** Media queries *****/



            @media (max-width: 415px) {

                h1, h2 { font-size: 32px; }

            }


            /* Retina-ize images/icons */

            @media
            only screen and (-webkit-min-device-pixel-ratio: 2),
            only screen and (   min--moz-device-pixel-ratio: 2),
            only screen and (     -o-min-device-pixel-ratio: 2/1),
            only screen and (        min-device-pixel-ratio: 2),
            only screen and (                min-resolution: 192dpi),
            only screen and (                min-resolution: 2dppx) {



            }

            .form-section {

                display: none;
            }
            .form-section.current {
                display: inherit;
            }
            /*.btn-info, .btn-default {
                margin-top: 10px;
            }*/
            input.parsley-success,
            select.parsley-success,
            textarea.parsley-success {
                color: #468847;
                background-color: #DFF0D8;
                border: 1px solid #D6E9C6;
            }

            input.parsley-error,
            select.parsley-error,
            textarea.parsley-error {
                color: black !important;
                background-color: #F2DEDE;
                border: 1px solid #EED3D7;
            }

            .parsley-errors-list {
                margin: 2px 0 3px;
                padding: 0;
                list-style-type: none;
                font-size: 0.9em;
                line-height: 0.9em;
                opacity: 0;

                transition: all .3s ease-in;
                -o-transition: all .3s ease-in;
                -moz-transition: all .3s ease-in;
                -webkit-transition: all .3s ease-in;
            }

            .parsley-errors-list.filled {
                opacity: 1;
            }


            /* css of dashboard*/
            #outer
            {
                width:100%;
                text-align: center;
            }
            .inner
            {
                display: inline-block;
                background-color: #3BACA9!important;
            }

            body {
                margin-top:45px;
            }
            /*--------------------------------------------------------------------------
              Navbar Style
            --------------------------------------------------------------------------*/
            .navbar {
                border-bottom-left-radius:0; /*Change bootstrap default border-radius for menu*/
            }
            @media(min-width:768px) {
                .navbar .navbar-brand {
                    width:224px; /*Change the width of .navbar-brand to center text and align with .sider-navbar border-right*/
                    text-align: center;
                }
            }

            #page-keeper {
                padding-left: 0px !important;
            }
            /*--------------------------------------------------------------------------
              Side Navigation
            --------------------------------------------------------------------------*/
            @media(min-width:768px) {
                .sider-navbar {

                    border-right:1px solid #E7E7E7; /*Change for right side border color*/
                    border-radius: 0;
                    overflow-y: auto;
                    background: #B2EBF2; /*Change for background color*/
                    bottom: 0;
                    overflow-x: hidden;
                    padding-bottom: 40px;
                }

                .sider-navbar li.sider-menu {

                }
                .sider-navbar li.sider-menu ul {
                    width: 225px;
                }
                .sider-navbar li.sider-menu > ul > li,
                .sider-navbar li.sider-menu ul li ul li{
                    border-top:1px solid #E7E7E7;/*Change for .sider-menu border color*/
                    -webkit-transition: all 0.3s ease-out;
                    transition: all 0.3s ease-out;
                }
                .sider-navbar li.sider-menu > ul > li:last-child {
                    border-bottom:1px solid #E7E7E7;/*Change for .sider-menu border color*/
                }
                .sider-navbar li.sider-menu > ul > li.active,
                .sider-navbar li.sider-menu > ul > li:hover,
                .sider-navbar li.sider-menu ul li ul li:hover{
                    border-right:6px solid #dddddd;
                }
                .sider-navbar li.sider-menu ul li ul li:hover{
                    background:#FFFFFF;/*Change for .sider-menu border color*/
                }
                .sider-navbar li.sider-menu ul li a {
                    outline: none;
                    padding:15px;
                }

            }
            .sider-navbar li.sider-menu ul {
                list-style:none;
                padding: 0;
            }

            .sider-navbar li.sider-menu ul li a {
                display: block;
                padding:15px;
                text-decoration: none;
                color:0.125.166.0.29;/*Change for .sider-menu color*/
            }
            .sider-navbar li.sider-menu ul li ul {
                background-color:#f3f3f3;/*Change for .sider-menu background-color*/
            }
            /*--------------------------------------------------------------------------
              Profile container
              Thanks to keenthemes' bootstrap snippet for a starting point for the styles
              http://bootsnipp.com/snippets/featured/user-profile-sidebar 
            --------------------------------------------------------------------------*/
            #profiledw {

            }
            .profile-dwpic img {
                float: none;
                margin: 50px auto 0;
                padding:6px;
                width: 15%;
                height: 70px;
                -webkit-border-radius: 50% !important;
                -moz-border-radius: 50% !important;
                border-radius: 50% !important;
                border-left: 4px solid #333333;
                border-right: 4px solid #333333;
            }

            .profile-dwtitle {
                text-align: center;
                margin-top: 16px;
                color: #333333;
            }

            .profile-dwtitle-name {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 7px;
            }

            .profile-dwtitle-title {
                text-transform: uppercase;
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 15px;
            }
            /*Hide profile picture on smaller devices*/
            @media(max-width:768px) {
                #profile {
                    display:none;
                }
            }
            /*--------------------------------------------------------------------------
              Page Keeper
              Setup layout so the main content will be to the right of .sider-navbar
            --------------------------------------------------------------------------*/
            @media(min-width:768px) {
                #page-keeper {
                    width: 100%;
                    padding: 10px 10px 10px 225px;
                }
            }



            #profile {

            }
            .profile-userpic img {
                float: none;
                margin: 50px auto 0;
                padding:6px;
                width: 70%;
                height: 150px;
                -webkit-border-radius: 50% !important;
                -moz-border-radius: 50% !important;
                border-radius: 50% !important;
                border-left: 4px solid #333333;
                border-right: 4px solid #333333;
            }

            .profile-usertitle {
                text-align: center;
                margin-top: 16px;
                color: #333333;
            }

            .profile-usertitle-name {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 7px;
            }

            .profile-usertitle-title {
                text-transform: uppercase;
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 15px;
            }
            /*Hide profile picture on smaller devices*/
            @media(max-width:768px) {
                #profile {
                    display:none;
                }
                .xsmt10 {margin-top: 10px;}
            }
            /*--------------------------------------------------------------------------
              Page Keeper
              Setup layout so the main content will be to the right of .sider-navbar
            --------------------------------------------------------------------------*/
            @media(min-width:768px) {
                #page-keeper {
                    width: 100%;
                    padding: 10px 10px 10px 225px;
                }

            }
            .card {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                max-width: 300px;
                margin: auto;
                text-align: center;
                color: black;
            }
            .star-rating .fa-star{color: orange;}
            .rating-block {
                unicode-bidi: bidi-override;
                direction: rtl;
            }
            .rating-block > span {
                display: inline-block;
                position: relative;
                width: 1.1em;
            }
            .rating-block > span:hover:before,
            .rating-block > span:hover ~ span:before {
                content: "\2605";
                position: relative;
            }
            .card-wrapper {
                position: relative;
                margin-top: 25px;
                -webkit-transition: all .4s ease;
                -moz-transition: all .4s ease;
                -ms-transition: all .4s ease;
                -o-transition: all .4s ease;
                transition: all .4s ease;
            }

            .card {
                position: relative;
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -webkit-flex-direction: column;
                -ms-flex-direction: column;
                flex-direction: column;
                background-color: #fff;
                border: 1px solid rgba(0,0,0,.125);
                border-radius: .25rem;
            }
            .card {
                border: 0;
                -webkit-flex-direction: initial;
                -ms-flex-direction: initial;
                flex-direction: initial;
                justify-content: center;
                align-items: center;
                padding: 15px 10px;
                background-color: #fcfcfc;
                -webkit-animation: showSlowlyElement .8s;
                animation: showSlowlyElement .8s;
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-border-radius: .3em;
                -moz-border-radius: .3em;
                -ms-border-radius: .3em;
                -o-border-radius: .3em;
                border-radius: .3em;
                -webkit-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -moz-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -ms-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -o-box-shadow: 0 0 15px rgba(0,0,0,.04);
                box-shadow: 0 0 15px rgba(0,0,0,.04);
            }
            .card i {
                font-size: 36px;
                min-width: 36px;
                text-align: center;
                margin-right: 15px;
            }
            card-title {
                margin-bottom: .75rem;
                font-size: 36px;
            }
            .card-subtitle {
                margin-top: -.375rem;
                margin-bottom: 0;
            }
            .content-header-title {
                top: 1px;
                left: 40px;
                position: absolute;
            }
            .content-header {
                font-size: 18px;
                padding-bottom: 20px;
                margin-top: 30px;
                position: relative;
                border-bottom: 1px solid #E5E5E5;
            }
            element.style {
                overflow: hidden;
                overflow-x: hidden;
                overflow-y: hidden;
                height: 454px;
            }
            .content-box {
                padding: 15px;
                margin-top: 25px;
                background-color: #fcfcfc;
                -webkit-animation: showSlowlyElement .8s;
                animation: showSlowlyElement .8s;
                -webkit-border-radius: .3em;
                -moz-border-radius: .3em;
                -ms-border-radius: .3em;
                -o-border-radius: .3em;
                border-radius: .3em;
                -webkit-transition: all .4s ease;
                -moz-transition: all .4s ease;
                -ms-transition: all .4s ease;
                -o-transition: all .4s ease;
                transition: all .4s ease;
                -webkit-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -moz-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -ms-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -o-box-shadow: 0 0 15px rgba(0,0,0,.04);
                box-shadow: 0 0 15px rgba(0,0,0,.04);
            }
            .col-md-6 {
                -webkit-box-flex: 0;
                -webkit-flex: 0 0 33.333333%;
                -ms-flex: 0 0 33.333333%;
                flex: 0 0 33.333333%;

            }
            .product-list img {
                width: 60px;
                height: 60px;
                margin-bottom: 15px;
                margin-right: 10px;
                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                -ms-border-radius: 50%;
                -o-border-radius: 50%;
                border-radius: 50%;
            }

            .pull-left {
                float: left;
            }

            .pull-left {
                float: left;
            }
            .content-header:after {
                content: "";
                width: 50px;
                height: 5px;
                left: 0;
                bottom: -1px;
                position: absolute;
                background-color: #263238;
                -webkit-border-radius: 50px;
                -moz-border-radius: 50px;
                -ms-border-radius: 50px;
                -o-border-radius: 50px;
                border-radius: 50px;
            }

            ::after, ::before {
                -webkit-box-sizing: inherit;
                box-sizing: inherit;
            }
            .card-title {
                font-size: 36px;
            }
            .card-subtitle {
                margin-top: -.375rem;
                margin-bottom: 0;
            }
            .sider-navbar{

                min-height: 1200px;
                display: table-cell;
                vertical-align: top;
                background-color: buttonface;
                -webkit-border-radius: .3em;
                -moz-border-radius: .3em;
                -ms-border-radius: .3em;
                -o-border-radius: .3em;
                border-radius: .3em;
                -webkit-transition: all .4s ease;
                -moz-transition: all .4s ease;
                -ms-transition: all .4s ease;
                -o-transition: all .4s ease;
                transition: all .4s ease;
                -webkit-box-shadow: 0 0 15px rgba(0,0,0,.1);
                -moz-box-shadow: 0 0 15px rgba(0,0,0,.1);
                box-shadow: 0 0 15px rgba(0,0,0,.1);
            }
            .side-nav {
                -ms-box-shadow: 0 0 15px rgba(0,0,0,.1);
                -o-box-shadow: 0 0 15px rgba(0,0,0,.1);
            }

            .side-notification {
                font-size: 18px;
                position: relative;
                justify-content: center;
                padding: 40px 20px 20px;
                border-bottom: 1px solid #21292b;
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                -o-user-select: none;
                user-select: none;
            }
            .notification-badge {
                display: inline-block;
                line-height: 1;
                color: #fff;
                top: -6px;
                left: 9px;
                font-size: 11px;
                padding: 2px 5px;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                position: absolute;
                background-color: #e33244;
                -webkit-border-radius: 50px;
                -moz-border-radius: 50px;
                -ms-border-radius: 50px;
                -o-border-radius: 50px;
                border-radius: 50px;
            }

            .bounceInDown {
                animation-name: bounceInDown;
            }

            .animated {
                animation-duration: 1s;
                animation-fill-mode: both;
            }

            .notification-icon {
                cursor: pointer;
                color: black;
                margin-left: 20px;
                margin-right: 20px;
                position: relative;
            }
            .notification-wrapper {
                display: block;
            }

            .notification-wrapper {
                width: 300px;
                z-index: 5;
                display: none;
                color: #263238;
                font-size: 14px;
                margin-top: 20px;
                left: -25px;
                position: absolute;
                background-color: #fcfcfc;
                -webkit-border-radius: .2em;
                -moz-border-radius: .2em;
                -ms-border-radius: .2em;
                -o-border-radius: .2em;
                border-radius: .2em;
                -webkit-box-shadow: 0 0 15px rgba(0,0,0,.07);
                -moz-box-shadow: 0 0 15px rgba(0,0,0,.07);
                -ms-box-shadow: 0 0 15px rgba(0,0,0,.07);
                -o-box-shadow: 0 0 15px rgba(0,0,0,.07);
                box-shadow: 0 0 15px rgba(0,0,0,.07);
            }

            .bounceInUp {
                animation-name: bounceInUp;
            }

            .animated {
                animation-duration: 1s;
                animation-fill-mode: both;
            }
            .fa-bell:before {
                content: "\f0f3";
            }
            .content-box{
                padding: 15px;
                margin-top: 25px;
                background-color: #fcfcfc;
                -webkit-animation: showSlowlyElement .8s;
                animation: showSlowlyElement .8s;
                -webkit-border-radius: .3em;
                -moz-border-radius: .3em;
                -ms-border-radius: .3em;
                -o-border-radius: .3em;
                border-radius: .3em;
                -webkit-transition: all .4s ease;
                -moz-transition: all .4s ease;
                -ms-transition: all .4s ease;
                -o-transition: all .4s ease;
                transition: all .4s ease;
                -webkit-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -moz-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -ms-box-shadow: 0 0 15px rgba(0,0,0,.04);
                -o-box-shadow: 0 0 15px rgba(0,0,0,.04);
                box-shadow: 0 0 15px rgba(0,0,0,.04);
            }


            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -ms-overflow-style: -ms-autohiding-scrollbar;
            }
            .table {
                margin-bottom: 0;
            }

            .table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }
            .thead {
                display: table-header-group;
                vertical-align: middle;
                border-color: inherit;
            }
            .table {
                border-collapse: collapse;
                background-color: transparent;
            }

            .table {
                display: table;
                border-collapse: separate;
                border-spacing: 2px;
                border-color: grey;
            }
            .tr {
                margin: 0;
                padding: 0;
                border: 0;
                font: inherit;
                text-decoration: none;
                list-style: none;
            }
            ::after, ::before {
                -webkit-box-sizing: inherit;
                box-sizing: inherit;
            }

            .tr {
                display: table-row;
                vertical-align: inherit;
                border-color: inherit;
            }
            .table th {
                vertical-align: middle;
                border-top: 0;
            }.table td, .table th {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #eceeef;
            }
            .text-center {
                text-align: center!important;
            }
            .table td, .table th {
                vertical-align: middle;
                border-top: 0;
            }

            .table td, .table th {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #eceeef;
            }

            .text-center {
                text-align: center!important;
            }
            .fa {
                display: inline-block;
                font: normal normal normal 14px/1 FontAwesome;
                font-size: inherit;
                text-rendering: auto;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            .i {
                font-size: 18px;
                margin-right: 10px;
            }
            .fa {
                display: inline-block;
                font: normal normal normal 14px/1 FontAwesome;
                font-size: inherit;
                text-rendering: auto;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            div.zabuto_calendar{margin:0;padding:0}div.zabuto_calendar .table{width:100%;margin:0;padding:0}div.zabuto_calendar .table th,div.zabuto_calendar .table td{padding:4px 2px;text-align:center}div.zabuto_calendar .table tr th,div.zabuto_calendar .table tr td{background-color:#fff}div.zabuto_calendar .table tr.calendar-month-header th{background-color:#fafafa}div.zabuto_calendar .table tr.calendar-month-header th span{cursor:pointer;display:inline-block;padding-bottom:10px}div.zabuto_calendar .table tr.calendar-dow-header th{background-color:#f0f0f0}div.zabuto_calendar .table tr:last-child{border-bottom:1px solid #ddd}div.zabuto_calendar .table tr.calendar-month-header th{padding-top:12px;padding-bottom:4px}div.zabuto_calendar .table-bordered tr.calendar-month-header th{border-left:0;border-right:0}div.zabuto_calendar .table-bordered tr.calendar-month-header th:first-child{border-left:1px solid #ddd}div.zabuto_calendar div.calendar-month-navigation{cursor:pointer;margin:0;padding:0;padding-top:5px}div.zabuto_calendar tr.calendar-dow-header th,div.zabuto_calendar tr.calendar-dow td{width:14%}div.zabuto_calendar .table tr td div.day{margin:0;padding-top:7px;padding-bottom:7px}div.zabuto_calendar .table tr td.event div.day,div.zabuto_calendar ul.legend li.event{background-color:#fff0c3}div.zabuto_calendar .table tr td.dow-clickable,div.zabuto_calendar .table tr td.event-clickable{cursor:pointer}div.zabuto_calendar .badge-today,div.zabuto_calendar div.legend span.badge-today{background-color:#357ebd;color:#fff;text-shadow:none}div.zabuto_calendar .badge-event,div.zabuto_calendar div.legend span.badge-event{background-color:#ff9b08;color:#fff;text-shadow:none}div.zabuto_calendar .badge-event{font-size:.95em;padding-left:8px;padding-right:8px;padding-bottom:4px}div.zabuto_calendar div.legend{margin-top:5px;text-align:right}div.zabuto_calendar div.legend span{color:#999;font-size:10px;font-weight:normal}div.zabuto_calendar div.legend span.legend-text:after,div.zabuto_calendar div.legend span.legend-block:after,div.zabuto_calendar div.legend span.legend-list:after,div.zabuto_calendar div.legend span.legend-spacer:after{content:' '}div.zabuto_calendar div.legend span.legend-spacer{padding-left:25px}div.zabuto_calendar ul.legend>span{padding-left:2px}div.zabuto_calendar ul.legend{display:inline-block;list-style:none outside none;margin:0;padding:0}div.zabuto_calendar ul.legend li{display:inline-block;height:11px;width:11px;margin-left:5px}div.zabuto_calendar ul.legend div.zabuto_calendar ul.legend li:first-child{margin-left:7px}div.zabuto_calendar ul.legend li:last-child{margin-right:5px}div.zabuto_calendar div.legend span.badge{font-size:.9em;border-radius:5px 5px 5px 5px;padding-left:5px;padding-right:5px;padding-top:2px;padding-bottom:3px}@media(max-width:979px){div.zabuto_calendar .table th,div.zabuto_calendar .table td{padding:2px 1px}}
            fieldset, label { margin: 0; padding: 0; }
            body{ margin: 20px; }
            h1 { font-size: 1.5em; margin: 10px; }

            /****** Style Star Rating Widget *****/

            .rating { 
                border: none;
                float: left;

            }

            .rating > input { display: none; } 
            .rating > label:before { 
                margin: 5px;
                font-size: 1.25em;
                font-family: FontAwesome;
                display: inline-block;
                content: "\f005";
            }

            .rating > .half:before { 
                content: "\f089";
                position: absolute;
            }

            .rating > label { 
                color: #ddd; 
                float: right; 
            }

            /***** CSS Magic to Highlight Stars on Hover *****/

            .rating > input:checked ~ label, /* show gold star when clicked */
            .rating:not(:checked) > label:hover, /* hover current star */
            .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

            .rating > input:checked + label:hover, /* hover current star when changing rating */
            .rating > input:checked ~ label:hover,
            .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
            .rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 

            .btn {
                /* padding: 18px 18px !important;
                 padding-top: 18px !important;
                 padding-right: 20px !important;
                 padding-bottom: 18px !important;
                 padding-left: 20px !important;
                 margin-bottom: 0;
                */                font-size: 18 !important;
            }
            .panel-heading {
                padding: 10px 15px;
                border-bottom: 1px solid transparent;
                border-top-right-radius: -1;
                border-top-left-radius: -1;
            }.panel-body {
                padding: 15px;
            }
            .panel-body > .navbar {
                margin-bottom: 0;
            }

            .panel-body > *:last-child {
                margin-bottom: 0;
            }

            .navbar-default {
                border-bottom: 1px solid #e2e9e6;
            }
            .navbar-default {
                background-color: #ffffff;
                border-color: #eeeeee;
            }


            .navbar {
                border-radius: 4px;
            }

            .navbar {
                position: relative;
                min-height: 50px;
                margin-bottom: 20px;
                border: 1px solid transparent;
            }

            * {
                box-sizing: border-box;
            }
            user agent stylesheet
            div {
                display: block;
            }.pull-right {
                float: right;
            }
            .select-rounded {
                height: 38px;
                -webkit-appearance: none;
                -moz-appearance: none;
                padding-right: 15px;
                padding-left: 15px;
                font-weight: 400;
                font-size: 12px;
                border-color: #263238;
                background-color: transparent;
                position: relative;
                outline: 0;
                background-position: right 5px top 50%;
                background-repeat: no-repeat;
                background-image: url(../images/select-arrow.png);
                -webkit-border-radius: 30px;
                -moz-border-radius: 30px;
                -ms-border-radius: 30px;
                -o-border-radius: 30px;
                border-radius: 30px;
            }.pull-right {
                float: right;
            }
            .button{
                box-shadow: none;
                border-radius: 3px;
                font-size: 100px!important;
                font-weight: bolder !important;
                -webkit-font-smoothing: antialiased !important;
                font-family: monospace !important;
            }
            .menu-toggle{
                display: none ! important;

            }

            /*Employer Dashboard css
            */
            .codexworld_rating_widget{
                padding: 0px;
                padding-top: 5px;
                margin: 0px;

            }
            .codexworld_rating_widget li{
                line-height: 0px;
                width: 28px;
                height: 28px;
                padding: 0px;
                margin: 0px;
                margin-left: 2px;
                list-style: none;
                float: left;
                cursor: pointer;
            }
            .codexworld_rating_widget li span{
                display: none;
            }

            .overall-rating{font-size: 14px;margin-top: 5px;color: #8e8d8d;}
            .table-hover { 
                width: 100%; 
                border-collapse: collapse; 
            }

            table.calendar		{ border-left:1px solid #999; overflow-x:auto;}
            tr.calendar-row	{  }
            td.calendar-day	{ min-height:80px; font-size:11px; position:absolute; } * html div.calendar-day { height:80px; }
            td.calendar-day:hover	{ background:#eceff5; }
            td.calendar-day-np	{ background:#eee; min-height:80px; } * html div.calendar-day-np { height:80px; }
            td.calendar-day-head { background:#ccc; font-weight:bold; text-align:center; width:120px; padding:5px; border-bottom:1px solid #999; border-top:1px solid #999; border-right:1px solid #999; }
            div.day-present{ background:green; padding:5px; color:green; font-weight:bold; float:right; margin:-5px -5px 0 0; width:20px; text-align:center; }
            div.day-number {
                padding: 3px;
                color: black;
                font-weight: bold;
                float: right;
                margin: 0;
                width: 30px;
                text-align: center;
            }



            /* shared */
            td.calendar-day, td.calendar-day-np { width:120px; padding:5px; border-bottom:1px solid #999; border-right:1px solid #999; }
            .present {color:green;font-size: medium;}
            .absent {color:red;font-size: medium;}
            .orphan{color:blue;font-size: medium;}
            .calendar-day .label {font-size: 14px; display: inherit;}
            td {width:80px !important;}
            fieldset, form {
                margin-bottom: 0px;
            }


            #one
            #two,#three
            {
                display: inline;
                height: 40px;
                line-height: 36px;
                box-sizing: border-box;
                text-align: center;
                border-radius: 6px;
                margin-top: 30px;
            }
            .cTable td{
                align:center;
            }
            td img{
                display: block;
                margin-left: auto;
                margin-right: auto;

            }



        </style>

        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
        <!--<script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>-->
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <!--<script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>-->                
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->


        <script language="javascript" type="text/javascript">
            $(function () {
                $("#rating_star").codexworld_rating_widget({
                    starLength: '5',
                    initialValue: '',
                    callbackFunctionName: 'processRating',
                    imageDirectory: 'images/',
                    inputAttr: 'postID'
                });
            });
            function processRating(val, attrVal) {
                console.log(val);
                console.log(attrVal);
                $.ajax({
                    type: 'POST',
                    //url:'../wp-content/themes/deli/rating',
                    //url: '/rating',
                    data: 'dwempid=' + attrVal + '&dwid=' + val,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'ok') {
                            alert('You have rated ' + val + ' to this Domestic worker');
                            $('#avgrat').text(data.average_rating);
                            $('#totalrat').text(data.rating_number);
                        } else {
                            alert('Some problem occured, please try again.');
                        }
                    }
                });
            }
        </script>



        <script type="text/javascript">
            (function (a) {
                a.fn.codexworld_rating_widget = function (p) {
                    var rat = "0";
                    var p = p || {};
                    var b = p && p.starLength ? p.starLength : "5";
                    var c = p && p.callbackFunctionName ? p.callbackFunctionName : "";
                    var rat = document.getElementById("v_rat").value;
                    console.log(rat);
                    var e = p && p.initialValue ? p.initialValue : rat;
                    var d = p && p.imageDirectory ? p.imageDirectory : "images";
                    var r = p && p.inputAttr ? p.inputAttr : "";
                    var f = e;
                    var g = a(this);
                    b = parseInt(b);
                    init();
                    g.next("ul").children("li").hover(function () {
                        $(this).parent().children("li").css('background-position', '0px 0px');
                        var a = $(this).parent().children("li").index($(this));
                        $(this).parent().children("li").slice(0, a + 1).css('background-position', '0px -28px')
                    }, function () {});
                    g.next("ul").children("li").click(function () {
                        var a = $(this).parent().children("li").index($(this));
                        var attrVal = (r != '') ? g.attr(r) : '';
                        f = a + 1;
                        g.val(f);
                        if (c != "") {
                            eval(c + "(" + g.val() + ", " + attrVal + ")")
                        }
                    });
                    g.next("ul").hover(function () {}, function () {
                        if (f == "") {
                            $(this).children("li").slice(0, f).css('background-position', '0px 0px')
                        } else {
                            $(this).children("li").css('background-position', '0px 0px');
                            $(this).children("li").slice(0, f).css('background-position', '0px -28px')
                        }
                    });
                    function init() {
                        $('<div style="clear:both;"></div>').insertAfter(g);
                        g.css("float", "left");

                        var a = $("<ul>");
                        a.addClass("codexworld_rating_widget");
                        for (var i = 1; i <= b; i++) {
                            //var imagePath = YOURSITENAME.templateURI + "/library/images/image.jpg";
                            var $img = '<img src="../wp-content/themes/deli/images/widget_star.gif"/>';
                            console.log("$img", $img);
                            //a.append($img);
                            a.append('<li style="background-image:url(../wp-content/themes/deli/images/widget_star.gif)"><span>' + i + '</span></li>');
                            //document.getElementById('v_dwempid').value, document.getElementById('v_dwid').value
                        }
                        a.insertAfter(g);
                        if (e != "") {
                            f = e;
                            g.val(e);
                            g.next("ul").children("li").slice(0, f).css('background-position', '0px -28px')
                        }
                    }
                }
            })(jQuery);
        </script>
        <script type="text/javascript">
            jQuery(function ($) {
                var filter;
                $('.filter').click(function () {
                    if (filter == this.id) {
                        $('tr').show();

                        filter = undefined;
                    } else {
                        $('#task tr:not(.' + this.id + ')').hide();
                        $('tr.' + this.id).show();
                        filter = this.id;
                    }
                });
            });
        </script>


    </head>
    <?php get_header(); ?>
    <body>
        <nav class="navbar navbar-inverse visible-xs">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar1">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $currentemp[0]['name']; ?></a>

                </div>
                <div class="collapse navbar-collapse" id="myNavbar1">
                    <ul class="nav navbar-nav" style="list-style-type: none;">
                        <li><a href="#"><span class="fa fa-bars"></span> Dashboard</a></li>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Domestic Worker">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseDw1" data-parent="#exampleAccordion">

                                <input type="hidden" name="v_photo" value="<?php echo $currentemp[0]['photoname']; ?>">

                                <a href="<?php
                                echo site_url() . "/empprofile?v_dwempid " . $dwempid . '&v_photo=' . $currentemp[0]['photoname'];
                                "'"
                                ?>"> 

                                    <span class="fa fa-user-md"></span> 
                                    My Profile</a>


                        </li>
                        <?php if($currentstatus[0]['substatus']=='paid'){?>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseEmp1" data-parent="#exampleAccordion">
<!-- onclick="if($currentstatus[0]['substatus']=='paid'){return true;}else{event.stopPropagation(); event.preventDefault();};"                                -->
                                <a href="<?php echo site_url() . "/requesthelper" ?>" >

                                    <span class="fa fa-search-plus"></span>

                                    Request for a Helper</span>
                                </a>
                               
                              
                              

                        </li>
                        <?php }else{?>
                             <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseEmp1" data-parent="#exampleAccordion">
<!-- onclick="if($currentstatus[0]['substatus']=='paid'){return true;}else{event.stopPropagation(); event.preventDefault();};"                                -->
                                
<a href="<?php echo site_url() . "/dashboard" ?>" onclick="return confirm('Please Pay for continue..');" >
   

                                    <span class="fa fa-search-plus"></span>

                                    Request for a Helper</span>
                                </a>
                               
                              
                              

                        </li>
                       <?php }?>
                        
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="contract">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseContracts1" data-parent="#exampleAccordion">

                                <a href="<?php echo site_url() . "/registermydw" ?>"> 

                                    <span class="fa fa-registered"></span>

                                    Register My Helper</span>
                                </a>

                        </li>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="contract">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseContracts1" data-parent="#exampleAccordion">

                                <a  href="https://aideexpert.freshdesk.com/support/tickets/new" target="_blank">
                                    <span class="fa fa-phone"></span>
                                    <span class="nav-link-text">
                                        Contact Aideexpert</span>
                                </a>

                        </li> 
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="contract">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseContracts1" data-parent="#exampleAccordion">

                                <?php
                                add_filter('allowed_redirect_hosts', 'allow_ms_parent_redirect');
                                allow_ms_parent_redirect($allowed);
                                ?>
                                <a href="<?php echo wp_logout_url('https://aideexpert.com'); ?>" >

                                    <span class="fa fa-sign-out"></span>

                                    Logout</span>
                                </a>

                        </li>


                    </ul>
                </div>
            </div>
        </nav>

        <section id="page-keeper">
            <div class="container-fluid">

<div class="row">

                <div class="col-sm-3 sidenav hidden-xs">
                    <ul class="nav navbar-nav sider-navbar" style="list-style-type: none;">


                        <li id="profile">

                            <figure class="profile-userpic">

                                <?php if ($currentemp[0]['photoname'] == '') {
                                    ?>                                
                                    <img class="img-responsive"  src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />




                                    <?php
                                } else {
                                    ?>
                                    <img class="img-responsive"  src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentemp[0]['photoname']; ?>" alt="Image Title" border="0" />
                                <?php } ?>
<!--  <img src="http://www.keita-gaming.com/assets/profile/default-avatar-c5d8ec086224cb6fc4e395f4ba3018c2.jpg" class="img-responsive" alt="Profile Picture">
                                --></figure>
                            <div class="profile-usertitle">
<!--  website -->
                                <div class="profile-usertitle-name"><?php echo $currentemp[0]['name']; ?></div>
                                
                                <div class="profile-usertitle-title"><?php echo $currentemp[0]['area']; ?></div>
                            </div>
                        </li>
                        <li class="sider-menu">

                            <ul>
                                <li><a href="#" style="color: #3BACA9;"><span class="fa fa-bars"></span>Dashboard</a></li>

                                <li class="active"><a href="<?php echo site_url() . "/empprofile?v_dwempid= " . $thisemployer['dwempid'] . "'" ?>" style="color: #3BACA9;">   <span class="fa fa-user-md"></span> My Profile </a></li>
 
                                <li>
                                
                                    <a href="<?php echo site_url() . "/requesthelper" ?>" style="color: #3BACA9;">
                                        <span class="fa fa-search-plus"></span> Request For a Helper</a>
                                </li>
 
                                <li><a href="<?php echo site_url() . "/registermydw" ?>" style="color: #3BACA9;"><span class="fa fa-registered"></span> Register My Helper</a></li>
                                <li><a href="https://aideexpert.freshdesk.com/support/tickets/new" style="color: #3BACA9;" target="_blank"><span class="fa fa-phone"></span> Contact AideExpert</a></li>
                                <li>

                                    <?php
                                    add_filter('allowed_redirect_hosts', 'allow_ms_parent_redirect');
                                    allow_ms_parent_redirect($allowed);
                                    ?>
                                    <a href="<?php echo wp_logout_url('https://aideexpert.com'); ?> "style="color: #3BACA9;">
                                        <span class="fa fa-sign-out"></span> Logout</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="col-md-9">
                    <div class="row">

                        <div class="panel-body">

                            <div class="navbar navbar-default">
                                <div class="container-fluid">


                                    <form class="form-horizontal demo-form" name="searchdw" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">

                                       
                                        <?php if (!$contractsRegistered) { ?>
                                            <div class="alert alert-block alert-success">


                                                <i class="ace-icon fa fa-check green"></i>

                                                Welcome  to
                                                <strong class="green">
                                                    AideExpert

                                                </strong>,
                                                You can 
                                                  <a href="<?php echo site_url() . "/registermydw"?>"><button type="button" name="Register" value="Register" style="background-color: #3BACA9;margin: auto;" ><font color="white">Register</font></button></a>
                                                  
<!--                                                <a href="<?php echo site_url() . "/registermydw" ?>"><b>Register</b></a> -->
                                                your domestic worker or you can  
                                                
                                                  <a href="<?php echo site_url() . "/requesthelper"?>"><button type="button" name="Register" value="Register" style="background-color: #3BACA9;margin: auto;margin-top: 2px;" ><font color="white">Request</font></button></a>

 for domestic helper.

                                            </div>

                                        <?php } else {
                                            ?>
                                            <?php
                                            foreach ($allcontracts as $row) {
                                                if ($contractid == $row['contractid']) {
                                                    $dwid = $row['dwid'];
                                                    $currentdw = array();
                                                    $sql = "SELECT * FROM `dws` WHERE `dwid` ='" . $dwid . "'";


                                                    $result = dwqueryexec(__FUNCTION__, $sql);
                                                    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                }
                                            }
                                            ?>

                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <h3  style="font-size: 18px;">Select Domestic Help    </h3>
                                                </div>
                                                <div class="col-md-3 text-center">
    <!--                                                         <section class="hidden-xs">-->
                                                    <?php if ($currentdw[0]['photoname'] == '') {
                                                        ?>                                
                                                        <img class="img-responsive" style="height:70px; width:70px;margin: 0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img alt="aideexpert" style="height:70px;width:70px;margin: 0 auto;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname']; ?>">
                                                    <?php } ?>
                                                    <!--</section>-->


                                        <!--                                                         <section class="hidden-md">
                                                    <?php if ($currentdw[0]['photoname'] == '') {
                                                        ?>                                
                                                                                <img class="img-responsive" style="width:70px;margin:0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />
                                                        <?php
                                                    } else {
                                                        ?>
                                                                                    <img alt="aideexpert" class="pull-left" style="width:70px;margin:0 auto;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname']; ?>">
                                                    <?php } ?>
                                         </section>-->

                                                    <div class="clearfix"></div>
                                                </div>

                                                <!--                                                <div class="form-group">
                                                                                                    <div class="col-md-12">
                                                                                                         <section class="hidden-md">
                                                                                                        <div class="table-responsive"> 
                                                                                                            
                                                                                            <table class="cTable">
                                                                                                <thead><tr>
                                                                                                        <th>Select Domestic Help</th>
                                                                                                        
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        
                                                                                                    <div class="col-md-3">
                                                                                                         
                                                <?php if ($currentdw[0]['photoname'] == '') {
                                                    ?>                                
                                                                                                                                                <td align="center"><img class="img-responsive" style="width:70px;margin-top:-5px;" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" /></td>
                                                    <?php
                                                } else {
                                                    ?>
                                                                                                                                                <td align="center"><img alt="aideexpert" class="pull-left" style="width:70px;margin-top:-5px;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname']; ?>"></td>
                                                <?php } ?>
                                                                                                        
                                                                                                    </div>
                                                                                            </tr>
                                                                                                    
                                                                                                                                                                </section>
                                                
                                                                                                </tbody>
                                                                                            </table>
                                                                                                             </section>
                                                        </div>
                                                        
                                                        <section class="hidden-xs"> 
                                                            <div class="col-md-12">
                                                                                                        <h3  style="font-size: 18px;">Select Domestic Help    </h3>
                                                                                                    </div>
                                                         <div class="col-md-3">
                                                       
                                                <?php if ($currentdw[0]['photoname'] == '') {
                                                    ?>                                
                                                                                                         <img class="img-responsive" style="width:70px;margin-left:70px" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />
                                                    <?php
                                                } else {
                                                    ?>
                                                                                                                                                    <img alt="aideexpert" class="pull-left" style="width:70px;margin-top:-5px;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname']; ?>">
                                                <?php } ?>
                                                                                                         </section>
                                                         </div>-->

                                                <div class="col-md-6">
                                                    <select name="contractid" id="contract1" class="form-control">

                                                        <?php
                                                        foreach ($allcontracts as $row) {
                                                            dwerror_log(__FUNCTION__ . ":Row=" . $row['contractid'] . ":" . $row['dwname']);
                                                            ?>

                                                            <option value="<?php echo $row['contractid']; ?>" 
                                                                    <?php if ($contractid == $row['contractid']) echo " selected=\"selected\" "; ?>  > 


                                                                <?php echo $row['dwname'];
                                                                ?>
                                                            <?php }
                                                            ?></option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 text-center" >

                                                    <input type="submit" name="dwupdate" value="Submit" class="btn btn-info">
                                                </div>
                                               
                                                <div class="col-md-6 text-center" >
                                                <span style="background-color: #5DC8B0 !important;" class="badge badge-success"><b><?php echo $currentstatus[0]['substatus']; ?></b></span><br>
                                <span style="background-color: cadetblue !important;" class="badge badge-success">from:<?php echo  $currentstatus[0]['substartdate']; ?></span>
                                <span style="background-color: cadetblue !important;" class="badge badge-success">to:<?php echo $currentstatus[0]['subenddate']; ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="hidden-xs">
                        <div class="row">

                            <div class="col-sm-3 card-wrapper">
                                <div class="card card-inverse" style="background-color :  #C2DBD8;">
                                    <i class="fa fa-calendar-check-o"></i>
                                    <div class="clear">
                                        <div class="card-title">
                                            <span  class="timer"><?php
                                                if (count($row) <= 0) {
                                                    echo '<span style="color:green">0</span>';
                                                    echo "/";
                                                    echo '<span style="color:red">0</span>';
                                                }
                                                foreach ($allcontracts as $row) {

                                                    if ($contractid == $row['contractid']) {
                                                        $dwid = $row['dwid'];
                                                        $countatten = array();
                                                        $lastmonth = date("Y-m-d", strtotime('-30 day'));

                                                        //Calculate hajeri and khada for all DWs active contracts for past 30 days
                                                        $sql = "SELECT count(`status`) FROM `unitjobs` WHERE `contractid`='" . $contractid . "' AND `jobdate`>='" . $lastmonth . "' AND (`status`='inprogress' OR `status`='completed')";

//echo $sql;    
                                                        $result = dwqueryexec(__FUNCTION__, $sql);
                                                        $countatten = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                        $sql = "SELECT count(`status`) FROM `unitjobs` WHERE `contractid`='" . $contractid . "' AND `jobdate`>='" . $lastmonth . "' AND (`status`='planned' OR `status`='created')";
                                                        //echo $sql;
                                                        $result = dwqueryexec(__FUNCTION__, $sql);
                                                        $countatten1 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                        //print_r($countatten);
                                                        echo '<span style="color:green">' . $countatten[0]['count(`status`)'] . '</span>';
                                                        echo "/";
                                                        echo '<span style="color:red">' . $countatten1[0]['count(`status`)'] . '</span>';
                                                    }
                                                }
                                                ?>
                                            </span>

                                        </div>

                                        <div class="card-subtitle"> <font color="green" size="1.5px">Present</font>  /
                                            <font color="red" size="1.5px">Absent</font></div>

                                        <!--                                            <div class="card-subtitle" ><font size="1.5px">Past 30 days</font></div>-->
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 card-wrapper">
                                <div class="card card-inverse" style="background-color :  #C2DBD8;">
                                    <i class="fa fa-calendar"></i>
                                    <div class="clear">
                                        <div class="card-title">
                                            <span class="timer"><?php
                                                // echo $countatten[0]['hajeri30'];
                                                $lastmonth = date("Y-m-d", strtotime('-30 day'));
                                                $sql = "SELECT COUNT(`status`) FROM `unitjobs` WHERE `contractid`='" . $contractid . "' AND `jobdate`>='" . $lastmonth . "' AND (`status`='inprogress' OR `status`='completed')";

//echo $sql;    
                                                $result = dwqueryexec(__FUNCTION__, $sql);
                                                $countatten2 = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                //Array ( [0] => Array ( [COUNT(`status`)] => 10 ) )
                                                // print_r($countatten2);
                                                //  echo  $atten;
                                                $avgatten = (($countatten[0]['count(`status`)'] / 30) * 100);
                                                echo round($avgatten);
                                                ?>%</span>
                                        </div>
                                        <div class="card-subtitle">Attendance</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 card-wrapper">
                                <div class="card card-inverse" style="background-color :  #C2DBD8;">
                                    <i class="fa fa-th-list"></i>
                                    <div class="clear">
                                        <div class="card-title">
                                            <span class="timer"><?php
                                                if (count($allcontracts) <= 0) {
                                                    echo '<span>0</span>';
                                                    echo '<span>/</span>';
                                                    echo '<span>0</span>';
                                                } else {
                                                    $date = date('Y-m-d ');
                                                    $sql = "SELECT COUNT(*) as total FROM  `tasks` WHERE  `contractid` =" . $contractid . " AND  `status` =  '0' AND `jobdate` =  '" . $date . "'";
                                                    $result = dwqueryexec(__FUNCTION__, $sql);
                                                    $counttask = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                    echo $counttask[0]['total'];
                                                    echo "/";
                                                    $sql = "SELECT COUNT(*) as total FROM  `tasks` WHERE  `contractid` =" . $contractid . " AND `jobdate` =  '" . $date . "'";
                                                    $result = dwqueryexec(__FUNCTION__, $sql);
                                                    $counttotaltask = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                    echo $counttotaltask[0]['total'];
                                                }
                                                ?></span>
                                        </div>
                                        <div class="card-subtitle">Todays Task</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 card-wrapper">
                                <div class="card card-inverse" style="background-color :  #C2DBD8;">
                                    <i class="fa fa-commenting"></i>
                                    <div class="clear">
                                        <div class="card-title">
                                            <span class="timer"><?php
                                                if (count($row) <= 0) {
                                                    echo '<span>0</span>';
                                                }
                                                foreach ($allcontracts as $row) {
                                                    if ($contractid == $row['contractid']) {
                                                        $dwid = $row['dwid'];
                                                        $countavgrating = array();
                                                        $sql = "SELECT * FROM `dws` WHERE `dwid` ='" . $dwid . "'";
                                                        $result = dwqueryexec(__FUNCTION__, $sql);
                                                        $countavgrating = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                        //print_r($countatten);
                                                        // echo $countavgrating[0]['avgrating'];
                                                        $avgcolor = $countavgrating[0]['avgrating'];
                                                        if ($avgcolor >= 3) {
                                                            echo '<span style="color:green">' . $countavgrating[0]['avgrating'] . '</span>';
                                                        } else {
                                                            echo '<span style="color:red">' . $countavgrating[0]['avgrating'] . '</span>';
                                                        }
                                                    }
                                                }


                                                //echo $rating[0]['rating_number'] 
                                                ?></span>
                                        </div>
                                        <div class="card-subtitle">Avg Feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="content-header">
                                <i class="fa fa-suitcase"></i>
                                <div class="text-center">   
                                    <div class="content-header-title">Tasks</div>
                                    <?php if (count($allcontracts) > 0) { ?>
                                        <a href="<?php echo site_url() . '/createtasks?contractid=' . $contractid; ?>"><input type="button" name="Addtask" value="Add Task" class="select-rounded pull-right"  style="height:38px; margin: 0 auto;" ></a>
                                    <?php } else { ?>
                                        <input type="button" name="Addtask" value="Add Task" class="select-rounded pull-right"  style="height:38px; margin: 0 auto;" disabled>

                                    <?php } ?>

                                    <button type="button" class="btn btn-lg filter" id="incompleted" style="height:38px; margin-left: 65px; background-color: #3BACA9 !important;">
                                        <i class="fa fa-check-circle" aria-hidden="true" style="
                                           color: white !important;
                                           font-size: larger !important;"></i>

                                    </button>
                                </div>
                                                   <!--  <input type="button" name="Completedtask" value="Completed Task" onclick='hello("You clicked!");'class="select-rounded pull-right"  style="height:38px; margin: 0 auto;" ></a>
                                -->
                            </div>



                            <div class="content-box" style="height:200px; overflow: scroll;-webkit-overflow-scrolling:touch;
                                 ">



                                <div class="col-xs-16">
                                    <div class="table-responsive"> 
<?php if (count($alltasks) == 0) { ?>
                                                <td class="manage-column ss-list-width">
                                                
                                                  <?php 
                                                 
                                                   echo "<div class='alert alert-block alert-success'>";


                                               

                                               echo " Go ahead try me!";
                                               echo" <strong class='green'>";
                                                   echo "Add some task for your helper";

                                                echo "</strong>";
                                                   echo "</div>";
                                                   
                                                  ?>
                                                
                                                </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                                                <?php
                                            } else {?>
                                        <table id="task" class="table table-striped">
                                            <thead><tr class="incompleted">
                                                    <th>Status</th>
                                                    <th>Task</th>
                                                    <th>Date</th>
                                                    <th><i class="fa fa-star-o" aria-hidden="true" ></i></th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                foreach ($alltasks as $row) {
                                                    ?>

                                                                                        <!--                                            <tr class="female">-->
                                                    <tr 
                                                    <?php
                                                    if ($row['status'] == 1) {
                                                        echo ' class="completed" ';
                                                    } else {
                                                        echo ' class="incompleted" ';
                                                    }
                                                    ?> >




                                                        <td class="manage-column ss-list-width">
                                                            <div class="red box"><?php
                                                                if (($row['status']) == 1) {
                                                                    ?>

                                                                    <i class="fa fa-check-square-o" id="completedtask" aria-hidden="true" data-toggle="tooltip" title="Done"></i>

                                                                </div>
                                                                <?php
                                                            } else {
                                                                ?>

                                                                <i class="fa fa-square-o" aria-hidden="true" id="incompletedtask" data-toggle="tooltip" title="Pending"></i>

                                                                <?php
                                                            }
                                                            ?>



                                                        </td>
                                                        <td class="manage-column ss-list-width"><a href="<?php echo site_url() . '/updatetasks?id=' . $row['_id'] . '&taskname=' . $row['taskname'] . '&priority=' . $row['priority'] . '&status=' . $row['status'] . '&jobdate=' . $row['jobdate'] . '&contractid=' . $contractid; ?>"><?php echo $row['taskname']; ?></td></a></td>




                                                        <td class="manage-column ss-list-width"><?php echo date('d M', strtotime($row['jobdate'])); ?></td>
                                                        <td class="manage-column ss-list-width"><?php
                                                            if (($row['priority']) == 1) {
                                                                ?>
                                                                <div class="red box">  
                                                                    <span class="tooltiptext" data-toggle="tooltip" title="High"> <i class="fa fa-star" aria-hidden="true"  ></i></span>
                                                                </div>  <?php
                                                            } else {
                                                                ?>


                                                                <i class="fa fa-star-o" aria-hidden="true" data-toggle="tooltip" title="Low"></i>
                                                                <?php
                                                            }
                                                            ?>          
                                                        </td>


                                                    </tr>

                                                    <?php
                                                }
                                            }
                                            ?>

                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="content-header">
                                <form class="form-horizontal demo-form" name="feedback" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">

                                    <i class="fa fa-newspaper-o"></i>
                                    <div class="content-header-title">Today's Rating</div>
                                    <?php if (count($allcontracts) > 0) { ?>
                                        <input type="submit" class="select-rounded pull-right" name="rating" value="Save" style="height:38px; margin: 0 auto;" />
                                    <?php } else { ?>
                                        <input type="submit" class="select-rounded pull-right" name="rating" value="Save" style="height:38px; margin: 0 auto;" disabled />
                                    <?php } ?>

                                    <input type="hidden" name="contractid" value="<?php echo $contractid; ?>">

                                    </div>

                                    <div class="content-box " style="height: 50px; display: flex; flex-direction: column;justify-content: center;text-align: center;
                                         align-items: center;">
                                         <?PHP
                                         $rating = array();
                                         $sqlresul = "SELECT * FROM `unitjobs` WHERE `contractid` = '" . $contractid . "' ORDER BY  unitjobid DESC LIMIT 1";
//echo $sqlresul;
                                         $previresult = dwqueryexec(__FUNCTION__, $sqlresul);
                                         $rating = mysqli_fetch_all($previresult, MYSQLI_ASSOC);
//echo $rating[0]['rating_number'];
//print_r($rating);
                                         ?>
                                         <?php $rate = $rating[0]['feedback']; ?>   
                                        <input name="v_rat" value="<?php echo $rating[0]['feedback']; ?>" id="v_rat" type="hidden" />

                                        <input name="rating-star" value="<?php echo $rating[0]['feedback']; ?>" id="rating_star" type="hidden" />


                                        <?php
                                        foreach ($allcontracts as $new) {


                                            if ($contractid == $new['contractid']) {
                                                ?>

                                                <input name="v_dwid" value="<?php echo $new['dwid']; ?>" id="v_dwid" type="hidden" />
                                                <input name="v_dwempid" value="<?php echo $new['dwempid']; ?>" id="v_dwempid" type="hidden" />

                                            <?php } ?>

                                        <?php } ?>
                                    </div>


                                </form>    

                                <form class="form-horizontal demo-form" name="notes" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">

                                    <div class="content-header">

                                        <i class="fa fa-newspaper-o"></i>
                                        <div class="content-header-title">Notes</div>
                                        <input type="hidden" name="contractid" value="<?php echo $contractid; ?>">
                                        <?php if (count($allcontracts) > 0) { ?>
                                            <input type="submit" class="select-rounded pull-right" name="Save" value="Save" style="height:38px; margin: 0 auto;" />
                                        <?php } else { ?>
                                            <input type="submit" class="select-rounded pull-right" name="Save" value="Save" style="height:38px; margin: 0 auto;" disabled/>
                                        <?php } ?>
                                    </div>
                                    <div class="content-box" style="height: 80px;">
                                        <div class="form-group">
                                            <?php if (count($allcontracts) == 0) { ?>
                                                 
                                                   <div class='alert alert-block alert-success'>


                                               

                                                Make Notes of
                                               <strong class='green'>
                                                 Leaves,Advances and Anything else.. 

                                                </strong>
                                                  </div>
                                             
                                            
                                                <?php
                                            } else {?> 
                                                
                                                <textarea rows="3" class="form-control" name="v_dwempnotes" style=overflow:scroll">
                                           
                                                
                                                <?php foreach ($allcontracts as $col) {
                                                    if ($contractid == $col['contractid'])
                                                        echo $col['dwempnotes'];
                                                }
                                            }
                                                ?></textarea>  
                                        </div>
                                    </div>

                            </div>
                            </form>


                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="content-header">
                                    <i class="fa fa-suitcase"></i>
                                    <div class="content-header-title">Attendance</div>
                                </div>
                                <div class="content-box">
                                    <div class="box">
                                        <?php
                                        $mydate = date('Y-m-d');
                                        $time = strtotime($mydate);
                                        $month = date("m", $time);
                                        $strmon = date("F", $time);
                                        $year = date("Y", $time);
                                        echo '<h2>' . $year . '    ' . $strmon . '</h2>';
                                        echo draw_calendar($contractid, $month, $year);
//echo $contractid; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <!--                            <div style="display: flex;flex-wrap: wrap;justify-content: space-around;">
                                                            <a href="<?php echo site_url() . '/editempcont'; ?>"><button type="button" style="background-color: #3BACA9;"><font color="white">Edit Contract</font></button></a>
                          
                                                            <button type="button"  style="background-color: #3BACA9;" ><font color="white">Pay Bonus</font></button>
                                                            <button type="button" class="xsmt10" style="background-color: #3BACA9;" ><font color="white">Request Replacement</font></button>
                                                        </div>
                        -->
                        
                        <div class="row">
                        <div style="display: flex;flex-wrap: wrap;justify-content: space-around;">
                            <a href="<?php echo site_url() . '/editempcont?v_contractid=' . $contractid; ?>"><button type="button" style="background-color: #3BACA9;"><font color="white">Edit Contract</font></button></a>

                           
                        <?php
if($currentstatus[0]['substatus']=='paid')
{?> <button type="button" name="Pay_subscription" value="Pay Monthly" style="background-color: #3BACA9;margin: auto;" disabled><font color="white">Subscribe Monthly</font></button>
<button type="button" name="Pay_subscription" value="Pay Monthly"  style="background-color: #3BACA9; margin-top: 5px!important;margin: auto;" disabled><font color="white">Subscribe Annual</font></button>

    <a href="<?php echo site_url() . "/askreplacement?v_dwid=" . $dwid . '&v_dwempid=' . $dwempid . '&v_contractid=' . $contractid; ?>"> <button type="button" name="request_replacement" value="Request Replacement" class="xsmt10" style="background-color: #3BACA9;" ><font color="white">Request Replacement</font></button></a> 
                       
<?php
}else{
?>
                             <a href="<?php echo site_url() . "/paymentreqsub?v_dwempid=" . $dwempid . '&v_contractid=' . $contractid; ?>"><button type="button" name="Pay_subscription" value="Pay Bonus" style="background-color: #3BACA9;margin: auto;" ><font color="white">Subscribe Monthly</font></button></a>
                             <br>
                             <a href="<?php echo site_url() . "/annualpay?v_dwempid=" . $dwempid . '&v_contractid=' . $contractid; ?>"><button type="button" name="Pay_subscription" value="Pay Bonus" style="background-color: #3BACA9;margin-top: 5px!important;margin: auto;" ><font color="white">Subscribe Anual</font></button></a>
      
                             <button type="button" name="request_replacement" value="Request Replacement" class="xsmt10" style="background-color: #3BACA9;" disabled ><font color="white">Request Replacement</font></button>
                       
<?php }?>   
            
                        </div>
                              </div>


                    </div>
                </div>


            </div>
        </div>
    </section>

    <?php get_footer(); ?>
</body>
</html>
<?php
// Wrap up script
closeAll();
?>