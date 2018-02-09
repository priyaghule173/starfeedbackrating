<?php

/*Template Name:DwEmp */

/*
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registeremp.php: Register the Employer
 */

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

if (isset($_REQUEST['Add'])) {
//print_r($_REQUEST);
 //  $empid=$_REQUEST["v_empid"];
$name = $_REQUEST["v_name"];
            $sql = " INSERT INTO `dwemps` SET `name` = '" . $name . "' ,";
                      
$sql .= genempSql();

    $result = dwqueryexec(__FUNCTION__, $sql);
  //   $empid = mysqli_insert_id($conn);
   $empid= mysqli_insert_id($GLOBALS['conn']);
  // echo $empid;
  // echo "<script>alert('Registration successfully. AideExpert Employee Id = " . $empid. "  ');</script>";
if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.');document.location='".site_url()."/dwemp'; </script>";

     // echo site_url() . '/dwemp';
        
        
} else {
   echo "<script>alert('Registration successfully. AideExpert Employee Id = " . $empid. "  ');document.location='".site_url()."/addcontract ?v_dwempid=".$empid."&v_name=".$name."';</script>";/* $url='addcontract.php';
*/  // echo site_url() . '/addcontract' ;
  /*
  $postvars=array(
       'empid'=>'$empid',
         'Add'=>'Add'
     );
     
     do_post_request($url,$postvars);
 */
    }

}

?>

<html>
 <head>
        <title>Employer Registration</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
              <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>
        
        
        
         <script type="text/javascript" src="https://www.google.com/jsapi"></script>

        <script type="text/javascript">
            google.load("elements", "1", {packages: "transliteration"});

            function OnLoad() {
                var options = {
                    sourceLanguage:
                            google.elements.transliteration.LanguageCode.ENGLISH,
                    destinationLanguage:
                            [google.elements.transliteration.LanguageCode.HINDI],
                    shortcutKey: 'ctrl+g',
                    transliterationEnabled: true
                };

                var control = new google.elements.transliteration.TransliterationControl(options);
                control.makeTransliteratable(["txtHindi"]);
                var keyVal = 32; // Space key
                $("#txtEnglish").on('keydown', function (event) {
                    if (event.keyCode === 32) {
                        var engText = $("#txtEnglish").val() + " ";
                        var engTextArray = engText.split(" ");
                        $("#txtHindi").val($("#txtHindi").val() + engTextArray[engTextArray.length - 2]);

                        document.getElementById("txtHindi").focus();
                        $("#txtHindi").trigger({
                            type: 'keypress', keyCode: keyVal, which: keyVal, charCode: keyVal
                        });
                    }
                });

                $("#txtHindi").bind("keyup", function (event) {
                    setTimeout(function () {
                        $("#txtEnglish").val($("#txtEnglish").val() + " ");
                        document.getElementById("txtEnglish").focus()
                    }, 0);
                });
            } //end onLoad function

            google.setOnLoadCallback(OnLoad);
        </script> 

              <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->  
        </head>
        <?php
            
 
        get_header(); ?>
         <body>
        <div class="container-fluid">
            <h1 class="text-center"> Employeer Registration Form</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="dwemp" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <!--action="action"-->
                
                    <!--<input type="hidden" name="v_empid" value="echo $empid;"/>
                        --><div class="form-group">
                           
                   <label class="control-label col-md-2">Employee Name:</label>

                            <div class="col-md-4">
                                <input type="text" name="v_name" class="form-control" id="txtEnglish"placeholder="Enter Employee Name" required="">
                            </div> 
                    <label class="control-label col-md-2">Employee Name Marathi:</label>

                            <div class="col-md-4">
                                <input type="text" name="v_nametts" class="form-control" id="txtHindi">
                            </div> 
                   </div>
                        
                 <div class="form-group">
                            <label class="control-label col-md-2">Address1</label>
                        
                                <div class="col-md-4">
                                <input type="text" name="v_address1" class="form-control" id="aaa" placeholder="Enter Employee Address1" required="">
                            </div>
                            <label class="control-label col-md-2">Address2</label>
                        
                                <div class="col-md-4">
                                <input type="text" name="v_address2" class="form-control" id="aaa" placeholder="Enter Employee Address2" >
                            </div>
                   
                   </div>  
                    <div class="form-group">
                         <label class="control-label col-md-2">pincode</label>
                        
                                <div class="col-md-4">
                                <input type="number" name="v_pincode" class="form-control" id="aaa" placeholder="Enter Employee pincode" required="" data-parsley-minlength="6" data-parsley-maxlength="6">
                            </div>
                         <label class="control-label col-md-2">Date Of Joining</label>
                        
                                <div class="col-md-4">
                                <input type="date" name="v_doj" class="form-control" id="aaa" placeholder="Enter Employee Date of Joining" >
                            </div>
                    </div>
                         <div class="form-group">
                            <label class="control-label col-md-2">Mobile Number</label>
                        
                                <div class="col-md-4">
                                <input type="number" name="v_regmobile" class="form-control" id="aaa" placeholder="Enter Employee Mobile Number" required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>
                   <label class="control-label col-md-2">Email Id</label>

                            <div class="col-md-4">
                                <input type="email" name="v_email" class="form-control" id="aaa" placeholder="Enter Employee Mail Id" >
                            </div> 
                   </div>  
                    <div class="form-group">
                            <label class="control-label col-md-2">Area</label>
                        
                                <div class="col-md-4">
                                   
                                    <?php
                                $allarea = getallArea();?>
                                <select name='v_area' id='mySelect' class='form-control' required='' onChange='check(this);'>"
                                <?php echo "<option value=''>----------Select------</option>";
                                for ($row = 0; $row < count($allarea); $row++) {

                                    $area = $allarea[$row]['area'];

                                    echo '<option value="' . $area . '"> ' . $area . '</option>';
                                }
                                echo "</select>";
                                ?>

                                </select>
                                    </div>
                             <label class="control-label col-md-2">City</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_city" id="v_city" required>
                                    <option value="">Select City</option>

                                    <option value="Pune">Pune</option>
                                    <option value="Mumbai">Mumbai</option>
                                    <option value="Delhi">Delhi</option>
                                </select>
                            </div>
                            
                                            </div>
                    
                     <div class="form-navigation">
                         <input  type="reset" id="Reset" name="Reset" value="Reset" class="btn btn-primary pull-left" >
                        <!-- <a href='contractdw.php.php?dwempid= echo $currentdw[0]['dwempid'] ?>&Add=Add'>
    --><input type="submit" id="Add" name="Add" value="Add"  class="btn btn-primary pull-right" ></a>

                                        </div>
</form>
                    
            </div>
        </div>
                     <?php get_footer(); ?>

             </body>
</html>
<?php
// Wrap up script
closeAll();
?>