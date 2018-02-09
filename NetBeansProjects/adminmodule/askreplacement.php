<?php
/* Template Name:askreplacement */

if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
//Common Functions

$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
initconfig();
createConnection();
$currempreplacement = array();
$askreplacement1 = array();
$contractdetails = array();
$currUnitjob = array();
$dwid = $_REQUEST['v_dwid'];
//print_r("dwid=" . $dwid);
$dwempid = $_REQUEST['v_dwempid'];
$contractid = $_REQUEST['v_contractid'];
$currdate = $_REQUEST['v_date'];
$olddw = getDWbydwid($dwid);
//print_r($olddw);
$askreplacement1 = getAlternateDw($dwid);

$contractdetails = getUnitjob($contractid, $currdate);

if (isset($_REQUEST['submit'])) {
    //checking dwid is present
    $sql = "SELECT * FROM `unitjobs` WHERE `contractid`='" . $contractid . "' AND `jobdate`='" . $currdate . "'";
    $result = dwqueryexec(__FUNCTION__, $sql);
    $currUnitjob = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //print_r($currUnitjob);
    if (($currUnitjob[0]['status'] == 'inprogress') || ($currUnitjob[0]['status'] == 'completed')) {
        echo "<script>alert('Your domestic worker marked as present .So you cannot ask for replacement. " . "  ');document.location='" . site_url() . "/dashboard';</script>";
    } else {

        $confirm1 = $_REQUEST['v_confirm1'];
        $confirm2 = $_REQUEST['v_confirm2'];


        if (isset($confirm1)) {
            $currempreplacement = getEMPbyID($dwempid);
            //  print_r($currempreplacement);
            //old dw
            $dwfullmsg = "You marked as absent your alternative domestic worker 1) Name:" . $askreplacement1[0]['name'] . "   Mobile no:" . $askreplacement1[0]['regmobile'] . ".";
            sendSMS($olddw['regmobile'], $dwfullmsg);

//alt new
            $altdwfullmsg = "You are alternative domestic worker for " . $currempreplacement[0]['name'];

            sendSMS($askreplacement1[0]['regmobile'], $altdwfullmsg);

            //employer msg
            $empfullmsg = "Your confirmed  domestic helper 1) Name:" . $askreplacement1[0]['name'] . "   Mobile no:" . $askreplacement1[0]['regmobile'] . "call her contract it.";


            sendSMS($currempreplacement[0]['regmobile'], $empfullmsg);
            sendDWmail($currempreplacement[0]['email'], "", "AlternateDW", $empfullmsg);
            $updsql = "UPDATE  `unitjobs` SET  `altdw` ='" . $askreplacement1[0]['dwid'] . "',`status`='orphan' WHERE  `contractid` ='" . $contractid . "' AND  `jobdate` = '" . $currdate . "'";
        }
        if (isset($confirm2)) {

            //old dw
            $dwfullmsg = "You marked as absent your alternative domestic worker 1) Name:" . $askreplacement1[1]['name'] . "   Mobile no:" . $askreplacement1[1]['regmobile'] . ".";

            sendSMS($olddw['regmobile'], $dwfullmsg);

//alt new
            $altdwfullmsg = "You are alternative domestic worker for " . $currempreplacement[0]['name'];

            sendSMS($askreplacement1[1]['regmobile'], $altdwfullmsg);

            $fullmsg = "Your suggested  domestic helper 1) Name:" . $askreplacement1[1]['name'] . "  Mobile no:" . $askreplacement1[1]['regmobile'] . "call her contract it.";
            sendDWmail($currempreplacement[0]['email'], "", "AlternateDW", $fullmsg);
            $currempreplacement = getEMPbyID($dwempid);
            $updsql = "UPDATE  `unitjobs` SET  `altdw` ='" . $askreplacement1[1]['dwid'] . "',`status`='orphan' WHERE  `contractid` ='" . $contractid . "' AND  `jobdate` = '" . $currdate . "'";
        }

        $update = dwqueryexec(__FUNCTION__, $updsql);
        //echo "<script>alert('Your request for replacement is successfully created. " . "  ');document.location='".site_url()."/dashboard';</script>";
    }
}
?>
<html>
    <!--
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registerdw.html : Domestic Worker Registration field...
 *

 *  -->
    <head>
        <title>Domestic Worker Registration</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all');
?>"/>
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                        

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
        <div class="container-fluid">
            <h1 class="text-center">Ask Replacement</h1>
            <br>

            <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <!--action="action"-->
                <div class="form-section">
                    <label class="control-label col-md-2">Select Date</label>
                    <div class="col-md-4">
                        <?php
                        $mydate = date('Y-m-d');
                        $tomorrow = date("Y-m-d", strtotime("+1 day"));
                        ?>
                        <select name='v_date' id='mySelect' class='form-control' required='' onChange='check(this);'>"
                            <?php
                            echo "<option value=''>----------Select------</option>";
                            echo '<option value="' . $mydate . '"> ' . $mydate . '</option>';
                            echo '<option value="' . $tomorrow . '"> ' . $tomorrow . '</option>';
                            ?>
                        </select>                                
                    </div>
                    
<!--                    <a href="tel:5551234567"><img src="callme.jpg" /></a>-->
                </div>
                <div class="col-md-12">
                    <div class="form-section">

                        <div class="form-group">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-6 text-center">
                                    <!--                                                         <section class="hidden-xs">-->
                                        <?php if ($askreplacement1[0]['photoname'] == '') {
                                            ?>                                
                                            <img class="img-responsive" style="height:70px; width:70px;margin: 0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />
                                            <?php
                                        } else {
                                            ?>
                                            <img alt="aideexpert" style="height:70px;width:70px;margin: 0 auto;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $askreplacement1[0]['photoname']; ?>">
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--</section>-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                         
                                        <label class="control-label col-md-8"> Name</label>

                                        <div class="col-md-12">
                                            <input type="text" name="v_name" value="<?php echo $askreplacement1[0]['name'] ?>"  class="form-control" id="v_name" disabled>

                                        </div>
                                        <label class="control-label col-md-8">Phone</label>
                                        <div class="col-md-12">
                                            <input type="text" name="v_phone" class="form-control" value="<?php echo $askreplacement1[0]['regmobile'] ?>" id="v_regmobile" disabled="">
                                            <br>
<!--                                         <a href="tel:5551234567"><img src="callme.jpg" /></a>-->
                                            <a class="mobilesOnly" href="tel:<?php echo $askreplacement1[0]['regmobile']?>" >
                                              <img class="img-responsive" style="height:30px; width:30px;margin: 0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/call.png" alt="Image Title" border="0" />
                                         
                                               </a>
                                       
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label ><input type="checkbox"  name="v_confirm1" value="confirm" >confirmed</label>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-6 text-center">
                                        <!--                                                         <section class="hidden-xs">-->
                                            <?php if ($askreplacement1[1]['photoname'] == '') {
                                                ?>                                
                                                <img class="img-responsive" style="height:70px; width:70px;margin: 0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />
                                                <?php
                                            } else {
                                                ?>
                                                <img alt="aideexpert" style="height:70px;width:70px;margin: 0 auto;"src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $askreplacement1[1]['photoname']; ?>">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!--</section>-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-8"> Name</label>

                                            <div class="col-md-12">
                                                <input type="text" name="v_name" value="<?php echo $askreplacement1[1]['name'] ?>"  class="form-control" id="v_name" disabled>

                                            </div>
                                            <label class="control-label col-md-8">Phone</label>
                                            <div class="col-md-12">
                                                <input type="text" name="v_phone" class="form-control" value="<?php echo $askreplacement1[1]['regmobile'] ?>" id="v_regmobile" disabled>
                                                <br>
                                                <a class="mobilesOnly" href="tel:<?php echo $askreplacement1[1]['regmobile']?>" >
                                              <img class="img-responsive" style="height:30px; width:30px;margin: 0 auto" src="<?php bloginfo('stylesheet_directory'); ?>/images/call.png" alt="Image Title" border="0" />
                                         
                                               </a>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="checkbox">
                                                <label ><input type="checkbox"  name="v_confirm2" value="confirm" >confirmed</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>




                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-navigation">
                    <button type="button" class="previous btn pull-left" style="
    background-color: #3BACA9 !important;
">&lt; Previous</button>
                    <button type="button" class="next btn pull-right" style="
    background-color: #3BACA9 !important;
">Next &gt;</button>
                    <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary pull-right">
                    <span class="clearfix"></span>
                </div>




            </form>




            <?php get_footer(); ?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>