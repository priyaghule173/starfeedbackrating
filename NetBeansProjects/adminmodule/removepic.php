<?php

    /* Template Name: removepic
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

$dwempid=$_REQUEST['dwempid'];
        $sql= "UPDATE `dwemps` SET `photoname`=Null WHERE `dwempid`=".$dwempid."";
        //echo $sql;
        $result = dwqueryexec(__FUNCTION__, $sql);
     echo "<script>alert('Employer Photo remove successfully.'); document.location='" . site_url() ."/empprof?v_dwempid=".$dwempid."';</script>";

  ?>    