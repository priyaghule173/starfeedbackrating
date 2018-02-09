<?php
/* Template Name:EmpReg */
?>
<html>
    <!--
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registerdw.html : Domestic Worker Registration field...
 *

 *  -->
    <head>
        <title>Aideexpert.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all'); // Inside a child theme
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
<script src='https://www.google.com/recaptcha/api.js'></script>
    <?php get_header(); ?>
    <body>
          <div class="top-content">

            <div class="inner-bg">
        <div class="container-fluid">
            <h1 class="text-center"> Domestic Worker Registration Form</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <div class="form-section">
                        <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Step 1 / 2</h3>
                                        <p>Tell us who you are:</p>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <div class="form-group">

                                        <label class="control-label col-md-4">Name</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="v_name"  name="v_name" placeholder="Enter Name here." required="" data-parsley-minlength="3" pattern="[a-zA-Z\s]+">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile Number</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control"  name="v_regmobile" id="v_regmobile" placeholder="Enter Mobile number" required="" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email</label>
                                        <div class="col-md-8">
                                            <input type="email" class="form-control"  name="v_email" id="v_email" placeholder="Enter Email" required="" >
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="v_city" id="v_city" required="">
                                                <option value="">Select City</option>

                                                <option value="Pune">Pune</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4"> &nbsp;</label>
                                        <div class="col-md-8">
                                            <input type="checkbox"  name="empregterms" value="empregterms" style="width:30px;height:20px;" required="">
                                            I Agree To <a href="/tandc">Terms & Conditions</a>
                                        </div>
                                    </div>

                                   
                                   <div class="form-group">
                                        <div class="col-md-12 text-center">


                                            <div class="g-recaptcha" data-theme="light"  data-sitekey="6LfsshsUAAAAAF02Y67nJ8ZA2ye2pQIoSHlyAzyZ" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
                                        </div>
                                    </div>

                        </div>

                    </div>
                    <div class="form-section">
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


                    </div>


                    <div class="form-navigation">
                        <button type="button" class="previous btn btn-info pull-left">&lt; Previous</button>
                        <button type="button" class="next btn btn-info pull-right">Next &gt;</button>
                        <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary pull-right">
                        <span class="clearfix"></span>
                    </div>


                </form>
            </div>
        </div>
                 </div>
        </div>
        <?php get_footer(); ?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>