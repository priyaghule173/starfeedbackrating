<?php
/*
  Template Name: test
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
            }  </style>

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
        </head>
    <?php get_header(); ?>
    <body>
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

</body>
</html>
