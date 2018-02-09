 <?php
/* Template Name:EmployerReg2 */


if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
initconfig();
//connecton will be created
createConnection();
$dwempid = $_REQUEST["v_dwempid"];
$regmobile = $_REQUEST["v_regmobile"];
$currentotp = array();


if ($_REQUEST['v_regmobile'] !== "") {
    $regmobile = $_REQUEST["v_regmobile"];
    $dwempid = $_REQUEST["v_dwempid"];
    $sql = "SELECT `regmobile` FROM `dwemps` WHERE `dwempid` ='" . $dwempid . "'";
    $result = dwqueryexec(__FUNCTION__, $sql);
}

if (isset($_REQUEST['verify'])) {



    $code = $_REQUEST["v_otp"];
    $regmobile = $_REQUEST["v_regmobile"];

    $sql = "SELECT `code` FROM `empotpgeneration` WHERE `mobileno` ='" . $regmobile . "' ORDER BY `otp_id` DESC  LIMIT 1";

    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentotp = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    if ($currentotp[0]['code'] == $code) {

        
        $regmobile = $_REQUEST["v_regmobile"];
          $dwempid = $_REQUEST["v_dwempid"];

        $name = $_REQUEST["v_name"];
        $mobverify = TRUE;
        $sql = "UPDATE `dwivr`.`dwemps` SET  `mobverify` =  '1' WHERE  `dwemps`.`dwempid` ='".$dwempid."'";

        $result = dwqueryexec(__FUNCTION__, $sql);
        echo "<script>alert('You Have Successfully Registered. Welcome to AideExpert. Please Set Password For Your Account By Clicking On Verification Link in Your email.');document.location='" .site_url() . "/dashboard?v_dwempid=".$empid."';</script>";
    } else {

        echo "<script>alert('You Entered Wrong OTP. You Can Resend OTP. ');document.location='" . site_url() . "/empreg2?v_dwempid=" . $empid . "& v_regmobile=" . $regmobile . "&register=Register';</script>";
    }
}
?>

<html>
    <!--
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registerdw.html : Employer Registration field...
 *

 *  -->
    <head>
        <title>Aideexpert.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>
        
                <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                        
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->
        <script type="text/javascript">
            $(function () {
                var $sections = $('.form-section');

                function navigateTo(index) {
                    // Mark the current section with the class 'current'
                    $sections
                            .removeClass('current')
                            .eq(index)
                            .addClass('current');
                    // Show only the navigation buttons that make sense for the current section:
                    $('.form-navigation .previous').toggle(index > 0);
                    var atTheEnd = index >= $sections.length - 1;
                    $('.form-navigation .next').toggle(!atTheEnd);
                    $('.form-navigation [type=submit]').toggle(atTheEnd);
                }

                function curIndex() {
                    // Return the current index by looking at which section has the class 'current'
                    return $sections.index($sections.filter('.current'));
                }

                // Previous button is easy, just go back
                $('.form-navigation .previous').click(function () {
                    navigateTo(curIndex() - 1);
                });

                // Next button goes forward iff current block validates
                $('.form-navigation .next').click(function () {
                    if ($('.demo-form').parsley().validate({group: 'block-' + curIndex()}))
                        navigateTo(curIndex() + 1);
                });


                // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
                $sections.each(function (index, section) {
                    $(section).find(':input').attr('data-parsley-group', 'block-' + index);
                });
                navigateTo(0); // Start at the beginning
            });
        </script>

    </head>
<?php get_header(); ?>
    <body>

        <div class="top-content">

            <div class="inner-bg">
                <div class="container-fluid">

                    <h1 class="text-center">Registration Form</h1>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <!--action="action"-->


                                <div class="form-top">

                                    <div class="form-top-left">
                                        <h3>Step 2 / 2</h3>
                                        <p>Set up your account:</p>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-key"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">OTP</label>
                                        <div class="col-md-8">
                                            <input type="hidden" class="form-control" id="v_regmobile"  name="v_regmobile" value="<?php echo $regmobile; ?>" >
                                            <input type="hidden" class="form-control" id="v_dwempid "  name="v_dwempid" value="<?php echo $dwempid; ?>" >
                                           
                                            <input type="text" class="form-control" id="txtEnglish"  name="v_otp" placeholder="Enter One time password here." required="" >

                                            <p><a href="<?php echo site_url() . "/resendotp?v_dwempid=" . $dwempid."&v_regmobile=" . $regmobile; ?>"<b>Resend OTP</b></a></p>



                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Re-Send Verification Email </label>

                                        <div class="col-md-8">
                                            <input type="checkbox"  name="v_reemail" value="TRUE" style="width:30px;height:20px;">
                                            Send The Email about account confirmation 
                                        </div>
                                    </div>
                                </div>




                                <input type="submit" value="Verify" name=verify class="previous btn btn-info pull-right">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
<?php get_footer(); ?>
    </body>
</html>

