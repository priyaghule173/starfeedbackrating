<?php
/* Template Name: UpdateBenefits */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * updbenfits.php: update the bank details i.e benefits of domestic worker
 *  *

 *  */
if (empty($_SERVER['CONTENT_TYPE'])) {
  $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
  } 

$ThisScript = basename(__FILE__, '.php');
//Common Functions


$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');

require_once('dwreglib.php');
if(!check_dwaccess('administrator')) {
    auth_redirect();
    
}

initconfig();

createConnection();
//print_r($_REQUEST);
$currentdw = array();
$currentdw1 = array();
if (isset($_REQUEST['search'])) {
    // print_r($_REQUEST);
    if ($_REQUEST["v_dwid"] == "") {
        if (isValiddwid("v_dwid") == false) {
            //Not valid dwid
            echo "<script>alert('Oops! Something went wrong. Please Search again..!!!'); document.location='".site_url()."/searchdw';</script>";
            // echo site_url() . '/registerdw' ;
        }
    } else {
        // Find if dwid is registered
        $dwid = $_REQUEST['v_dwid'];
        // print_r("dwid=".$dwid);
        $currentdw1 = getDWbydwid($dwid);
        // echo "currentdw1=";
        //print_r($currentdw1);
        if (count($currentdw1) < 1) {
             //Not valid dwid
            echo "<script>alert('Oops! Domestic Worker record not found..!!!');document.location='".site_url()."/dwemp';</script>";
           ///  echo site_url() . '/registerdw' ;
            //alert("Alert! Domestic Worker record not found!");
            exit;
        } else {


            //Check if DW benefits are already registered
            $currentdwb = getBenefitsbydwid($dwid);
            //  print_r($currentdwb);

            if (count($currentdwb) < 1) {
                //Benefits data not available
                $currentdwb = createBenefits($dwid);
                
            }
        }
    }
}
if (isset($_REQUEST['submit'])) {
    //  $sql = " INSERT INTO `benefits` SET `dwid` = '" . $dwid . "' ,";
    $sql = 'UPDATE `benefits` SET ';
    $sql .= genbenefitsql();
    $dwid = $_REQUEST['v_dwid'];
    $sql .= " WHERE `dwid`='" . $dwid . "'";
    //echo $sql;
    $result = dwqueryexec(__FUNCTION__, $sql);
    //echo "<script>alert('record updated successfully..!!!'); </script>";
// echo site_url() . '/searchdw' ;
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!');document.location='".site_url()."/searchdw'; </script>";
// echo site_url() . '/searchdw' ;
        }
    //Else If No DW found, give alert and go back
    else {

        echo "<script>alert('Record Successfully Updated.');document.location='".site_url()."/searchdw'; </script>";
   //echo site_url() . '/searchdw' ;
        }
}

//validate dwid recieved from get/GET
//search dwid in dws database to check this is valid dw
//check if benefits record exist in benefits table
//if no record exist create a blank record 
//if record exist initialize display variables from record data
//edithtml form
?>


<html>
    <head>
                <title>Update Benefits</title>

          <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
      
           <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>     
        
        
        
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>              

    </head>
    <?php 
               

    get_header();?>
    <body>
        
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

        <div class="container-fluid">
            <h1 class="text-center">Benefits of Domestic Worker</h1>
            <form class="form-horizontal demo-form" name="bdetails" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
                <!--action="action"-->
                <!--first form-->
                <div class="form-group">
                    <label class="control-label col-md-2">ID Number</label>
                    <div class="col-md-4">
                        <input type="text" name="v_dwid" class="form-control form-control-lg" id="aaa" value="<?php echo $currentdw1['dwid'] ?>" style="font-weight: bold;" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Bank Account Number</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="v_acno" value="<?php echo $currentdwb['acno'] ?>"  class="field-style field-split align-right"   placeholder="Enter Your Bank Account Number"  data-parsley-minlength="10" data-parsley-maxlength="14">
                    </div> 
                </div>

                <div class="form-group">

                    <label class="control-label col-md-2">Bank Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_bname" value="<?php echo $currentdwb['bname'] ?>"  class="field-style field-split align-left" placeholder="Enter Your Bank Here">
                    </div>
                </div>

                <div class="form-group">

                    <label class="control-label col-md-2">Bank Branch Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_branch" value="<?php echo $currentdwb['branch'] ?>" class="field-style field-split align-left" placeholder="Enter Your Bank Branch Name Here">
                    </div>
                </div>

                <div class="form-group">

                    <label class="control-label col-md-2">IFSC code</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control"  name="v_ifsc" value="<?php echo $currentdwb['ifsc'] ?>" class="field-style field-split align-left" placeholder="Enter Your Bank's IFSC code Here" pattern="/^[A-Za-z]{4}\d{7}$/" >
                        <!--pattern="/^[A-Za-z]{4}\d{7}$"/ MAHB0000954-->
                    </div>
                </div>


                <div class="col-xs-12">
                    <div class="table-responsive">           
                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Serial No</th>
                                    <th>Name</th>
                                    <th>Date Of Birth</th>
                                    <th>Gender</th>
                                    <th>Relationship</th>
                                    <th>Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <th ><input type="text" class="form-control" name="v_b1name"value="<?php echo $currentdwb['b1name'] ?>"  placeholder="Enter name." ></th>
                                    <td><input type="date" class="form-control"name="v_b1dob" value="<?php echo $currentdwb['b1dob'] ?>"id="datepicker" ></td>
                                    <td> <select class="form-control" name="v_b1gender" id="v_gender" >
                                            <option value="">Select Gender</option>
                                            <option value="Male"  <?php
if ($currentdwb['b1gender'] == "Male") {
    echo 'selected="selected"';
}
?>>Male</option>                               
                                            <option value="Female"  <?php
if ($currentdwb['b1gender'] == "Female") {
    echo 'selected="selected"';
}
?>>Female</option>
                                            <option value="Other"  <?php
                                            if ($currentdwb['b1gender'] == "Other") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Other</option>
                                        </select></td>
                                    <td><select class="form-control" name="v_b1relation" id="v_relation" >
                                            <option value="">Select Relationship</option>
                                            <option value="Self"  <?php
                                            if ($currentdwb['b1relation'] == "Self") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Self</option>                               
                                            <option value="Spouse"  <?php
                                                    if ($currentdwb['b1relation'] == "Spouse") {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Spouse</option>
                                            <option value="Daughter"  <?php
                                            if ($currentdwb['b1relation'] == "Daughter") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Daughter</option>
                                            <option value="Son" <?php
                                            if ($currentdwb['b1relation'] == "Son") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Son</option>
                                        </select></td>
                                    <td><input type="text" class="form-control" name="v_b1income" value="<?php echo $currentdwb['b1income'] ?>" placeholder="Enter income." ></td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <th ><input type="text" class="form-control" name="v_b2name"value="<?php echo $currentdwb['b2name'] ?>"  placeholder="Enter name." ></th>
                                    <td><input type="date" class="form-control"name="v_b2dob" value="<?php echo $currentdwb['b2dob'] ?>"id="datepicker" ></td>
                                    <td> <select class="form-control" name="v_b2gender" id="v_gender" >
                                            <option value="">Select Gender</option>
                                            <option value="Male"  <?php
                                                    if ($currentdwb['b2gender'] == "Male") {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Male</option>                               
                                            <option value="Female"  <?php
                                            if ($currentdwb['b2gender'] == "Female") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Female</option>
                                            <option value="Other"  <?php
                                            if ($currentdwb['b2gender'] == "Other") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Other</option>
                                        </select></td>
                                    <td><select class="form-control" name="v_b2relation" id="v_relation" >
                                            <option value="">Select Relationship</option>
                                            <option value="Self"  <?php
                                                    if ($currentdwb['b2relation'] == "Self") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Self</option>                               
                                            <option value="Spouse"  <?php
                                                    if ($currentdwb['b2relation'] == "Spouse") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Spouse</option>
                                            <option value="Daughter"  <?php
                                                    if ($currentdwb['b2relation'] == "Daughter") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Daughter</option>
                                            <option value="Son" <?php
                                                    if ($currentdwb['b2relation'] == "Son") {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Son</option>
                                        </select></td>
                                    <td><input type="text" class="form-control" name="v_b2income" value="<?php echo $currentdwb['b2income'] ?>" placeholder="Enter name." ></td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <th ><input type="text" class="form-control" name="v_b3name"value="<?php echo $currentdwb['b3name'] ?>"  placeholder="Enter name." ></th>
                                    <td><input type="date" class="form-control"name="v_b3dob" value="<?php echo $currentdwb['b3dob'] ?>"id="datepicker" ></td>
                                    <td> <select class="form-control" name="v_b3gender" id="v_gender" >
                                            <option value="">Select Gender</option>
                                            <option value="Male"  <?php
                                            if ($currentdwb['b3gender'] == "Male") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Male</option>                               
                                            <option value="Female"  <?php
                                            if ($currentdwb['b3gender'] == "Female") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Female</option>
                                            <option value="Other"  <?php
                                            if ($currentdwb['b3gender'] == "Other") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Other</option>
                                        </select></td>
                                    <td><select class="form-control" name="v_b3relation" id="v_relation" >
                                            <option value="">Select Relationship</option>
                                            <option value="Self"  <?php
                                            if ($currentdwb['b3relation'] == "Self") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Self</option>                               
                                            <option value="Spouse"  <?php
                                            if ($currentdwb['b3relation'] == "Spouse") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Spouse</option>
                                            <option value="Daughter"  <?php
                                                    if ($currentdwb['b3relation'] == "Daughter") {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Daughter</option>
                                            <option value="Son" <?php
                                            if ($currentdwb['b3relation'] == "Son") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Son</option>
                                        </select></td>
                                    <td><input type="text" class="form-control" name="v_b3income" value="<?php echo $currentdwb['b3income'] ?>" placeholder="Enter name." ></td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <th ><input type="text" class="form-control" name="v_b4name"value="<?php echo $currentdwb['b4name'] ?>"  placeholder="Enter name." ></th>
                                    <td><input type="date" class="form-control"name="v_b4dob" value="<?php echo $currentdwb['b4dob'] ?>"id="datepicker" ></td>
                                    <td> <select class="form-control" name="v_b4gender" id="v_gender" >
                                            <option value="">Select Gender</option>
                                            <option value="Male"  <?php
                                            if ($currentdwb['b4gender'] == "Male") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Male</option>                               
                                            <option value="Female"  <?php
                                                    if ($currentdwb['b4gender'] == "Female") {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Female</option>
                                            <option value="Other"  <?php
                                            if ($currentdwb['b4gender'] == "Other") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Other</option>
                                        </select></td>
                                    <td><select class="form-control" name="v_b4relation" id="v_relation" >
                                            <option value="">Select Relationship</option>
                                            <option value="Self"  <?php
                                            if ($currentdwb['b4relation'] == "Self") {
                                                echo 'selected="selected"';
                                            }
                                            ?>>Self</option>                               
                                            <option value="Spouse"  <?php
                                                    if ($currentdwb['b4relation'] == "Spouse") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Spouse</option>
                                            <option value="Daughter"  <?php
                                                    if ($currentdwb['b4relation'] == "Daughter") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Daughter</option>
                                            <option value="Son" <?php
                                                    if ($currentdwb['b4relation'] == "Son") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Son</option>
                                        </select></td>
                                    <td><input type="text" class="form-control" name="v_b4income" value="<?php echo $currentdwb['b4income'] ?>" placeholder="Enter income." ></td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <th ><input type="text" class="form-control" name="v_b5name"value="<?php echo $currentdwb['b5name'] ?>"  placeholder="Enter name." ></th>
                                    <td><input type="date" class="form-control"name="v_b5dob" value="<?php echo $currentdwb['b5dob'] ?>"id="datepicker" ></td>
                                    <td> <select class="form-control" name="v_b5gender" id="v_gender" >
                                            <option value="">Select Gender</option>
                                            <option value="Male"  <?php
                                                    if ($currentdwb['b5gender'] == "Male") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Male</option>                               
                                            <option value="Female"  <?php
                                                    if ($currentdwb['b5gender'] == "Female") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Female</option>
                                            <option value="Other"  <?php
                                                    if ($currentdwb['b5gender'] == "Other") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Other</option>
                                        </select></td>
                                    <td><select class="form-control" name="v_b5relation" id="v_relation" >
                                            <option value="">Select Relationship</option>
                                            <option value="Self"  <?php
                                                    if ($currentdwb['v_b5relation'] == "Self") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Self</option>                               
                                            <option value="Spouse"  <?php
                                                    if ($currentdwb['v_b5relation'] == "Spouse") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Spouse</option>
                                            <option value="Daughter"  <?php
                                                    if ($currentdwb['v_b5relation'] == "Daughter") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Daughter</option>
                                            <option value="Son" <?php
                                                    if ($currentdwb['v_b5relation'] == "Son") {
                                                        echo 'selected="selected"';
                                                    }
                                            ?>>Son</option>
                                        </select></td>
                                    <td><input type="text" class="form-control" name="v_b5income" value="<?php echo $currentdwb['b5income'] ?>" placeholder="Enter income." ></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-navigation">
                            <input type="reset" class="next btn btn-info pull-left" value="Reset"/>
                            <input type="submit" id="submit" name="submit" value="submit" class="btn btn-info pull-right">
                            <span class="clearfix"></span>
                        </div>
                    </div>
                 </div>
            </form>
        </div>
      <?php get_footer();?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>