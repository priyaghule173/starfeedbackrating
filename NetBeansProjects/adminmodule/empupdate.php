<?php
/* Template Name:EmpUpdate */
/*
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * empupdate.php: Update the employer record
 */
            

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
if (isset($_REQUEST['Update'])) {
    $sql = 'UPDATE `dwemps` SET ';
    $sql .= genempSql();
    $dwempid = $_REQUEST['v_dwempid'];
    $sql .= " WHERE `dwempid`='" . $dwempid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
  
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter domestic worker data again.');document.location='".site_url()."/searchemp'; </script>";
    } else {

        echo "<script>alert('Domestic Worker updated successfully.');document.location='".site_url()."/searchemp';</script>";
    }
}
?>