<?php
/* Template name: UpdateContract */
/*
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * updatecontracts.php: Update the contract of Dw and Employer
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
if (isset($_REQUEST['Update'])) {
    $sql = 'UPDATE `contracts` SET ';
    $sql .= gencontractSql();
    $contractid = $_REQUEST['v_contractid'];
    $sql .= " WHERE `contractid`='" . $contractid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);

    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter the data again.');document.location='".site_url()."/searchcontract'; </script>";
 //echo site_url() . '/searchcontract' ;
        } else {

        echo "<script>alert('Contract is Updated successfully.');document.location='".site_url()."/searchcontract'; </script>";
         //echo site_url() . '/searchcontract' ;
    }
}
?>