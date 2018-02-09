<?php
/* Template Name:EditDw */

/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * editdw.php: Edit the Domestic Worker record
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

if (isset($_REQUEST['submit'])) {
    
    
    if (!empty($_FILES["v_photo"]["name"])) {



    $file_name = $_FILES["v_photo"]["name"];

    $temp_name = $_FILES["v_photo"]["tmp_name"];

    $imgtype = $_FILES["v_photo"]["type"];

    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_size = $_FILES['v_photo']['size'];
    $imagename = date("d-m-Y") . "-" . time() . "." . $ext;

    $target_path = get_stylesheet_directory()."/images/" . $imagename;

    $fp = fopen($temp_name, 'r');
    $photo = fread($fp, filesize($temp_name));
//$photo = addslashes($photo);
    fclose($fp);

    $imgf = fopen($target_path, 'w');

    if ($imgf == NULL) {
        echo "<script>alert('ERROR: Cant upload profile photo to server!');document.location='".site_url()."/editdw'; </script>";
       //  echo site_url() . '/searchdw' ;
       
    } else {
        $path = fwrite($imgf, $photo, $file_size);
    }


    if (!get_magic_quotes_gpc()) {
        $photo = addslashes($photo);
    }

    if ($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
        echo "<script>alert(' Sorry, only JPG, JPEG & PNG files are allowed!');</script>";
    }

    echo "<script>alert(' Image Uploaded Successfully!');</script>";

    //if(move_uploaded_file($temp_name, $target_path)) {
}
 if(empty($_FILES['v_photo']['name'])) //new image uploaded
{
   //process your image and data
    $sql = 'UPDATE `dws` SET ';
   
  $sql .= gendwSql();
    
    $dwid = $_REQUEST['v_dwid'];
    $sql .= " WHERE `dwid`='" . $dwid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
    //echo $sql; 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.');document.location='".site_url()."/searchdw'; </script>";
 
         echo site_url() . '/searchdw' ;
        
    } else {

        echo "<script>alert('Domestic Worker updated successfully.');document.location='".site_url()."/searchdw'; </script>";
    
 echo site_url() . '/searchdw' ;        
        
    }
}
else // no image uploaded
{
   // save data, but no change the image column in MYSQL, so it will stay the same value

  $sql = 'UPDATE `dws` SET ';
   
    $sql .= " `photoname` = '" . $imagename . "' ,";
  $sql .= gendwSql();
    
    $dwid = $_REQUEST['v_dwid'];
    $sql .= " WHERE `dwid`='" . $dwid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
    //echo $sql; 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.');document.location='".site_url()."/searchdw'; </script>";
 
         echo site_url() . '/searchdw' ;
        
    } else {

        echo "<script>alert('Domestic Worker updated successfully.');document.location='".site_url()."/searchdw'; </script>";
    
 echo site_url() . '/searchdw' ;        
        
    }
}

    
}


if (isset($_REQUEST['search'])) {
   
    if ($_REQUEST['v_regid'] !== "") {
        $regid = $_REQUEST['v_regid'];
        $sql = "SELECT * FROM `dws` WHERE `regid` ='" . $regid . "'";
    } 
    else if ($_REQUEST["v_dwid"] !== "") 
    {
        $dwid = $_REQUEST["v_dwid"];
        $sql = "SELECT * from `dws` WHERE `dwid`='" . $dwid . "'";
    } 
    else if ($_REQUEST["v_regmobile"] !== "")
    {
        
        $regmobile = $_REQUEST["v_regmobile"];
        $sql = "SELECT * from `dws` WHERE `regmobile`='" . $regmobile . "'";
     
    }
} 
$result = dwqueryexec(__FUNCTION__, $sql);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
     $prof1=array_map('trim',explode(",",$currentdw[0]['prof1']));
     $prof2=array_map('trim',explode(",",$currentdw[0]['prof2']));
     $prof3=array_map('trim',explode(",",$currentdw[0]['prof3']));

 $appl=array_map('trim',explode(",",$currentdw[0]['appliances']));
    
    $skills =array_map('trim',explode(",",$currentdw[0]['majorskills']));
  
    //If Sql ERROR, give alert and go back 
   if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!');document.location='".site_url()."/searchdw'; </script>";
 
         echo site_url() . '/searchdw' ;
        
   }
   //Else If No DW found, give alert and go back
    else if ($currentdw == NULL) {

        echo "<script>alert('Oops! Did not find matching domestic worker. Please search again.'); document.location='".site_url()."/searchdw';</script>";
    echo site_url() . '/searchdw' ;
        }
?>
<html>
    <head>
        <title>Edit Domestic Worker</title>
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
        <script type="text/javascript">
            window.Parsley.addValidator('maxFileSize', {
                validateString: function (_value, maxSize, parsleyInstance) {
                    if (!window.FormData) {
                        alert('You are making all developpers in the world cringe. Upgrade your browser!');
                        return true;
                    }
                    var files = parsleyInstance.$element[0].files;
                    return files.length != 1 || files[0].size <= maxSize * 1024;
                },
                requirementType: 'integer',
                messages: {
                    en: 'This file should not be larger than %s Kb',
                    fr: 'Ce fichier est plus grand que %s Kb.'
                }
            });



        </script>
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

        <script type="text/javascript">
            $(function () {
                $("input[name='v_dwidtype']").click(function () {

                    if ($("#chkOne").is(":checked")) {
                        $("#One").show();
                        $("#Two").hide();
                        $("#Three").hide();
                        $("#aaa").attr("required", true);
                        $("#bbb").attr("required", false);
                        $("#ccc").attr("required", false);
                    } else if ($("#chkTwo").is(":checked")) {
                        $("#One").hide();
                        $("#Two").show();
                        $("#Three").hide();
                        $("#aaa").attr("required", false);
                        $("#bbb").attr("required", true);
                        $("#ccc").attr("required", false);
                    } else if ($("#chkThree").is(":checked")) {
                        $("#One").hide();
                        $("#Two").hide();
                        $("#Three").show();
                        $("#aaa").attr("required", false);
                        $("#bbb").attr("required", false);
                        $("#ccc").attr("required", true);
                    } else {
                        $("#One").hide();
                        $("#Two").hide();
                        $("#Three").hide();
                        $("#aaa").attr("required", "false");
                        $("#bbb").attr("required", "false");
                        $("#ccc").attr("required", "false");
                    }
                });
            });
        </script>
        
        <script type="text/javascript">
            document.getElementByName("registration").submit();
            document.registration.submit();

            $(function () {
                $("#form").submit(function (e) {
                    e.preventDefault(); // prevent the form from being submitted normally
                    $.ajax({
                        type: "post",
                        // url: "registerdw.php",
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response == "done") {
                                alert("stuff done");
                            } else {
                                alert("isset wasn't true");
                            }
                        },
                        error: function (response) {
                            alert("server returned a 500 error or something");
                        }
                    });
                });
            })();
        </script>
        <script>
            function getSize()
            {
                var myFSO = new ActiveXObject("Scripting.FileSystemObject");
                var filepath = document.upload.file.value;
                var thefile = myFSO.getFile(filepath);
                var size = thefile.size;
                alert(size + " bytes");
            }
        </script>

        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->
    </head>  
        <?php  
        
                

        get_header();?>

    <body>
        <div class="container-fluid">
            <h1 class="text-center"> Domestic Worker Update Form</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" name="editdw" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <!--action="action"-->
                    <div class="form-section">
                        <!--first form-->
                        <div class="form-group">
                            <label class="control-label col-md-2">Id Type</label>
                            <div class="col-md-4">
                                <label>
                                    <input type="radio" id="chkOne" name="v_dwidtype" readonly  style="font-weight: bold;" value="1" <?php if ($currentdw[0]['dwidtype'] == '1') {
    echo ' checked ';
} ?> required="" >Adhar Id</label>
                                <label><input type="radio" id="chkTwo" name="v_dwidtype" readonly  style="font-weight: bold;" value="2" <?php if ($currentdw[0]['dwidtype'] == '2') {
    echo ' checked ';
} ?> required="" >Voter Id</label>
                                <label>   <input type="radio" id="chkThree" name="v_dwidtype"  style="font-weight: bold;" readonly value="3" <?php if ($currentdw[0]['dwidtype'] == '3') {
    echo ' checked ';
} ?> required="" >PanCard Id
                                </label>
                            </div>
                            <label class="control-label col-md-2">ID Number</label>
                            <div class="col-md-4" id="One" >
                                <input type="text" name="v_dwid" class="form-control form-control-lg" id="aaa" value="<?php echo $currentdw[0]['dwid'] ?>"   style="font-weight: bold;" readonly>
                            </div>


                        </div>
                        <!--form-group 1 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txtEnglish"  name="v_name" value="<?php echo $currentdw[0]['name'] ?>" placeholder="Enter Domestic Worker Name." required="" pattern="[a-zA-Z\s]+">
                            </div>

                            <label class="control-label col-md-2">Name in  Devnagri</label>
                            <div class="col-md-4">
                                <input type="text" name="v_nametts" value="<?php echo $currentdw[0]['nametts'] ?>" class="form-control" id="txtHindi" >
                            </div>
                        </div>
                        <!--form-group 4 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Mobile Number</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="v_regmobile" id="v_regmobile" value="<?php echo $currentdw[0]['regmobile'] ?>" placeholder="Enter Mobile No." required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>

                            <label class="control-label col-md-2">Date of Birth</label>
                            <div class="col-md-4">
                                <input type="date" name="v_dob" class="form-control" value="<?php echo $currentdw[0]['dob']; ?>" id="v_date" >
                            </div>
                        </div>
                        <!--form-group 5 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Address 1</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="3" name="v_address1" id="v_address1" placeholder="Enter Address One." required=""><?php echo $currentdw[0]['address1'] ?></textarea>
                            </div>

                            <label class="control-label col-md-2">Address 2</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="3" name="v_address2"id="v_address2" placeholder="Enter Address here." ><?php echo $currentdw[0]['address2'] ?></textarea>
                            </div>
                        </div>
                        <!--form-group 6 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2" for="v_gender">Gender</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_gender" id="v_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male"  <?php if ($currentdw[0]['gender'] == "M") {
    echo 'selected="selected"';
} ?> >Male</option>
                                    <option value="Female"  <?php if ($currentdw[0]['gender'] == "F") {
    echo 'selected="selected"';
} ?>>Female</option>
                                    <option value="Other"  <?php if ($currentdw[0]['gender'] == "O") {
    echo 'selected="selected"';
} ?>>Other</option>
                                </select>
                            </div>

                            <label class="control-label col-md-2">Email</label>
                            <div class="col-md-4">
                                <input type="email" class="form-control"  name="v_email" id="v_email"   value="<?php echo $currentdw[0]['email'] ?>"  placeholder="Enter Email">
                            </div>
                        </div>
                        <!--form-group 7 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Marital Status</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_marital" id="v_mstatus" required>
                                    <option value="">Select maritial status</option>
                                    <option value="Single"  <?php if ($currentdw[0]['marital'] == "Single") {
    echo 'selected="selected"';
} ?> >Single</option>
                                    <option value="Married"  <?php if ($currentdw[0]['marital'] == "Married") {
    echo 'selected="selected"';
} ?> >Married</option>
                                    <option value="Divorced" <?php if ($currentdw[0]['marital'] == "Divorced") {
    echo 'selected="selected"';
} ?> >Divorced</option>

                                </select>
                            </div>

                            <label class="control-label col-md-2">Employee Status</label>
                            <div class="col-md-4">
                                <label >
                                    <input type="radio" name="v_empstatus" required="" value="Y" <?php echo ($currentdw[0]['empstatus'] == "Y") ? 'checked' : ''; ?> >Looking For A Job</label>
                                <label> <input type="radio" name="v_empstatus" value="N" <?php echo ($currentdw[0]['empstatus'] == "N") ? 'checked' : ''; ?> >Not Looking for Job
                                </label>
                            </div>
                        </div>
                        <!--form-group 8 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">City</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_city" id="v_city" required>
                                    <option value="">Select City</option>

                                    <option value="Pune" <?php if ($currentdw[0]['city'] == "Pune") {
    echo 'selected="selected"';
} ?>>Pune</option>
                                    <option value="Mumbai" <?php if ($currentdw[0]['city'] == "Mumbai") {
    echo 'selected="selected"';
} ?>>Mumbai</option>
                                    <option value="Delhi" <?php if ($currentdw[0]['city'] == "Delhi") {
    echo 'selected="selected"';
} ?>>Delhi</option>
                                </select>
                            </div>

                            <label class="control-label col-md-2">Area</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_area" id="v_area" >
                                    <option value="<?php echo $currentdw[0]['area'];?>"> <?php echo $currentdw[0]['area'];?> </option>
                                     <?php
                                $allarea = getallArea();
                               
                                for ($row = 0; $row < count($allarea); $row++) {

                                    $area = $allarea[$row]['area'];

                                    echo '<option value="' . $area . '"> ' . $area . '</option>';
                                }
                                echo "</select>";
                                ?>
                                    
                            </div>
                        </div>
                        <!--form-group 9 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Pincode</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="v_pincode" id="v_pincode" value="<?php echo $currentdw[0]['pincode'] ?>" placeholder="Enter 6 digit Pincode" data-parsley-minlength="6" data-parsley-maxlength="6" required="">
                            </div>
                        </div>
                        <!--form-group 10 ends-->
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-2">Group Leader Id</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="v_dwsupportid"  id="v_leadid" value="<?php echo $currentdw[0]['dwsupportid'] ?>" placeholder="Enter Group Leader Id" >
                            </div>

                            <label class="control-label col-md-2">Group Leader Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="v_supportname" id="v_leadname"  value="<?php echo $currentdw[0]['supportname'] ?>" placeholder="Enter Group Leader Name" required="">
                            </div>
                        </div>
                        <!--form-group 2 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Group Leader Mobile No.</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control"  name="v_supportmobile" id="v_leadno" value="<?php echo $currentdw[0]['supportmobile'] ?>" placeholder="Enter Leader Mobile No." required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>

                            <label class="control-label col-md-2">Enter Group Name</label>

                            <div class="col-md-4">
                                <input type="text" name="v_groupname" class="form-control" id="v_groupname" value="<?php echo $currentdw[0]['groupname'] ?>" placeholder="Enter Group Name" required="">

                            </div>
                        </div>
                        <!--form-group 3 ends-->

                        <hr>

                        <!--first form ends-->
                    </div>

                    <div class="form-section">


                        <div class="form-group">
                            <label class="control-label col-md-2">Select Proficiency</label>
                            <div class="col-md-2">
                                <select class="form-control" name="v_lang1" id="v_prof1" >
                                    <option></option>
                                    <option value="Marathi" <?php if ($currentdw[0]['lang1'] == "Marathi") {
    echo 'selected="selected"';
} ?> >Marathi</option>
                                    <option value="Hindi" <?php if ($currentdw[0]['lang1'] == "Hindi") {
    echo 'selected="selected"';
} ?> >Hindi</option>
                                    <option value="English" <?php if ($currentdw[0]['lang1'] == "English") {
    echo 'selected="selected"';
} ?> >English</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_prof1[]" value="R" <?php if(in_array("R",$prof1)){ echo "checked"; }?> >Read</label>

                                    <label ><input type="checkbox" name="v_prof1[]"  value="W" <?php if(in_array("W",$prof1)){ echo "checked"; }?>>Write</label>

                                    <label ><input type="checkbox" name="v_prof1[]" value="S" <?php if(in_array("S",$prof1)){ echo "checked"; }?>>Speak</label>
                                </div>  
                            </div>

                            <div class="col-md-2">
                                <select class="form-control" name="v_lang2" id="v_prof2" >
                                    <option></option>
                                    <option value="Marathi" <?php if ($currentdw[0]['lang2'] == "Marathi") {
    echo 'selected="selected"';
} ?> >Marathi</option>
                                    <option value="Hindi" <?php if ($currentdw[0]['lang2'] == "Hindi") {
    echo 'selected="selected"';
} ?>>Hindi</option>
                                    <option value="English"  <?php if ($currentdw[0]['lang2'] == "English") {
    echo 'selected="selected"';
} ?> >English</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox" name="v_prof2[]" value="R" <?php if(in_array("R",$prof2)){ echo "checked"; }?> >Read</label>

                                    <label ><input type="checkbox" name="v_prof2[]" value="W" <?php if(in_array("W",$prof2)){ echo "checked"; }?> >Write</label>

                                    <label ><input type="checkbox" name="v_prof2[]" value="S" <?php if(in_array("S",$prof2)){ echo "checked"; }?> >Speak</label>
                                </div>   
                            </div>
                        </div>                                      
                        <!--form-group 1 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Select Proficiency</label>
                            <div class="col-md-2">
                                <select class="form-control" name="v_lang3" id="v_prof3">
                                    <option></option>
                                    <option value="Marathi" <?php if ($currentdw[0]['lang3'] == "Marathi") {
    echo 'selected="selected"';
} ?> >Marathi</option>
                                    <option value="Hindi" <?php if ($currentdw[0]['lang3'] == "Hindi") {
    echo 'selected="selected"';
} ?> >Hindi</option>
                                    <option value="English" <?php if ($currentdw[0]['lang3'] == "English") {
    echo 'selected="selected"';
} ?> >English</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_prof3[]" value="R" <?php if(in_array("R",$prof3)){ echo "checked"; }?> >Read</label>

                                    <label ><input type="checkbox" name="v_prof3[]" value="W" <?php if(in_array("W",$prof3)){ echo "checked"; }?> >Write</label>

                                    <label><input type="checkbox"  name="v_prof3[]" value="S" <?php if(in_array("S",$prof3)){ echo "checked"; }?> >Speak</label>
                                </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Education details</label>
                            <div class="col-md-8">
                                <label >
                                    <input type="radio" name="v_edu" value="NE" <?php echo ($currentdw[0]['edu'] == "NE") ? 'checked' : ''; ?> >Not Educated
                                </label>
                                <label >
                                    <input type="radio" name="v_edu" value="PM" <?php echo ($currentdw[0]['edu'] == "PM") ? 'checked' : ''; ?> >Primary
                                </label>
                                <label >
                                    <input type="radio"  name="v_edu" value="TP" <?php echo ($currentdw[0]['edu'] == "TP") ? 'checked' : ''; ?>>10th Pass
                                </label>
                                <label >
                                    <input type="radio" name="v_edu" value="GD" <?php echo ($currentdw[0]['edu'] == "GD") ? 'checked' : ''; ?>>Graduated
                                </label>
                            </div>
                        </div>

                        <!--form-group 1 ends-->
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-2">Mobile Literacy details</label>
                            <div class="col-md-8">
                                <label>
                                    <input type="radio" name="v_mliteracy" value="CC" <?php echo ($currentdw[0]['mliteracy'] == "CC") ? 'checked' : ''; ?> >Can Call Only
                                </label>
                                <label >
                                    <input type="radio" name="v_mliteracy" value="CR" <?php echo ($currentdw[0]['mliteracy'] == "CR") ? 'checked' : ''; ?>>Can Read SMS
                                </label>
                                <label>
                                    <input type="radio" name="v_mliteracy" value="CS" <?php echo ($currentdw[0]['mliteracy'] == "CS") ? 'checked' : ''; ?>>Can Send SMS 
                                </label>
                                <label >
                                    <input type="radio" name="v_mliteracy"value="SM" <?php echo ($currentdw[0]['mliteracy'] == "SM") ? 'checked' : ''; ?>>SmartPhone
                                </label>
                            </div>
                        </div>


                        <!--form-group 2 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Appliance Details</label>
                            <div class="col-md-8">
                                <div class="checkbox">
                                    <label ><input type="checkbox" name="v_appliances[]" value="VM"  <?php if(in_array("VM",$appl)){ echo "checked"; }?> >Vaccum Cleaner</label>

                                    <label ><input type="checkbox"name="v_appliances[]" value="WM"  <?php if(in_array("WM",$appl)){ echo "checked"; }?> >Washing Machine</label>

                                    <label ><input type="checkbox"  name="v_appliances[]" value="MW"  <?php if(in_array("MW",$appl)){ echo "checked"; }?> >Microwave</label>

                                    <label ><input type="checkbox"  name="v_appliances[]" value="MX"  <?php if(in_array("MX",$appl)){ echo "checked"; }?> >Mixer</label>
                                    <label ><input type="checkbox"  name="v_appliances[]" value="DW"  <?php if(in_array("DW",$appl)){ echo "checked"; }?> >Dish Washer</label>


                                </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Major Working Skills</label>
                            <div class="col-md-8">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_majorskills[]"value="DH" <?php if(in_array("DH",$skills)){ echo "checked"; }?> >धुनी</label>

                                    <label ><input type="checkbox" name="v_majorskills[]" value="PO" <?php if(in_array("PO",$skills)){ echo "checked"; }?> >पोल्या</label>

                                    <label><input type="checkbox" name="v_majorskills[]" value="BH" <?php if(in_array("BH",$skills)){ echo "checked"; }?> >भांडी</label>
                                    <label ><input type="checkbox" name="v_majorskills[]" value="CO" <?php if(in_array("CO",$skills)){echo "checked"; }?>>स्वयपाक</label>
                                    <label ><input type="checkbox" name="v_majorskills[]" value="EW" <?php if(in_array("EW",$skills)){echo "checked"; }?>>अधीक काम</label>
                                    <label><input type="checkbox" name="v_majorskills[]" value="CH" <?php if(in_array("CH",$skills)){echo "checked"; }?> >मुलाची देखरेख</label> </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->

                        <div class="form-group">

                            <label class="control-label col-md-2">Working Since</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="v_worksince" id="v_ddate" value="<?php echo $currentdw[0]['worksince']; ?>" >
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="control-label col-md-2">Date of Joining</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="v_doj" id="v_doj" value="<?php echo $currentdw[0]['doj']; ?>" >
                            </div>
                        </div>
                        <!--form-group 10 ends-->


      <div class="form-group">            
<div class="col-md-4">
                      <?php echo $currentdw[0]['photoname'] ?>
                     <!-- <td><img src="images/ <p $currentdw[0]['photoname'] ?>" />-->
         <!--<img src="  <?p echo get_stylesheet_directory();?>/images/ <?ph $currentdw[0]['photoname'] ;?>" />-->
 
    <img style=" margin:0; width:100px; height:100px; display: block;" src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname'] ;?>" alt="Image Title" border="0"  />

</div> </div><div class="form-group">    
                            <label class="control-label col-md-2" >Take or select photo(s)</label><br />
                            <div class="col-md-4">
                     <input type="hidden" name="v_photo" value="<?php echo $currentdw[0]['photoname'] ;?>" />

                                <input type="file" name="v_photo"  onClick="getSize();" accept="image/x-png, image/gif, image/jpeg" data-parsley-max-file-size="42" capture="camera" />


                            </div>
                      </div>      
</div>
                         
                        <hr>
                        
                    </div>
<div class="form-navigation">
                    <div class="form-group">
                     <div class="container">
                         <div class="left">
                             <input type="button" class="previous btn btn pull-left" value="< Previous"/></div>
                             <div class="centre">
                               <a href="<?php echo site_url() .'/searchdw';?> ">  <input type="button" id="reg" name="register" value="Cancle"   class="btn btn-info"></a></div>
             
                                <button type="button" class="next btn btn pull-right">Next &gt; </button>
                               <div class="right">
                  
                        <input type="submit" id="submit" name="submit" value="submit" class="btn btn-primary pull-right">
                </div>
                     </div>
                </div>
                   </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
<!--                    <div class="form-navigation">
                        <button type="button" class="previous btn btn pull-left">&lt; Previous</button>
                        <button type="button" class="next btn btn pull-right">Next &gt; </button>
                        
                        <input type="submit" id="submit" name="submit" value="submit" class="btn btn-primary pull-right">
                        <span class="clearfix"></span>
                    </div>-->

                </form>


            </div>
        </div>

    <?php    get_footer();?>

    </body>
</html>

<?php
// Wrap up script
closeAll();
?>
