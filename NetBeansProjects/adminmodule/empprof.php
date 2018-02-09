<?php
/* Template Name: empprofile
 */

if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
require_once('dwreglib.php');

$libpath = dirname(__FILE__);
//require_once ('empcommon.php');
require_once(ABSPATH . '/dwpivr/common.php');

initconfig();
createConnection();
$thisuser = wp_get_current_user();
dwerror_log(__FUNCTION__ . ":Curr wp user=" . $thisuser->user_email);
$sql = "SELECT * FROM `dwemps` WHERE `email` = '" . $thisuser->user_email . "'";
$result = dwqueryexec(__FUNCTION__, $sql);
if ($result->num_rows > 0) {
    $thisemployer = $result->fetch_assoc();
    $dwempid = $thisemployer['dwempid'];
    $currentdw = array();
    $sql = "SELECT * FROM `dwemps` WHERE `dwempid` ='" . $dwempid . "'";
    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (!empty($_FILES["v_photo"]["name"])) {
    $file_name = $_FILES["v_photo"]["name"];
    $temp_name = $_FILES["v_photo"]["tmp_name"];
    $imgtype = $_FILES["v_photo"]["type"];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_size = $_FILES['v_photo']['size'];
    $imagename = date("d-m-Y") . "-" . time() . "." . $ext;
    $target_path = get_stylesheet_directory() . "/images/" . $imagename;
    $fp = fopen($temp_name, 'r');
    $photo = fread($fp, filesize($temp_name));
//$photo = addslashes($photo);
    fclose($fp);
    $imgf = fopen($target_path, 'w');
    if ($imgf == NULL) {
        echo "<script>alert('ERROR: Cant upload profile photo to server!');document.location='registerdw.php'</script>";
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
}
if (isset($_REQUEST['submit'])) {

    
    if(empty($_FILES['v_photo']['name'])) //new image uploaded
{
   //process your image and data
     $dwempid = $thisemployer['dwempid'];
    $sql = 'UPDATE `dwemps` SET ';    
        $sql .= genempSql();

    $sql .= " WHERE `dwempid`='" . $dwempid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);

    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong');document.location='" . site_url() . "/empprofile'; </script>";
         //echo site_url() . '/searchemp' ;
    } else {

        echo "<script>alert('Employer Data updated successfully.'); document.location='" . site_url() ."/dashboard';</script>";
         //echo site_url() . '/searchemp' ;
    }
        
        
}
else // no image uploaded
{
   // save data, but no change the image column in MYSQL, so it will stay the same value

    
    $dwempid = $thisemployer['dwempid'];
    $sql = 'UPDATE `dwemps` SET ';
    $sql .= " `photoname` = '" . $imagename . "' ,";

    $sql .= genempSql();

    $sql .= " WHERE `dwempid`='" . $dwempid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);

    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong');document.location='" . site_url() . "/empprofile'; </script>";
         //echo site_url() . '/searchemp' ;
    } else {

        echo "<script>alert('Employer Data updated successfully.'); document.location='" . site_url() ."/dashboard';</script>";
         //echo site_url() . '/searchemp' ;
    }
}

    }
    
    
?>
<html>
<head>
    <title>Aideexpert.com</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>          
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .Container-fluid
        {
            text-align: center;
        }

        .Container:before
        {
            content: '';
            height: 100%;
            display: inline-block;
            vertical-align: middle;
        }
        .img-responsivee {

            max-width: 100px !important;
            max-height:200px!important;
        }
    </style>
</head>
<?php get_header(); ?>
<body>
    <div class="col-md-16">
        <div class="row">
            <div class="container-fluid">
                <h1 class="text-center"> My Profile</h1>
                <br>
                <!--                <div class="col-md-16">-->
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <div class="form-group">
                        
                         <?php
                     //   print_r($currentdw);
                         
                         
                        if ($currentdw[0]['photoname'] == '') 
                                    {?>                                
                                    <img class="img-responsive" style="display: block ; margin: 0 auto; width:100px;height:100px; "   src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png" alt="Image Title" border="0" />

                                                                           
                                    <?php    }
                                    
                                    else
                                        {?>
                                    <img class="img-responsive" style="display: block ; margin: 0 auto; width:100px;height:100px; "   src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo $currentdw[0]['photoname']; ?>" alt="Image Title" border="0" width="" height="" style="padding-right:44px;"/>
   <?php }?>
                        
                        
                        
                        
                        
               
                        <label class="control-label col-md-2" >Take or select photo(s)</label><br />
                        <div class="col-md-4">
                            <input type="hidden" name="source" value="10000000" />
                            <input type="file" name="v_photo" onClick="getSize();" accept="image/x-png, image/gif, image/jpeg" data-parsley-max-file-size="42"  />
                        </div>
                        
                        

                        
<a href="<?php echo site_url() . "/removepic?dwempid=".$dwempid ;?>"><label class="control-label" >Remove Profile Image</label></a>
                      

</div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control"  value="<?php echo $currentdw[0]['name'] ?>" name="v_name"  required="" pattern="[a-zA-Z\s]+">
                        </div>
                        <label class="control-label col-md-2">Address1</label>

                        <div class="col-md-4">
                            <input type="text" name="v_address1" class="form-control" value="<?php echo $currentdw[0]['address1'] ?>" id="aaa"  required="">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="control-label col-md-2">Pincode</label>

                        <div class="col-md-4">
                            <input type="number" name="v_pincode" class="form-control" value="<?php echo $currentdw[0]['pincode'] ?>" id="aaa" required="" data-parsley-minlength="6" data-parsley-maxlength="6">
                        </div>
                        <label class="control-label col-md-2">Date Of Joining</label>

                        <div class="col-md-4">
                            <input type="date" name="v_doj" class="form-control" value="<?php echo $currentdw[0]['doj'] ?>" id="aaa"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Mobile Number</label>

                        <div class="col-md-3">
                            <input type="number" name="v_regmobile" class="form-control" value="<?php echo $currentdw[0]['regmobile'] ?>" id="aaa"  required="" data-parsley-minlength="10" data-parsley-maxlength="10">
                        </div>
                        <?php
                        if (($currentdw[0]['mobverify']) == 1) {
                            ?>  
                            <div class="col-md-1">
                                <input type="checkbox" data-toggle="tooltip" onclick="return false;" title="Verified" style="width:30px;height:20px;"   name="mobverify" value="1" <?php echo "checked"; ?>>
                            </div> 
                            <?php
                        } else {
                            ?>
                         <div class="col-md-1">
                            <a href="<?php echo site_url() . "/empreg2?v_dwempid=" . $thisemployer['dwempid'] ?>">Click Here for Mobile Verify</a>                                         
                         </div>                
 <?php }
                        ?>
                        <label class="control-label col-md-2">Email Id</label>

                        <div class="col-md-4">
                            <input type="email" name="v_email" class="form-control" value="<?php echo $currentdw[0]['email'] ?>" id="aaa"  >
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
                            <select class="form-control" name="v_city" id="v_city" >
                                <option value="">Select City</option>

                                <option value="Pune" <?php
                                if ($currentdw[0]['city'] == "Pune") {
                                    echo 'selected="selected"';
                                }
                                ?>>Pune</option>
                            </select>
                        </div>
                    </div>
                                    <form class="form-horizontal demo-form" enctype="multipart/form-data" name="registration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                    <input type="submit" value="Submit" name="submit" >
                    <button type="button" class="  pull-right">Cancel</button>
                    <a href="<?php echo site_url() . "/dashboard"  ?>"><button type="button" class="  pull-left" >Back</button></a>


                    
                </form>
                <!--                </div>-->
            </div>

        </div>
    </div>
</div>
<?php get_footer(); ?>
</body>
</html>