<?php
/* Template Name:DwUpdate */
/*
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * dwupdate.php: Update the Domestic Worker
 */
  


if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}
$ThisScript = basename(__FILE__, '.php');
//Common Functions
$libpath = dirname(__FILE__);
require_once($libpath . '/../dwpivr/common.php');
require_once('dwreglib.php');
if(!check_dwaccess('administrator')) {
    auth_redirect();
    
}



initconfig();
createConnection();
if (isset($_REQUEST['submit'])) {
    
    
    if (!empty($_FILES["v_photo"]["name"])) {



    $file_name = $_FILES["v_photo"]["name"];

    $temp_name = $_FILES["v_photo"]["tmp_name"];

    $imgtype = $_FILES["v_photo"]["type"];

    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_size = $_FILES['v_photo']['size'];
    $imagename = date("d-m-Y") . "-" . time() . "." . $ext;

    $target_path = "./images/" . $imagename;

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

    echo "<script>alert(' Image Uploaded Successfully!');</script>";

    //if(move_uploaded_file($temp_name, $target_path)) {
}

    $sql = 'UPDATE `dws` SET ';
   

    $sql .= " `photoname` = '" . $imagename . "' ,";


    $sql .= gendwSql();
    
    
    
    $dwid = $_REQUEST['v_dwid'];
    $sql .= " WHERE `dwid`='" . $dwid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
    //echo $sql; 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.'); document.location='".site_url()."/searchdw';</script>";
    } else {

        echo "<script>alert('Domestic Worker updated successfully.'); document.location='".site_url()."/searchdw';</script>";
    }
}
?>