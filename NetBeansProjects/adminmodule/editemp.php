<?php
/* Template Name:EditEmp */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * editemp.php: Edit the Employer record
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}
           

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
if(!check_dwaccess('administrator')) {
    auth_redirect();
    
}

initconfig();
//connecton will be created
createConnection();
//Search by regid or dwid or regmobile

if (isset($_REQUEST['Update'])) {
    $sql = 'UPDATE `dwemps` SET ';
    $sql .= genempSql();
    $dwempid = $_REQUEST['v_dwempid'];
    $sql .= " WHERE `dwempid`='" . $dwempid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
   
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter Employer Data again.');document.location='".site_url()."/searchemp'; </script>";
   // echo site_url() . '/searchemp' ;
        } else {

        echo "<script>alert('Employer Data updated successfully.'); document.location='".site_url()."/searchemp';</script>";
  //   echo site_url() . '/searchemp' ;
        
        }
}



$currentdw = array();
$currentdw1 = array();

       
            // Find if dwempid is registered
            $dwempid = $_REQUEST['v_dwempid'];
           // print_r("dwempid=" . $dwempid);
            $currentdw1 = getEmpDWbydwid($dwempid);
          //  print_r($currentdw1);
if (isset($_REQUEST['search'])) {
    // print_r($_REQUEST);
    if ($_REQUEST['v_dwempid'] !== "") {
        $dwempid = $_REQUEST['v_dwempid'];
        $sql = "SELECT * FROM `dwemps` WHERE `dwempid` ='" . $dwempid . "'";
    } else if ($_REQUEST["v_name"] !== "") {
        $name = $_REQUEST["v_name"];
        $sql = "SELECT * from `dwemps` WHERE `name`='" . $name . "'";
    } else if ($_REQUEST["v_regmobile"] !== "") {

        $regmobile = $_REQUEST["v_regmobile"];
        $sql = "SELECT * from `dwemps` WHERE `regmobile`='" . $regmobile . "'";
    }

    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //If Sql ERROR, give alert and go back 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!'); document.location='".site_url()."/searchemp';</script>";
         //echo site_url() . '/searchemp' ;

        
        
    }
    //Else If No DW found, give alert and go back
    else if($currentdw == NULL) {

        echo "<script>alert('Oops! Did not find matching Employer Data. Please search again.');document.location='".site_url()."/searchemp';</script>";
         //echo site_url() . '/searchemp' ;

        
        
    }
}

?>





<html>
 <head>
        <title>Edit Employer</title>
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
        <?php get_header(); ?>
         <body>
        <div class="container-fluid">
            <h1 class="text-center"> Employeer Registration Form</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="dwemp" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI'];  ?>">
                  <div class="form-group">
                           
                   <label class="control-label col-md-2">Employee Name:</label>

                            <div class="col-md-4">
                                <input type="text" name="v_name" class="form-control" value="<?php echo $currentdw[0]['name'] ?>" id="txtEnglish"placeholder="Enter Employee Name" required="">
                            </div> 
                   <label class="control-label col-md-2">Employee Name Marathi:</label>

                            <div class="col-md-4">
                                <input type="text" name="v_nametts" class="form-control" id="txtHindi" value="<?php echo $currentdw[0]['nametts'] ?>">
                            </div> 
                   </div>
                   
                        
                 <div class="form-group">
                            <label class="control-label col-md-2">Address1</label>
                        
                                <div class="col-md-4">
                                <input type="text" name="v_address1" class="form-control" id="aaa" placeholder="Enter Employee Address1" value="<?php echo $currentdw[0]['address1'] ?>"required="">
                            </div>
                            <label class="control-label col-md-2">Address2</label>
                        
                                <div class="col-md-4">
                                <input type="text" name="v_address2" class="form-control" id="aaa" placeholder="Enter Employee Address2" value="<?php echo $currentdw[0]['address2'] ?>" >
                            </div>
                   
                   </div>  
                    <div class="form-group">
                         <label class="control-label col-md-2">pincode</label>
                        
                                <div class="col-md-4">
                                <input type="number" name="v_pincode" class="form-control" id="aaa" placeholder="Enter Employee pincode" value="<?php echo $currentdw[0]['pincode'] ?>" required="" data-parsley-minlength="6" data-parsley-maxlength="6">
                            </div>
                         <label class="control-label col-md-2">Date Of Joining</label>
                        
                                <div class="col-md-4">
                                <input type="date" name="v_doj" class="form-control" id="aaa" placeholder="Enter Employee Date of Joining" value="<?php echo $currentdw[0]['doj'] ?>" >
                            </div>
                    </div>
                         <div class="form-group">
                            <label class="control-label col-md-2">Mobile Number</label>
                        
                                <div class="col-md-4">
                                <input type="number" name="v_regmobile" class="form-control" id="aaa" placeholder="Enter Employee Mobile Number" value="<?php echo $currentdw[0]['regmobile'] ?>" required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>
                   <label class="control-label col-md-2">Email Id</label>

                            <div class="col-md-4">
                                <input type="email" name="v_email" class="form-control"  placeholder="Enter Employee Mail Id" value="<?php echo $currentdw[0]['email'] ?>"  >
                            </div> 
                   </div>  
                    <div class="form-group">
                            <label class="control-label col-md-2">Area</label>
                        
                                 <div class="col-md-4">
                            <?php $allarea = getallArea(); ?>
                            <select name='v_area' id='mySelect' class='form-control'  onChange='check(this);'>
                            <option value="<?php echo $currentdw[0]['area'];?>"> <?php echo $currentdw[0]['area'];?> </option>   
 <?php
                              //  echo "<option value=''>" . $currentdw[0]['area'] . "</option>";
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

                                    <option value="Pune" <?php if ($currentdw[0]['city'] == "Pune") {
    echo 'selected="selected"';
} ?>>Pune</option>
                                    <option value="Mumbai" <?php if ($currentdw[0]['city'] == "Mumbai") {
    echo 'selected="selected"';
} ?> >Mumbai</option>
                                    <option value="Delhi" <?php if ($currentdw[0]['city'] == "Delhi") {
    echo 'selected="selected"';
} ?> >Delhi</option>
                                </select>
                            </div>
                            
                                            </div>
                    
                     <div class="form-navigation">
                         <a href="<?php echo site_url() . '/searchemp' ; ?>"> <button  type="button" class="btn btn pull-left" >Cancle</button></a>
                        <!-- <a href='contractdw.php.php?dwempid= echo $currentdw[0]['dwempid'] ?>&Add=Add'>
    --><input type="submit" id="Add" name="Update" value="Update"  class="btn btn pull-right" ></a>

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