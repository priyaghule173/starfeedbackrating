<?php
/* Template Name:EmployerReg1 */

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

if (isset($_REQUEST['register'])) {
    //************wpcode**/******/////
     $regmobile = $_REQUEST['v_regmobile'];
    $data= mysqli_query($GLOBALS['conn'],"SELECT `regmobile` FROM `dwemps` WHERE `regmobile` ='" . $regmobile . "'");
    $checkrows=mysqli_num_rows($data);
   if($checkrows>0) {
            echo "<script>alert(' Mobile Number Already Exist. Please enter Valid Mobile Number.'); document.location='" . site_url() . "/empreg';</script>";

                //echo "<script>alert('Please enter a number!');document.location='" . site_url() . "/empreg';</script>";
        }else{
     
        
    wp_logout();
    $name = $wpdb->escape($_REQUEST['v_name']);


    $email = $wpdb->escape($_REQUEST['v_email']);
    if ($_REQUEST) {
        if (!is_email($email)) {
            echo "<script>alert('Please enter a valid email!');document.location='" . site_url() . "/empreg';</script>";
            $errors['v_email'] = "Please enter a valid email";
        } elseif (email_exists($email)) {
            echo "<script>alert('This email address is already in use!');document.location='" . site_url() . "/empreg';</script>";

            $errors['v_email'] = "This email address is already in use";
        } 
        $name = $_REQUEST['v_name'];
        $email = $_REQUEST['v_email'];
        $errors = register_new_user($name, $email);
        $user_id = register_new_user($name, $email);
//wp_update_user( array( 'ID' => $user_id, 'role' => 'administrator' ) );
        $myuser = new WP_User($user_id);
        $myuser->add_role('customer');
        if (!is_wp_error($errors)) {

  
            
            $name = $_REQUEST["v_name"];
            $sql = " INSERT INTO `dwemps` SET `name` = '" . $name . "' ,";
            $sql .= genempSql();
            $sql = rtrim($sql, ", ");

            $result = dwqueryexec(__FUNCTION__, $sql);

            //   $empid = mysqli_insert_id($conn);
            $empid = mysqli_insert_id($GLOBALS['conn']);

            if ($GLOBALS['conn']->error) {
                //Error
                echo "<script>alert(' Oops! something went wrong. Please enter Employer data again.'); document.location='" . site_url() . "/empreg';</script>";
            } else {
                createOTP($regmobile);
                echo "<script>document.location='" . site_url() . "/empreg2?v_dwempid=" . $empid . "& v_regmobile=" . $regmobile . "&register=Register'</script>";
            }
        
    }else {

            echo "<script>alert(' Error: In creating wordpress user. Please enter Employer data again.'); document.location='" . site_url() . "/empreg';</script>";
        }
        }
        
    }
}

?>

<html>
    <!--
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * empreg1.php : Quick Employer Registration field...
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

                    <h1 class="text-center">Registration Form</h1>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            <form class="form-horizontal demo-form" enctype="multipart/form-data" name="empregistration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <!--action="action"-->
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
                            <label class="control-label col-md-4">Area</label>
                            <div class="col-md-8">
                                <?php
                                $allarea = getallArea();?>
                                <select name='v_area' id='mySelect' class='form-control' required='' onChange='check(this);'>"
                                <?php echo "<option value=''>----------Select------</option>";
                                for ($row = 0; $row < count($allarea); $row++) {

                                    $area = $allarea[$row]['area'];

                                    echo '<option value="' . $area . '"> ' . $area . '</option>';
                                }
                               
                                ?>

              </select>


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

                                    <div class="form-group">
                                        <div class="col-md-12 text-center">

                                            <input type="submit" value="Register"  name=register class=" btn btn-info">
                                        </div>     </div>   </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
</body>
</html>