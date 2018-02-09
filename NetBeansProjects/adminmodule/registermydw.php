<?php
/* Template Name:RegisterMyDw */

/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registerdw.php: Register the Domestic Worker
 *

 *  */
 if (empty($_SERVER['CONTENT_TYPE'])) {
  $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
  } 

$ThisScript = basename(__FILE__, '.php');
//Common Functions
        


$libpath = dirname(__FILE__);
require_once(ABSPATH. '/dwpivr/common.php');
require_once('dwreglib.php');


initconfig();

createConnection();


if (!empty($_FILES["v_photo"]["name"])) {



    $file_name = $_FILES["v_photo"]["name"];

    $temp_name = $_FILES["v_photo"]["tmp_name"];

    $imgtype = $_FILES["v_photo"]["type"];

    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_size = $_FILES['v_photo']['size'];
    $imagename = date("d-m-Y") . "-" . time() . "." . $ext;

    $target_path = get_stylesheet_directory(). "/images/" . $imagename;

    $fp = fopen($temp_name, 'r');
    $photo = fread($fp, filesize($temp_name));
//$photo = addslashes($photo);
    fclose($fp);

    $imgf = fopen($target_path, 'w');

    if ($imgf == NULL) {
        echo "<script>alert('ERROR: Cant upload profile photo to server!');</script>";
        exit;
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

if (isset($_REQUEST['submit'])) {

    switch ($_REQUEST['v_dwidtype']) {
        case '1':
            $dwid = $_REQUEST["v_dwid1"];
            $sql = " INSERT INTO `dws` SET `dwid` = '" . $dwid . "' ,";
            break;

        case '2':
            $dwid = $_REQUEST["v_dwid2"];
            $sql = " INSERT INTO `dws` SET `dwid` = '" . $dwid . "' ,";
            break;

        case '3':
            $dwid = $_REQUEST["v_dwid3"];
            $sql = " INSERT INTO `dws` SET `dwid` = '" . $dwid . "' ,";
            break;
    }



   

    $sql .= " `photoname` = '" . $imagename . "' ,";






    //Generate Sql from the REQUEST variables from registerdw form submit
    $sql .= gendwSql();

    //INSERT new record
    $result = dwqueryexec(__FUNCTION__, $sql);



    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.'); document.location='".site_url()."/registermydw'</script>";
      
        
    } else {
        //Insert successful, get Registration id, and display message
        $sql = "SELECT * from `dws` WHERE `dwid`='" . $dwid . "'";
        $result = dwqueryexec(__FUNCTION__, $sql);
        $myregid = mysqli_fetch_all($result, MYSQLI_ASSOC);

 $msg="नमस्कार, संघमित्रा घरकामगार गटा मधे आपले स्वागत आहे. आपली घरकामगार गटा मधे नोंदणी करण्यात आलेली आहे";
         sendSms($myregid[0]['regmobile'],$msg);
       // echo "<script>alert('Domestic Worker registration successfully. AideExpert Registration Id = ". $myregid[0]['regid'] . "');document.location='".site_url()."/contractbyemp?v_dwid='".$dwid."'</script>";
                
                 echo "<script>alert(' Registration successfully, with register id=".$myregid[0]['regid']."'); document.location='".site_url()."/contractbyemp?v_dwid=".$dwid."'</script>";
  
               
   
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
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); 
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

    </head>
    
    <?php    get_header();?>
    <body>
        <div class="container-fluid">
            <h1 class="text-center"> Domestic Worker Registration Form</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <!--action="action"-->
                    <div class="form-section">
                        <!--first form-->
                        <div class="form-group">
                            <label class="control-label col-md-2">Id Type</label>
                            <div class="col-md-4">
                                <label>
                                    <input type="radio" id="chkOne" name="v_dwidtype" value="1" required="false">Adhar Id</label>
                                <label><input type="radio" id="chkTwo" name="v_dwidtype" value="2" required="false">Voter Id</label>
                                <label>   <input type="radio" id="chkThree" name="v_dwidtype" value="3" required="false">PanCard Id
                                </label>
                            </div>
                            <label class="control-label col-md-2">ID Number</label>
                            <div class="col-md-4" id="One" style="display: none">
                                <input type="number" name="v_dwid1" class="form-control" id="aaa" placeholder="Enter Adhar Id" required="" data-parsley-minlength="12" data-parsley-maxlength="12">
                            </div>
                            <div class="col-md-4"  id="Two" style="display: none">
                                <input type="text" name="v_dwid2" class="form-control" id="bbb" placeholder="Enter Voter Id" required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>
                            <div class="col-md-4"  id="Three" style="display: none">
                                <input type="text" name="v_dwid3" class="form-control" id="ccc" placeholder="Enter PanCard Id" required="" data-parsley-minlength="10" data-parsley-maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                            </div>

                        </div>
                        <!--form-group 1 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txtEnglish"  name="v_name" placeholder="Enter Domestic Worker Name." required="" pattern="[a-zA-Z\s]+">
                            </div>

                            <label class="control-label col-md-2">Name in  Devnagri</label>
                            <div class="col-md-4" >
                                <input type="text" name="v_nametts"  class="form-control" id="txtHindi" >
                            </div>
                        </div>
                        <!--form-group 4 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Mobile Number</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="v_regmobile" id="v_dwno" placeholder="Enter Mobile No." required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>

                            <label class="control-label col-md-2">Date of Birth</label>
                            <div class="col-md-4">
                                <input type="date" name="v_dob" class="form-control" id="v_date" required="">
                            </div>
                        </div>
                        <!--form-group 5 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Address 1</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="3" name="v_address1" id="v_address1" placeholder="Enter Address One." required=""></textarea>
                            </div>

                            <label class="control-label col-md-2">Address 2</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="3" name="v_address2"id="v_address2" placeholder="Enter Address here." ></textarea>
                            </div>
                        </div>
                        <!--form-group 6 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2" for="v_gender">Gender</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_gender" id="v_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <label class="control-label col-md-2">Email</label>
                            <div class="col-md-4">
                                <input type="email" class="form-control"  name="v_email" id="v_email" placeholder="Enter Email">
                            </div>
                        </div>
                        <!--form-group 7 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Marital Status</label>
                            <div class="col-md-4">
                                <select class="form-control" name="v_marital" id="v_mstatus" required>
                                    <option value="">Select maritial status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>

                                </select>
                            </div>

                            <label class="control-label col-md-2">Employee Status</label>
                            <div class="col-md-4">
                                <label >
                                    <input type="radio" name="v_empstatus" value="Y" >Looking For A Job</label>
                                <label> <input type="radio" name="v_empstatus" value="N">Not Looking for Job
                                </label>
                            </div>
                        </div>
                        <!--form-group 8 ends-->

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




                            </div>
                        </div>
                        <div>
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
                        <!--form-group 9 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Pincode</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="v_pincode" id="v_pincode" placeholder="Enter 6 digit Pincode" data-parsley-minlength="6" data-parsley-maxlength="6" required="">
                            </div>
                        </div>
                        <!--form-group 10 ends-->
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-2">Group Leader Id</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="v_dwsupportid"  id="v_leadid" placeholder="Enter Group Leader Id" >
                            </div>

                            <label class="control-label col-md-2">Group Leader Name</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="v_supportname" id="v_leadname" placeholder="Enter Group Leader Name"  >
                            </div>
                        </div>
                        <!--form-group 2 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Group Leader Mobile No.</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control"  name="v_supportmobile" id="v_leadno" placeholder="Enter Leader Mobile No." data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>

                            <label class="control-label col-md-2">Select Group Name</label>

                            <div class="col-md-4">
                                <input type="text" name="v_groupname" class="form-control" id="v_groupname" placeholder="Enter Group Name">

                            </div>
                        </div>
                        <!--form-group 3 ends-->

                        <hr>

                        <!--first form ends-->
                    </div>

                    <div class="form-section">


                        <div class="form-group">
                            <label class="control-label col-md-2">Select Language</label>
                            <div class="col-md-2">
                                <select class="form-control" name="v_lang1" id="v_prof1" >

                                    <option value="">Select</option>
                                    <option value="Marathi">Marathi</option>
                                    <option value="Hindi">Hindi</option>
                                    <option value="English">English</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_prof1[]" value="R" >Read</label>

                                    <label ><input type="checkbox" name="v_prof1[]"  value="W">Write</label>

                                    <label ><input type="checkbox" name="v_prof1[]" value="S" >Speak</label>
                                </div>  
                            </div>

                            <div class="col-md-2">
                                <select class="form-control" name="v_lang2" id="v_prof2" >

                                    <option value="">Select</option>
                                    <option value="Marathi">Marathi</option>
                                    <option value="Hindi">Hindi</option>
                                    <option value="English">English</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox" name="v_prof2[]" value="R">Read</label>

                                    <label ><input type="checkbox" name="v_prof2[]" value="W">Write</label>

                                    <label ><input type="checkbox" name="v_prof2[]" value="S" >Speak</label>
                                </div>   
                            </div>
                        </div>                                      
                        <!--form-group 1 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Select Proficiency</label>
                            <div class="col-md-2">
                                <select class="form-control" name="v_lang3" id="v_prof3">

                                    <option value="">Select</option>
                                    <option value="Marathi">Marathi</option>
                                    <option value="Hindi">Hindi</option>
                                    <option value="English">English</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_prof3[]" value="R">Read</label>

                                    <label ><input type="checkbox" name="v_prof3[]" value="W">Write</label>

                                    <label><input type="checkbox"  name="v_prof3[]" value="S" >Speak</label>
                                </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->


                        <div class="form-group">
                            <label class="control-label col-md-2">Education details</label>
                            <div class="col-md-8">
                                <label >
                                    <input type="radio" name="v_edu" value="NE" >Not Educated
                                </label>
                                <label >
                                    <input type="radio" name="v_edu" value="PM">Primary
                                </label>
                                <label >
                                    <input type="radio"  name="v_edu" value="TP">10th Pass
                                </label>
                                <label >
                                    <input type="radio" name="v_edu" value="GD">Graduated
                                </label>
                            </div>
                        </div>

                        <!--form-group 1 ends-->
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-2">Mobile Literacy details</label>
                            <div class="col-md-8">
                                <label>
                                    <input type="radio" name="v_mliteracy" value="CC" required>Can Call Only
                                </label>
                                <label >
                                    <input type="radio" name="v_mliteracy" value="CR" >Can Read SMS
                                </label>
                                <label>
                                    <input type="radio" name="v_mliteracy" value="CS">Can Send SMS 
                                </label>
                                <label >
                                    <input type="radio" name="v_mliteracy"value="SM">SmartPhone
                                </label>
                            </div>
                        </div>


                        <!--form-group 2 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Appliance Details</label>
                            <div class="col-md-8">
                                <div class="checkbox">
                                    <label ><input type="checkbox" name="v_appliances[]" value="VM" required="">Vaccum Cleaner</label>

                                    <label ><input type="checkbox"name="v_appliances[]" value="WM">Washing Machine</label>

                                    <label ><input type="checkbox"  name="v_appliances[]" value="MW">Microwave</label>

                                    <label ><input type="checkbox"  name="v_appliances[]" value="MX">Mixer</label>
                                    <label ><input type="checkbox"  name="v_appliances[]" value="DW">Dish Washer</label>


                                </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->

                        <div class="form-group">
                            <label class="control-label col-md-2">Major Working Skills</label>
                            <div class="col-md-8">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_majorskills[]"value="DH" data-parsley-mincheck="1" required="">धुनी</label>

                                    <label ><input type="checkbox" name="v_majorskills[]" value="PO">पोल्या</label>

                                    <label><input type="checkbox" name="v_majorskills[]" value="BH" >भांडी</label>

                                    <label ><input type="checkbox" name="v_majorskills[]" value="CO">स्वयपाक</label>
                                    <label ><input type="checkbox" name="v_majorskills[]" value="EW">अधीक काम</label>
                                    <label><input type="checkbox" name="v_majorskills[]" value="CH">मुलाची देखरेख</label>
                                </div> 
                            </div>
                        </div>                                      
                        <!--form-group 3 ends-->

                        <div class="form-group">

                            <label class="control-label col-md-2">Working Since</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="v_worksince" id="v_ddate">
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="control-label col-md-2">Date of Joining</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="v_doj" id="v_doj" >
                            </div>
                        </div>
                        <!--form-group 10 ends-->

                        <div class="form-group">

                            <label class="control-label col-md-2" >Take or select photo(s)</label><br />
                            <div class="col-md-4">
                                <input type="hidden" name="source" value="10000000" />
                                <input type="file" name="v_photo" onClick="getSize();" accept="image/x-png, image/gif, image/jpeg" data-parsley-max-file-size="42"  />


                            </div>
                        </div>   




                    </div>

                    <div class="form-navigation">
                        <button type="button" class="back  pull-left">&lt; Back</button>
                        <button type="button" class="previous btn  pull-left">&lt; Previous</button>
                        <button type="button" class="next btn pull-right">Next &gt;</button>
                        <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary pull-right">
                        <span class="clearfix"></span>
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
